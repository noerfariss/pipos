<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Jobs\RegisterMailJob;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            return redirect()->route('member.index');
        }

        return view('auth.register');
    }

    public function register(RegisterRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = User::create([
                'sekolah' => $request->sekolah,
                'email' => $request->email,
                'npsn' => $request->npsn,
                'whatsapp' => $request->whatsapp,
                'telpon' => $request->telpon,
                'status' => false,
                'token' => Str::uuid(),
                'expired_token' => now()->addMinutes(5),
                'password' => Hash::make($request->password),
            ]);

            activity()
                ->causedBy($user->id)
                ->useLog('register')
                ->log(request()->ip());

            DB::commit();

            dispatch(new RegisterMailJob($user));

            return redirect()->route('auth.register')->with('pesan', '<div class="alert alert-success">Registrasi berhasil, silahkan cek email Anda untuk verifikasi</div>');
        } catch (\Throwable $th) {
            Log::warning($th->getMessage());
            DB::rollBack();

            return redirect()->route('auth.register')->with('pesan', '<div class="alert alert-danger">Terjadi kesalahan, cobalah kembali</div>');
        }
    }

    public function verifikasi($token)
    {
        $user = User::where('token', $token)->firstOrFail();

        if (Carbon::parse($user->expired_token)->greaterThan(Carbon::now())) {
            User::find($user->id)->update([
                'status' => true,
                'email_verified_at' => Carbon::now(),
                'token' => null,
                'expired_token' => null,
            ]);

            $user->assignRole('admin');

            activity()
                ->causedBy($user->id)
                ->useLog('verifikasi email')
                ->log(request()->ip());

            return redirect()->route('auth.login');
        } else {
            User::find($user->id)->update([
                'token' => null,
                'expired_token' => null,
            ]);

            activity()
                ->causedBy($user->id)
                ->useLog('expired verifikasi')
                ->log(request()->ip());

            return view('auth.expired');
        }
    }
}
