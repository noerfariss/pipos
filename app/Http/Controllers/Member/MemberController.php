<?php

namespace App\Http\Controllers\Member;

use App\Exports\MemberExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Member\MemberCreateRequest;
use App\Http\Requests\Member\MemberGantiPasswordRequest;
use App\Http\Requests\Member\MemberUpdateRequest;
use App\Http\Resources\errorResource;
use App\Http\Resources\Member\MemberResource;
use App\Http\Resources\SuccessResource;
use App\Models\Member;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class MemberController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:MEMBER_READ')->only('index');
        $this->middleware('permission:MEMBER_CREATE')->only(['create', 'store']);
        $this->middleware('permission:MEMBER_EDIT')->only(['edit', 'update']);
        $this->middleware('permission:MEMBER_DELETE')->only('delete');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('member.member.index');
    }


    public function ajax(Request $request)
    {
        $cari = $request->cari;

        $data = Member::query()
            ->select('uuid', 'nama', 'foto', 'phone', 'email', 'jenis_kelamin', 'alamat', 'kota_id', 'status')
            ->when($cari, function ($e, $cari) {
                $e->where(function ($e) use ($cari) {
                    $e->where('nama', 'like', '%' . $cari . '%')->orWhere('email', 'like', '%' . $cari . '%')->orWhere('phone', 'like', '%' . $cari . '%');
                });
            })
            ->where('status', cekStatus($request->status));

        if ($request->filled('export')) {

            activity()
                ->causedBy(Auth::id())
                ->useLog('kategori export')
                ->log(request()->ip());

            return Excel::download(new MemberExport($data->get()), 'MEMBER.xlsx');
        }

        return DataTables::eloquent($data)
            ->editColumn('foto', fn ($e) => fotoProfil($e->foto, $e->jenis_kelamin))
            ->editColumn('jenis_kelamin', fn ($e) => genderTable($e->jenis_kelamin))
            ->editColumn('status', fn ($e) => statusTable($e->status))
            ->editColumn('kota_id', fn ($e) => $e->kota?->kota)
            ->editColumn('created_at', fn ($e) => Carbon::parse($e->created_at)->timezone(session('zonawaktu'))->isoFormat('DD MMM YYYY HH:mm'))
            ->addColumn('aksi', function ($e) {
                $user = User::find(Auth::id());

                $btnEdit = $user->hasPermissionTo('MEMBER_EDIT')
                    ? ($e->status == true ? '<li><a href="' . route('member.edit', ['member' => $e->uuid]) . '" class="dropdown-item"><i class="bx bx-pencil"></i> Edit</a></li>' : '')
                    : '';

                $btnPassword = $user->hasPermissionTo('MEMBER_EDIT')
                    ? ($e->status == true ? '<li><a href="#" data-nama="' . $e->nama . '" data-phone="' . $e->phone . '" data-email="' . $e->email . '" data-uuid="' . $e->uuid . '" class="dropdown-item btn-open-ganti-password"><i class="bx bx-dialpad-alt"></i> Ganti Password</a></li>' : '')
                    : '';

                $btnDelete = $user->hasPermissionTo('MEMBER_DELETE')
                    ? ($e->status == true ? '<li><a href="' . route('member.destroy', ['member' => $e->uuid]) . '" data-title="' . $e->nama . '" class="dropdown-item btn-hapus"><i class="bx bx-trash"></i> Delete</a></li>' : '')
                    : '';

                $btnReload = $user->hasPermissionTo('MEMBER_EDIT')
                    ? ($e->status == false ?  '<li><a href="' . route('member.destroy', ['member' => $e->uuid]) . '" data-title="' . $e->nama . '" data-status="' . $e->status . '" class="dropdown-item btn-hapus"><i class="bx bx-refresh"></i></i> reset</a></li>' : '')
                    : '';

                return '<div class="btn-group float-end" role="group" aria-label="Button group with nested dropdown">
                            <div class="btn-group" role="group">
                                <button id="btnGroupDrop1" type="button" class="dropdown-toggle badge border text-dark" data-bs-toggle="dropdown" aria-expanded="false">
                                    setting
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="btnGroupDrop1">
                                    ' . $btnEdit . '
                                    ' . $btnPassword . '
                                    ' . $btnDelete . '
                                    ' . $btnReload . '
                                </ul>
                            </div>
                        </div>';
            })
            ->rawColumns(['aksi', 'foto', 'jenis_kelamin', 'status'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('member.member.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MemberCreateRequest $request)
    {
        DB::beginTransaction();
        try {
            Member::create($request->only(['nama', 'foto', 'phone', 'email', 'jenis_kelamin', 'alamat', 'kota_id']));
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
    public function show($member)
    {
        try {
            $member = Member::where('uuid', $member)->firstOrFail();
            return new MemberResource($member);
        } catch (\Throwable $th) {
            Log::warning($th->getMessage());
            return new errorResource();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($member)
    {
        $member = Member::where('uuid', $member)->firstOrFail();

        return view('member.member.edit', compact('member'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MemberUpdateRequest $request, Member $member)
    {
        DB::beginTransaction();
        try {
            Member::find($member->id)->update($request->only(['nama', 'foto', 'phone', 'email', 'jenis_kelamin', 'alamat', 'kota_id']));
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
    public function destroy($member)
    {
        DB::beginTransaction();
        try {
            $member = Member::where('uuid', $member)->firstOrFail();
            $status = $member->status;

            if ($status == true) {
                Member::find($member->id)->update(['status' => false]);
            } else {
                Member::find($member->id)->update(['status' => true]);
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

    public function password(MemberGantiPasswordRequest $request)
    {
        DB::beginTransaction();
        try {
            Member::where('uuid', $request->uuid)->update([
                'password' => Hash::make($request->password),
            ]);

            DB::commit();

            return new SuccessResource();
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::warning($th->getMessage());

            return new errorResource();
        }
    }

    public function cariCustomer(Request $request)
    {
        $key = $request->key;

        try {
            $member = Member::query()
                ->where('phone', $key)->orWhere('uuid', $key)->orWhere('email', $key)
                ->first();

            if ($member) {
                return new MemberResource($member);
            } else {
                return new errorResource(['message' => 'Customer tidak terdaftar', 'status' => 404]);
            }
        } catch (\Throwable $th) {
            Log::warning($th->getMessage());
            return new errorResource(['message' => $th->getMessage()]);
        }
    }
}
