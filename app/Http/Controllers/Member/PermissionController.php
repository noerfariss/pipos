<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class PermissionController extends Controller
{
    public function ajax(Request $request)
    {
        $cari = $request->cari;

        if ($request->edit) {
            $data = Permission::query()
                ->leftJoin('role_has_permissions', function ($leftJoin) use ($request) {
                    $leftJoin->on('role_has_permissions.permission_id', '=', 'permissions.id')->where('role_has_permissions.role_id', $request->edit);
                })
                ->when($cari, function ($e, $cari) {
                    $e->where('name', 'like', '%' . $cari . '%');
                });
        } else {
            $data = Permission::query()
                ->when($cari, function ($e, $cari) {
                    $e->where('name', 'like', '%' . $cari . '%');
                });
        }


        return DataTables::eloquent($data)
            ->addColumn('aksi', function ($e) {
                $user = User::find(Auth::id());

                $btnEdit = $user->hasPermissionTo('PERMISSION_EDIT')
                    ? '<li><a href="' . route('role.edit', ['role' => $e->id]) . '" class="dropdown-item"><i class="bx bx-pencil"></i> Edit</a></li>' : '';

                $btnDelete = $user->hasPermissionTo('PERMISSION_DELETE')
                    ? '<li><a href="' . route('role.destroy', ['role' => $e->id]) . '" data-title="' . $e->name . '" class="dropdown-item btn-hapus"><i class="bx bx-trash"></i> Delete</a></li>' : '';

                return '<div class="btn-group float-end" role="group" aria-label="Button group with nested dropdown">
                            <div class="btn-group" role="group">
                                <button id="btnGroupDrop1" type="button" class="badge border text-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    setting
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="btnGroupDrop1">
                                    ' . $btnEdit . '
                                    ' . $btnDelete . '
                                </ul>
                            </div>
                        </div>';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
}
