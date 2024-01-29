<?php

namespace App\Http\Controllers\Member;

use App\Exports\BannerExport;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\Controller;
use App\Http\Requests\Banner\BannerCreateRequest;
use App\Http\Requests\Banner\BannerUpdateRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class BannerController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:BANNER_READ')->only('index');
        $this->middleware('permission:BANNER_CREATE')->only(['create', 'store']);
        $this->middleware('permission:BANNER_EDIT')->only(['edit', 'update']);
        $this->middleware('permission:BANNER_DELETE')->only('delete');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('member.banner.index');
    }

    public function ajax(Request $request)
    {
        $data = Banner::query()
            ->where('status', $request->status);

        if ($request->filled('export')) {

            activity()
                ->causedBy(Auth::id())
                ->useLog('banner export')
                ->log(request()->ip());

            return Excel::download(new BannerExport($data->get(), $request->all()), 'BANNER.xlsx');
        }

        return DataTables::eloquent($data)
            ->addColumn('gambar', function ($e) {
                if ($e->banner) {
                    return '<img src="' . url('/storage' . '/' . $e->banner) . '" class="img-fluid rounded">';
                } else {
                    return '-';
                }
            })
            ->addColumn('aksi', function ($e) {
                $user = User::find(Auth::id());

                $btnEdit = $user->hasPermissionTo('BANNER_EDIT') ? '<a href="' . route('banner.edit', ['banner' => $e->id]) . '" class="btn btn-xs "><i class="bx bx-edit"></i></a>' : '';
                $btnDelete = $user->hasPermissionTo('BANNER_DELETE') ?  '<a href="' . route('banner.destroy', ['banner' => $e->id]) . '" data-title="' . $e->banner . '" class="btn btn-xs text-danger btn-hapus"><i class="bx bx-trash"></i></a>' : '';
                $btnReload = $user->hasPermissionTo('BANNER_EDIT') ? '<a href="' . route('banner.destroy', ['banner' => $e->id]) . '" data-title="' . $e->banner . '" data-status="' . $e->status . '" class="btn btn-outline-secondary btn-xs btn-hapus"><i class="bx bx-refresh"></i></i></a>' : '';

                if ($e->status == true) {
                    return $btnEdit . ' ' . $btnDelete;
                } else {
                    return $btnReload;
                }
            })
            ->rawColumns(['gambar', 'aksi'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('member.banner.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BannerCreateRequest $request)
    {
        DB::beginTransaction();
        try {
            Banner::create($request->only(['banner', 'keterangan']));
            DB::commit();

            return redirect()->route('banner.create')->with('pesan', '<div class="alert alert-success">Data berhasil ditambahkan</div>');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::warning($th->getMessage());
            return redirect()->back()->with('pesan', '<div class="alert alert-danger">Terjadi kesalahan, cobalah kembali</div>');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Anggota  $anggota
     * @return \Illuminate\Http\Response
     */
    public function show(Banner $banner)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Anggota  $anggota
     * @return \Illuminate\Http\Response
     */
    public function edit(Banner $banner)
    {
        return view('member.banner.edit', compact('banner'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Anggota  $anggota
     * @return \Illuminate\Http\Response
     */
    public function update(BannerUpdateRequest $request, Banner $banner)
    {
        DB::beginTransaction();
        try {
            Banner::where('id', $banner->id)->update($request->only(['banner', 'keterangan']));
            DB::commit();

            return redirect()->route('banner.index')->with('pesan', '<div class="alert alert-success">Data berhasil diperbarui</div>');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::warning($th->getMessage());
            return redirect()->back()->with('pesan', '<div class="alert alert-danger">Terjadi kesalahan, cobalah kembali</div>');
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function destroy(Banner $banner)
    {
        $status = $banner->status;
        DB::beginTransaction();
        try {
            if ($status == true) {
                Banner::find($banner->id)->update(['status' => false]);
            } else {
                Banner::find($banner->id)->update(['status' => true]);
            }

            DB::commit();

            return response()->json([
                'pesan' => 'Data berhasil dihapus',
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::warning($th->getMessage());

            return response()->json([
                'pesan' => 'Terjadi kesalahan'
            ], 500);
        }
    }
}
