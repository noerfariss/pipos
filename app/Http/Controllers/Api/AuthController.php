<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Resources\Anggota\AnggotaResource;
use App\Http\Resources\errorResource;
use App\Http\Resources\SuccessResource;
use App\Models\Anggota;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        try {
            if (
                Auth::guard('api')->attempt($request->only(['nomor_anggota', 'password']))
            ) {
                $user = Anggota::where('nomor_anggota', $request->anggota)->first();
                $user['token'] = $user->createToken('api', ['*'], now()->addWeek())->plainTextToken;

                return new AnggotaResource($user);
            }

            return new errorResource(['message' => 'Nomor anggota atau password salah']);
        } catch (\Throwable $th) {
            Log::warning($th->getMessage());
            return new errorResource();
        }
    }

    public function user()
    {
        try {
            $user = request()->user();
            return new AnggotaResource($user);
        } catch (\Throwable $th) {
            Log::warning($th->getMessage());
            return new errorResource();
        }
    }

    public function logout()
    {
        try {
            $user = request()->user();
            $user->currentAccessToken()->delete();

            return new SuccessResource();
        } catch (\Throwable $th) {
            Log::warning($th->getMessage());
            return new errorResource();
        }
    }
}
