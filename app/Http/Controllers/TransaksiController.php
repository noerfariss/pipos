<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TransaksiController extends Controller
{
    public function ajax(Request $request)
    {
        $cari = $request->cari;

        $data = Transaksi::query()
            ->when($cari, function ($e, $cari) {
                $e->where(function ($e) use ($cari) {
                    $e->where('uuid', 'like', '%' . $cari . '%');
                });
            })
            ->whereDate('created_at', Carbon::now()->isoFormat('YYYY-MM-DD'));

        return DataTables::eloquent($data)
            ->editColumn('created_at', fn ($e) => Carbon::parse($e->created_at)->timezone(session('zonawaktu'))->isoFormat('DD MMM YYYY HH:mm'))
            ->make(true);
    }
}
