<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Stok;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use App\Enums\StokEnums;
use App\Events\StokEvent;
use App\Exports\StokInExport;
use App\Http\Requests\Stok\StokInRequest;
use App\Http\Resources\errorResource;
use App\Http\Resources\Stok\StokInResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class StokInController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:STOKIN_READ')->only('index');
        $this->middleware('permission:STOKIN_CREATE')->only(['create', 'store']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('member.stokin.index');
    }

    public function ajax(Request $request)
    {
        $cari = $request->cari;
        $tanggal = pecahTanggal($request->tanggal);

        $data = Stok::query()
            ->withWhereHas('produk', fn ($e) => $e->select('id', 'barcode', 'produk'))
            ->with('suplier', fn ($e) => $e->select('id', 'kode_label', 'suplier'))
            ->where('tipe', StokEnums::IN->value)
            ->when($cari, function ($e, $cari) {
                $e->whereHas('produk', function ($e) use ($cari) {
                    $e->where('barcode', 'like', '%' . $cari . '%')->orWhere('produk', 'like', '%' . $cari . '%');
                });
            })
            ->when($tanggal, function ($e, $tanggal) {
                $e->where(function ($e) use ($tanggal) {
                    $e->where('created_at', '>=', $tanggal[0] . ' 00:00:00')->where('created_at', '<=', $tanggal[1] . ' 23:59:59');
                });
            });


        if ($request->filled('export')) {

            activity()
                ->causedBy(Auth::id())
                ->useLog('stok in export')
                ->log(request()->ip());

            return Excel::download(new StokInExport($data->get()), 'stokin.xlsx');
        }

        return DataTables::eloquent($data)
            ->addColumn('kode_produk', fn ($e) => $e->produk ? $e->produk->barcode : '-')
            ->addColumn('produk', fn ($e) => $e->produk ? $e->produk->produk : '-')
            ->addColumn('suplier', fn ($e) => $e->suplier ? $e->suplier->suplier : '-')
            ->editColumn('created_at', fn ($e) => Carbon::parse($e->created_at)->timezone(session('zonawaktu'))->isoFormat('DD MMM YYYY HH:mm'))
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('member.stokin.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StokInRequest $request)
    {
        DB::beginTransaction();
        try {
            $stok = Stok::create($request->only(['tipe', 'suplier_id', 'produk_id', 'qty', 'keterangan']));

            StokEvent::dispatch($stok);

            DB::commit();

            return redirect()->back()->with('pesan', '<div class="alert alert-success">Data berhasil ditambahkan</div>');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::warning($th->getMessage());
            return redirect()->back()->with('pesan', '<div class="alert alert-danger">Terjadi kesalahan, cobalah kembali</div>');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($stok)
    {
        try {
            $stok = Stok::where('uuid', $stok)->firstOrFail();
            return new StokInResource($stok);
        } catch (\Throwable $th) {
            Log::warning($th->getMessage());
            return new errorResource();
        }
    }
}
