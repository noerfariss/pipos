<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Enums\StokEnums;
use App\Events\StokEvent;
use App\Exports\StokOutExport;
use App\Http\Requests\Stok\StokOutRequest;
use App\Http\Resources\errorResource;
use App\Http\Resources\Stok\StokOutResource;
use App\Models\Stok;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class StokOutController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:STOKOUT_READ')->only('index');
        $this->middleware('permission:STOKOUT_CREATE')->only(['create', 'store']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('member.stokout.index');
    }

    public function ajax(Request $request)
    {
        $cari = $request->cari;
        $tipe = $request->tipe;
        $tanggal = pecahTanggal($request->tanggal);

        $data = Stok::query()
            ->withWhereHas('produk', fn ($e) => $e->select('id', 'barcode', 'produk'))
            ->with('suplier', fn ($e) => $e->select('id', 'kode_label', 'suplier'))
            ->whereIn('tipe', [StokEnums::OUT->value, StokEnums::SALE->value])
            ->when($tipe, fn ($e, $tipe) => $e->where('tipe', $tipe))
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

            return Excel::download(new StokOutExport($data->get()), 'stokout.xlsx');
        }

        return DataTables::eloquent($data)
            ->editColumn('tipe', fn ($e) => stokTipeTable($e->tipe))
            ->addColumn('kode_produk', fn ($e) => $e->produk ? '<a href="' . route('stokout.show', ['uuid' => $e->uuid]) . '" class="text-dark btn-detail" data-title = "STOK KELUAR">' . $e->produk->barcode . '</a>' : '-')
            ->addColumn('produk', fn ($e) => $e->produk ? '<a href="' . route('stokout.show', ['uuid' => $e->uuid]) . '" class="text-dark btn-detail" data-title = "STOK KELUAR">' . $e->produk->produk . '</a>' : '-')
            ->addColumn('suplier', fn ($e) => $e->suplier ? '<a href="' . route('stokout.show', ['uuid' => $e->uuid]) . '" class="text-dark btn-detail" data-title = "STOK KELUAR">' . $e->suplier->suplier . '</a>' : '-')
            ->editColumn('keterangan', fn ($e) => Str::words($e->keterangan, 5, '...'))
            ->editColumn('created_at', fn ($e) => Carbon::parse($e->created_at)->timezone(session('zonawaktu'))->isoFormat('DD MMM YYYY HH:mm'))
            ->rawColumns(['tipe', 'kode_produk', 'produk', 'suplier'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('member.stokout.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StokOutRequest $request)
    {
        DB::beginTransaction();
        try {
            $stok = Stok::create($request->only(['tipe', 'reason', 'produk_id', 'qty', 'keterangan']));

            StokEvent::dispatch($stok);

            DB::commit();

            return redirect()->back()->with('pesan', '<div class="alert alert-success">Data berhasil ditambahkan</div>');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::warning($th->getMessage());
            return redirect()->back()->with('pesan', '<div class="alert alert-danger">' . $th->getMessage() . '</div>');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($stok)
    {
        try {
            $stok = Stok::where('uuid', $stok)->firstOrFail();
            return new StokOutResource($stok);
        } catch (\Throwable $th) {
            Log::warning($th->getMessage());
            return new errorResource();
        }
    }
}
