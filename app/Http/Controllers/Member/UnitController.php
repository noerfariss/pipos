<?php

namespace App\Http\Controllers\Member;

use App\Exports\UnitExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Unit\UnitCreateRequest;
use App\Http\Requests\Unit\UnitUpdateRequest;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class UnitController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:UNIT_READ')->only('index');
        $this->middleware('permission:UNIT_CREATE')->only(['create', 'store']);
        $this->middleware('permission:UNIT_EDIT')->only(['edit', 'update']);
        $this->middleware('permission:UNIT_DELETE')->only('delete');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('member.unit.index');
    }

    public function ajax(Request $request)
    {
        $cari = $request->cari;

        $data = Unit::query()
            ->when($cari, function ($e, $cari) {
                $e->where('unit', 'like', '%' . $cari . '%');
            })
            ->where('status', cekStatus($request->status));

        if ($request->filled('export')) {

            activity()
                ->causedBy(Auth::id())
                ->useLog('unit export')
                ->log(request()->ip());

            return Excel::download(new UnitExport($data->get()), 'UNIT.xlsx');
        }

        return DataTables::eloquent($data)
            ->addColumn('aksi', function ($e) {
                $user = User::find(Auth::id());

                $btnEdit = $user->hasPermissionTo('UNIT_EDIT')
                    ? ($e->status == true ? '<li><a href="' . route('unit.edit', ['unit' => $e->uuid]) . '" class="dropdown-item"><i class="bx bx-pencil"></i> Edit</a></li>' : '')
                    : '';

                $btnDelete = $user->hasPermissionTo('UNIT_DELETE')
                    ? ($e->status == true ? '<li><a href="' . route('unit.destroy', ['unit' => $e->uuid]) . '" data-title="' . $e->unit . '" class="dropdown-item btn-hapus"><i class="bx bx-trash"></i> Delete</a></li>' : '')
                    : '';

                $btnReload = $user->hasPermissionTo('UNIT_EDIT')
                    ? ($e->status == false ?  '<li><a href="' . route('unit.destroy', ['unit' => $e->uuid]) . '" data-title="' . $e->unit . '" data-status="' . $e->status . '" class="dropdown-item btn-hapus"><i class="bx bx-refresh"></i></i> reset</a></li>' : '')
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
     */
    public function create()
    {
        return view('member.unit.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UnitCreateRequest $request)
    {
        DB::beginTransaction();
        try {
            Unit::create($request->only(['unit']));
            DB::commit();

            return redirect()->back()->with('pesan', '<div class="alert alert-success">Data berhasil ditambahkan</div>');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::warning($th->getMessage());
            return redirect()->back()->with('pesan', '<div class="alert alert-danger">Terjadi kesalahan, cobalah kembali</div>');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Unit $unit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($unit)
    {
        $unit = Unit::where('uuid', $unit)->firstOrFail();

        return view('member.unit.edit', compact('unit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UnitUpdateRequest $request, Unit $unit)
    {
        DB::beginTransaction();
        try {
            Unit::find($unit->id)->update($request->only(['unit']));
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
    public function destroy($unit)
    {
        $unit = Unit::where('uuid', $unit)->firstOrFail();
        $status = $unit->status;
        DB::beginTransaction();
        try {
            if ($status == true) {
                unit::find($unit->id)->update(['status' => false]);
            } else {
                unit::find($unit->id)->update(['status' => true]);
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
