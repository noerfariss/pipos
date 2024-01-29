<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class HomeController extends Controller
{
    public function index()
    {
        // $user = User::find(Auth::id());
        $admin = Role::where('name', 'admin')->first();

        // $admin->givePermissionTo(['PENGATURAN_PEMINJAMAN', 'PENGATURAN_PEMINJAMAN_EDIT']);
        // $admin->revokePermissionTo(['PENGATURAN_PEMINJAMAN', 'PENGATURAN_PEMINJAMAN_EDIT']);

        // dd($user->hasPermissionTo('PENGATURAN'));

        return view('member.index');
    }

    public function keluar()
    {
        activity()
            ->causedBy(Auth::id())
            ->useLog('logout')
            ->log(request()->ip());

        Auth::logout();

        return redirect()->route('auth.login');
    }
}
