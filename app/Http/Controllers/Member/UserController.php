<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\PasswordUserUpdateRequest;
use App\Http\Requests\User\UserCreateRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:USERWEB_READ')->only('index');
        $this->middleware('permission:USERWEB_CREATE')->only(['create', 'store']);
        $this->middleware('permission:USERWEB_EDIT')->only(['edit', 'update']);
        $this->middleware('permission:USERWEB_DELETE')->only('delete');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('member.user.index');
    }


    public function ajax(Request $request)
    {
        $cari = $request->cari;

        $data = User::query()
            ->when($cari, function ($e, $cari) {
                $e->where('nama', 'like', '%' . $cari . '%')->orWhere('email', 'like', '%' . $cari . '%')->orWhere('whatsapp', 'like', '%' . $cari . '%');
            })
            ->where('id', '<>', 1)
            ->where('status', cekStatus($request->status));

        return DataTables::eloquent($data)
            ->editColumn('foto', fn ($e) => fotoProfil($e->foto))
            ->addColumn('roles', fn ($e) => $e->roles->implode('name', ', '))
            ->addColumn('aksi', function ($e) {
                $user = User::find(Auth::id());

                $btnEdit = $user->hasPermissionTo('USERWEB_EDIT')
                    ? ($e->status == true ? '<li><a href="' . route('user.edit', ['user' => $e->uuid]) . '" class="dropdown-item"><i class="bx bx-pencil"></i> Edit</a></li>' : '')
                    : '';

                $btnPassword = $user->hasPermissionTo('USERWEB_EDIT')
                    ? ($e->status == true ? '<li><a href="' . route('user.edit', ['user' => $e->uuid, 'password' => true]) . '" class="dropdown-item"><i class="bx bx-dialpad-alt"></i> Ganti Password</a></li>' : '')
                    : '';

                $btnDelete = $user->hasPermissionTo('USERWEB_DELETE')
                    ? ($e->status == true ? '<li><a href="' . route('user.destroy', ['user' => $e->uuid]) . '" data-title="' . $e->nama . '" class="dropdown-item btn-hapus"><i class="bx bx-trash"></i> Delete</a></li>' : '')
                    : '';

                $btnReload = $user->hasPermissionTo('USERWEB_EDIT')
                    ? ($e->status == false ?  '<li><a href="' . route('user.destroy', ['user' => $e->uuid]) . '" data-title="' . $e->nama . '" data-status="' . $e->status . '" class="dropdown-item btn-hapus"><i class="bx bx-refresh"></i></i> reset</a></li>' : '')
                    : '';

                return '<div class="btn-group float-end" role="group" aria-label="Button group with nested dropdown">
                            <div class="btn-group" role="group">
                                <button id="btnGroupDrop1" type="button" class="badge border text-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
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
            ->editColumn('status', fn ($e) =>  statusTable($e->status))
            ->rawColumns(['aksi', 'foto', 'status'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::where('id', '<>', 1)->get();

        return view('member.user.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserCreateRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = User::create($request->only(['nama', 'email', 'whatsapp', 'password', 'alamat']));
            $roles = Role::whereIn('id', $request->roles)->get();
            $user->syncRoles($roles);

            DB::commit();

            return redirect()->back()->with('pesan', '<div class="alert alert-success">Data berhasil ditambahkan</div>');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::warning($th->getMessage());
            return redirect()->back()->with('pesan', '<div class="alert alert-danger">Terjadi kesalahan, cobalah kembali</div>');
        }
    }

    public function password(PasswordUserUpdateRequest $request, User $user)
    {
        DB::beginTransaction();
        try {
            $user = User::find($user->id)->update([
                'password' => Hash::make($request->password)
            ]);
            DB::commit();

            return redirect()->back()->with('pesan', '<div class="alert alert-success">Passsowrd berhasil diperbarui</div>');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::warning($th->getMessage());
            return redirect()->back()->with('pesan', '<div class="alert alert-danger">Terjadi kesalahan, cobalah kembali</div>');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($user)
    {
        $user = User::where('uuid', $user)->firstOrFail();
        $roles = Role::where('id', '<>', 1)->get();

        if (request()->password) {
            return view('member.user.password', compact('user'));
        }

        return view('member.user.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        // dd($request->all());
        DB::beginTransaction();
        try {
            User::find($user->id)->update($request->only(['nama', 'email', 'whatsapp', 'alamat', 'foto']));
            $roles = Role::whereIn('id', $request->roles)->get();
            $user->syncRoles($roles);
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
    public function destroy($user)
    {
        $user = User::where('uuid', $user)->firstOrFail();
        $status = $user->status;
        DB::beginTransaction();
        try {
            if ($status == true) {
                User::find($user->id)->update(['status' => false]);
            } else {
                User::find($user->id)->update(['status' => true]);
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
