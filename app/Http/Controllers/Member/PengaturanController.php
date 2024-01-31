<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pengaturan\PengaturanUpdateRequest;
use App\Models\Pengaturan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PengaturanController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:PENGATURAN_READ')->only(['show']);
        $this->middleware('permission:PENGATURAN_EDIT')->only(['edit', 'update']);
    }
    /**
     * Show the form for creating the resource.
     */
    public function create(): never
    {
        abort(404);
    }

    /**
     * Store the newly created resource in storage.
     */
    public function store(Request $request): never
    {
        abort(404);
    }

    /**
     * Display the resource.
     */
    public function show()
    {
        $pengaturan = Pengaturan::first();

        return view('member.pengaturan.show', compact('pengaturan'));
    }

    /**
     * Show the form for editing the resource.
     */
    public function edit()
    {
        $pengaturan = Pengaturan::first();
        return view('member.pengaturan.edit', compact('pengaturan'));
    }

    /**
     * Update the resource in storage.
     */
    public function update(PengaturanUpdateRequest $request)
    {
        DB::beginTransaction();
        try {
            Pengaturan::query()->update($request->only(['nama', 'alamat', 'logo', 'email', 'phone', 'phone2', 'timezone', 'kota_id']));

            activity()
                ->causedBy(Auth::id())
                ->withProperties(['ip' => request()->ip()])
                ->log('update pengaturan');

            DB::commit();

            session(['zonawaktu' => $request->timezone]);

            return redirect()->route('pengaturan.edit')->with('pesan', '<div class="alert alert-success">Pengaturan berhasil diperbarui</div>');
        } catch (\Throwable $th) {
            Log::warning($th->getMessage());
            DB::rollBack();

            return redirect()->route('pengaturan.edit')->with('pesan', '<div class="alert alert-danger">Terjadi kesalahan, cobalah kembali</div>');
        }
    }

    /**
     * Remove the resource from storage.
     */
    public function destroy(): never
    {
        abort(404);
    }
}
