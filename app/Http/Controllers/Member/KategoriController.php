<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Exports\KategoriExport;
use App\Http\Requests\Kategori\KategoriCreateRequest;
use App\Http\Requests\Kategori\KategoriUpdateRequest;
use App\Models\Kategori;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class KategoriController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:KATEGORI_READ')->only('index');
        $this->middleware('permission:KATEGORI_CREATE')->only(['create', 'store']);
        $this->middleware('permission:KATEGORI_EDIT')->only(['edit', 'update']);
        $this->middleware('permission:KATEGORI_DELETE')->only('delete');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('member.kategori.index');
    }

    public function ajax(Request $request)
    {
        $cari = $request->cari;

        $data = Kategori::query()
            ->when($cari, function ($e, $cari) {
                $e->where('kategori', 'like', '%' . $cari . '%')->orWhere('kode', 'like', '%' . $cari . '%');
            })
            ->where('status', cekStatus($request->status));

        if ($request->filled('export')) {

            activity()
                ->causedBy(Auth::id())
                ->useLog('kategori export')
                ->log(request()->ip());

            return Excel::download(new KategoriExport($data->get()), 'KATEGORI.xlsx');
        }

        return DataTables::eloquent($data)
            ->addColumn('aksi', function ($e) {
                $user = User::find(Auth::id());

                $btnEdit = $user->hasPermissionTo('KATEGORI_EDIT')
                    ? ($e->status == true ? '<li><a href="' . route('kategori.edit', ['kategori' => $e->uuid]) . '" class="dropdown-item"><i class="bx bx-pencil"></i> Edit</a></li>' : '')
                    : '';

                $btnDelete = $user->hasPermissionTo('KATEGORI_DELETE')
                    ? ($e->status == true ? '<li><a href="' . route('kategori.destroy', ['kategori' => $e->uuid]) . '" data-title="' . $e->kategori . '" class="dropdown-item btn-hapus"><i class="bx bx-trash"></i> Delete</a></li>' : '')
                    : '';

                $btnReload = $user->hasPermissionTo('KATEGORI_EDIT')
                    ? ($e->status == false ?  '<li><a href="' . route('kategori.destroy', ['kategori' => $e->uuid]) . '" data-title="' . $e->kategori . '" data-status="' . $e->status . '" class="dropdown-item btn-hapus"><i class="bx bx-refresh"></i></i> reset</a></li>' : '')
                    : '';

                return '<div class="btn-group float-end" role="group" aria-label="Button group with nested dropdown">
                            <div class="btn-group" role="group">
                                <button id="btnGroupDrop1" type="button" class="badge border text-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    setting
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="btnGroupDrop1">
                                    ' . $btnEdit . '
                                    ' . $btnDelete . '
                                    ' . $btnReload . '
                                </ul>
                            </div>
                        </div>';
            })
            ->editColumn('status', fn ($e) =>  statusTable($e->status))
            ->rawColumns(['aksi', 'status'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('member.kategori.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(KategoriCreateRequest $request)
    {
        DB::beginTransaction();
        try {
            Kategori::create($request->only(['kategori']));
            DB::commit();

            return redirect()->route('kategori.create')->with('pesan', '<div class="alert alert-success">Data berhasil ditambahkan</div>');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::warning($th->getMessage());
            return redirect()->back()->with('pesan', '<div class="alert alert-danger">Terjadi kesalahan, cobalah kembali</div>');
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Kategori  $kategori
     * @return \Illuminate\Http\Response
     */
    public function show(Kategori $kategori)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Kategori  $kategori
     * @return \Illuminate\Http\Response
     */
    public function edit($kategori)
    {
        $kategori = Kategori::where('uuid', $kategori)->firstOrFail();

        return view('member.kategori.edit', compact('kategori'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Kategori  $kategori
     * @return \Illuminate\Http\Response
     */
    public function update(KategoriUpdateRequest $request, Kategori $kategori)
    {
        DB::beginTransaction();
        try {
            Kategori::find($kategori->id)->update($request->only(['kategori']));
            DB::commit();

            return redirect()->back()->with('pesan', '<div class="alert alert-success">Data berhasil diperbaruhi</div>');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::warning($th->getMessage());

            return redirect()->back()->with('pesan', '<div class="alert alert-danger">Terjadi kesalahan, cobalah kembali</div>');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Kategori  $kategori
     * @return \Illuminate\Http\Response
     */
    public function destroy($kategori)
    {
        $kategori = Kategori::where('uuid', $kategori)->firstOrFail();
        $status = $kategori->status;
        DB::beginTransaction();
        try {
            if ($status == true) {
                Kategori::find($kategori->id)->update(['status' => false]);
            } else {
                Kategori::find($kategori->id)->update(['status' => true]);
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
