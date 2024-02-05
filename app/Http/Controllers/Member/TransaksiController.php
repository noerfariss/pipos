<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TransaksiController extends Controller
{
    public function ajax(Request $request)
    {
        $cari = $request->cari;
        $tanggal = pecahTanggal($request->tanggal);

        $data = Transaksi::query()
            ->when($cari, function ($e, $cari) {
                $e->where(function ($e) use ($cari) {
                    $e->where('no_transaksi', 'like', '%' . $cari . '%');
                });
            })
            ->where(function ($e) use ($tanggal) {
                $e->whereDate('created_at', '>=', $tanggal[0])->whereDate('created_at', '<=', $tanggal[1]);
            });

        return DataTables::eloquent($data)
            ->editColumn('created_at', fn ($e) => Carbon::parse($e->created_at)->timezone(session('zonawaktu'))->isoFormat('DD MMM YYYY HH:mm'))
            ->make(true);
    }
}
