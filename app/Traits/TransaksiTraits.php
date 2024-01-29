<?php

namespace App\Traits;

use App\Models\Transaksi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

trait TransaksiTraits
{
    public function kodeTransaksi()
    {
        $no_transaksi = Transaksi::query()
            ->whereYear('created_at', date('Y'))
            ->whereMonth('created_at', date('m'))
            ->where('user_id', Auth::id())
            ->orderBy('id', 'desc')
            ->first();

        $user = str_pad(Auth::id(), 3, 'U', STR_PAD_LEFT);

        if ($no_transaksi === null) {
            $kode = 'PJ' . date('Y') . $user . date('m') . '0001';

            return [
                'nomor' => 1,
                'kode' => $kode,
            ];
        } else {

            $urutan = $no_transaksi->kode;
            $newUrutan = $urutan + 1;

            $kode = 'PJ' . date('Y') . $user . date('m') . str_pad($newUrutan, 4, '0', STR_PAD_LEFT);

            return [
                'nomor' => $newUrutan,
                'kode' => $kode,
            ];
        }
    }
}
