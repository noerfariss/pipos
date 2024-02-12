<?php

namespace App\Http\Controllers\Member;

use App\Exports\SuplierExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Suplier\SuplierCreateRequest;
use App\Http\Requests\Suplier\SuplierUpdateRequest;
use App\Models\Suplier;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class SuplierController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:SUPLIER_READ')->only('index');
        $this->middleware('permission:SUPLIER_CREATE')->only(['create', 'store']);
        $this->middleware('permission:SUPLIER_EDIT')->only(['edit', 'update']);
        $this->middleware('permission:SUPLIER_DELETE')->only('delete');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('member.suplier.index');
    }

    public function ajax(Request $request)
    {
        $cari = $request->cari;

        $data = Suplier::query()
            ->when($cari, function ($e, $cari) {
                $e->where('suplier', 'like', '%' . $cari . '%')->orWhere('kode', 'like', '%' . $cari . '%');
            })
            ->where('status', cekStatus($request->status));

        if ($request->filled('export')) {

            activity()
                ->causedBy(Auth::id())
                ->useLog('kategori export')
                ->log(request()->ip());

            return Excel::download(new SuplierExport($data->get()), 'SUPLIER.xlsx');
        }

        return DataTables::eloquent($data)
            ->addColumn('aksi', function ($e) {
                $user = User::find(Auth::id());

                $btnEdit = $user->hasPermissionTo('SUPLIER_EDIT')
                    ? ($e->status == true ? '<li><a href="' . route('suplier.edit', ['suplier' => $e->uuid]) . '" class="dropdown-item"><i class="bx bx-pencil"></i> Edit</a></li>' : '')
                    : '';

                $btnDelete = $user->hasPermissionTo('SUPLIER_DELETE')
                    ? ($e->status == true ? '<li><a href="' . route('suplier.destroy', ['suplier' => $e->uuid]) . '" data-title="' . $e->suplier . '" class="dropdown-item btn-hapus"><i class="bx bx-trash"></i> Delete</a></li>' : '')
                    : '';

                $btnReload = $user->hasPermissionTo('SUPLIER_EDIT')
                    ? ($e->status == false ?  '<li><a href="' . route('suplier.destroy', ['suplier' => $e->uuid]) . '" data-title="' . $e->suplier . '" data-status="' . $e->status . '" class="dropdown-item btn-hapus"><i class="bx bx-refresh"></i></i> reset</a></li>' : '')
                    : '';

                return '<div class="btn-group float-end" role="group" aria-label="Button group with nested dropdown">
                            <div class="btn-group" role="group">
                                <button id="btnGroupDrop1" type="button" class="dropdown-toggle badge border text-dark" data-bs-toggle="dropdown" aria-expanded="false">
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
            ->editColumn('status', fn ($e) => statusTable($e->status))
            ->rawColumns(['aksi', 'status'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('member.suplier.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SuplierCreateRequest $request)
    {
        DB::beginTransaction();
        try {
            Suplier::create($request->only(['suplier', 'alamat']));
            DB::commit();

            return redirect()->route('suplier.create')->with('pesan', '<div class="alert alert-success">Data berhasil ditambahkan</div>');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::warning($th->getMessage());
            return redirect()->back()->with('pesan', '<div class="alert alert-danger">Terjadi kesalahan, cobalah kembali</div>');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Suplier $suplier)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($suplier)
    {
        $suplier = Suplier::where('uuid', $suplier)->firstOrFail();

        return view('member.suplier.edit', compact('suplier'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SuplierUpdateRequest $request, Suplier $suplier)
    {
        DB::beginTransaction();
        try {
            Suplier::find($suplier->id)->update($request->only(['suplier', 'alamat']));
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
     */
    public function destroy($suplier)
    {
        DB::beginTransaction();
        try {
            $suplier = Suplier::where('uuid', $suplier)->firstOrFail();
            $status = $suplier->status;

            if ($status == true) {
                Suplier::find($suplier->id)->update(['status' => false]);
            } else {
                Suplier::find($suplier->id)->update(['status' => true]);
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
