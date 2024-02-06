<?php

namespace App\Http\Controllers\Member;

use App\Exports\TransaksiExport;
use App\Http\Controllers\Controller;
use App\Http\Resources\errorResource;
use App\Http\Resources\Transaksi\TransaksiResource;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class TransaksiController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:TRANSAKSI_READ')->only('index');
    }

    public function index()
    {
        return view('member.transaksi.index');
    }

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

        if ($request->filled('export')) {

            activity()
                ->causedBy(Auth::id())
                ->useLog('transaksi export')
                ->log(request()->ip());

            return Excel::download(new TransaksiExport($data->get()), 'TRANSAKSI.xlsx');
        }

        return DataTables::eloquent($data)
            ->editColumn('created_at', fn ($e) => Carbon::parse($e->created_at)->timezone(session('zonawaktu'))->isoFormat('DD MMM YYYY HH:mm'))
            ->addColumn('items_count', fn ($e) => collect(json_decode($e->items))->sum('qty'))
            ->editColumn('items', fn ($e) => $e->items ? json_decode($e->items) : [])
            ->addColumn('member', fn ($e) => $e->member_id ? $e->member->nama : '-')
            ->rawColumns(['transaksi'])
            ->make(true);
    }

    public function show($uuid)
    {
        try {
            $transaksi = Transaksi::where('uuid', $uuid)->firstOrFail();
            return new TransaksiResource($transaksi);
        } catch (\Throwable $th) {
            Log::warning($th->getMessage());
            return new errorResource();
        }
    }
}
