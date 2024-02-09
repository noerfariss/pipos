<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            return redirect()->route('auth.index');
        }

        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $remember = true;
        try {
            if (Auth::attempt($request->only(['email', 'password', 'status']), $remember)) {

                activity()
                    ->causedBy(Auth::id())
                    ->useLog('login')
                    ->log(request()->ip());

                return redirect()->route('auth.index');
            } else {

                activity()
                    ->withProperties([
                        'ip' => request()->ip(),
                        'email' => $request->email,
                    ])
                    ->log('failed login');

                return redirect()->route('auth.login')->with('pesan', '<div class="alert alert-danger">Email atau password salah!</div>');
            }


            return redirect()->route('auth.login')->with('pesan', '<div class="alert alert-success">Registrasi berhasil, silahkan cek email Anda untuk verifikasi</div>');
        } catch (\Throwable $th) {
            Log::warning($th->getMessage());

            return redirect()->route('auth.login')->with('pesan', '<div class="alert alert-danger">Terjadi kesalahan, cobalah kembali</div>');
        }
    }

    public function logout()
    {
        activity()
            ->causedBy(Auth::id())
            ->useLog('logout')
            ->log(request()->ip());

        Auth::logout();

        return redirect()->route('auth.login');
    }
}
