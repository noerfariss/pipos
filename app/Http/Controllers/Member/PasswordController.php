<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\PasswordUpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class PasswordController extends Controller
{
    public function index()
    {
        return view('member.profil.password');
    }

    public function store(PasswordUpdateRequest $request)
    {
        DB::beginTransaction();
        try {

            if (Hash::check($request->password_lama, Auth::user()->password)) {
                User::find(Auth::id())->update([
                    'password' => Hash::make($request->password),
                ]);

                DB::commit();

                return redirect()->back()->with('pesan', '<div class="alert alert-success">Password berhasil diperbaruhi</div>');
            } else {
                return redirect()->back()->with('pesan', '<div class="alert alert-danger">Password lama salah!</div>');
            }
        } catch (\Throwable $th) {
            Log::warning($th->getMessage());
            DB::rollBack();
            return redirect()->back()->with('pesan', '<div class="alert alert-danger">Terjadi kesalahan, silahkan coba lagi</div>');
        }
    }
}
