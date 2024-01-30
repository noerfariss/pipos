<?php

namespace App\Http\Controllers\Member;

use App\Exports\ProdukExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Produk\ProdukCreateRequest;
use App\Http\Requests\Produk\ProdukEditRequest;
use App\Http\Resources\errorResource;
use App\Http\Resources\Produk\ProdukResource;
use App\Models\Produk;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class ProdukController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:PRODUK_READ')->only('index');
        $this->middleware('permission:PRODUK_CREATE')->only(['create', 'store']);
        $this->middleware('permission:PRODUK_EDIT')->only(['edit', 'update']);
        $this->middleware('permission:PRODUK_DELETE')->only('delete');
    }

    public function ajax(Request $request)
    {
        $cari = $request->cari;
        $kategori = $request->kategori;

        $data = Produk::query()
            ->withWhereHas('kategori')
            ->when($kategori, fn ($e, $kategori) => $e->whereHas('kategori', fn ($e) => $e->whereIn('id', $kategori)))
            ->when($cari, function ($e, $cari) {
                $e->where(function ($e) use ($cari) {
                    $e->where('barcode', 'like', '%' . $cari . '%')->orWhere('produk', 'like', '%' . $cari . '%')->orWhere('keterangan', 'like', '%' . $cari . '%');
                });
            })
            ->where('status', cekStatus($request->status));

        if ($request->filled('export')) {

            activity()
                ->causedBy(Auth::id())
                ->useLog('produk export')
                ->log(request()->ip());

            return Excel::download(new ProdukExport($data->get()), 'PRODUK.xlsx');
        }

        return DataTables::eloquent($data)
            ->addColumn('produk', function ($e) {
                return '
                    <a
                        href="' . route('produk.show', ['produk' => $e->uuid]) . '"
                        class="text-dark btn-detail"
                        data-title = "Produk"
                    >

                        <div><b>' . $e->produk . '</b></div>' .
                    '<div>' . $e->barcode . '</div>' .
                    '</a>';
            })
            ->editColumn('kategori_id', fn ($e) => $e->kategori->kategori)
            ->editColumn('foto', fn ($e) => fotoProduk($e->foto))
            ->editColumn('status', fn ($e) => statusTable($e->status))
            ->editColumn('created_at', fn ($e) => Carbon::parse($e->created_at)->timezone(session('zonawaktu'))->isoFormat('DD MMM YYYY HH:mm'))
            ->addColumn('aksi', function ($e) {
                $user = User::find(Auth::id());

                $btnEdit = $user->hasPermissionTo('PRODUK_EDIT')
                    ? ($e->status == true ? '<li><a href="' . route('produk.edit', ['produk' => $e->uuid]) . '" class="dropdown-item"><i class="bx bx-pencil"></i> Edit</a></li>' : '')
                    : '';

                $btnDelete = $user->hasPermissionTo('PRODUK_DELETE')
                    ? ($e->status == true ? '<li><a href="' . route('produk.destroy', ['produk' => $e->uuid]) . '" data-title="' . $e->produk . '" class="dropdown-item btn-hapus"><i class="bx bx-trash"></i> Delete</a></li>' : '')
                    : '';

                $btnReload = $user->hasPermissionTo('PRODUK_EDIT')
                    ? ($e->status == false ?  '<li><a href="' . route('produk.destroy', ['produk' => $e->uuid]) . '" data-title="' . $e->produk . '" data-status="' . $e->status . '" class="dropdown-item btn-hapus"><i class="bx bx-refresh"></i></i> reset</a></li>' : '')
                    : '';

                return '<div class="btn-group float-end" role="group" aria-label="Button group with nested dropdown">
                            <div class="btn-group" role="group">
                                <button id="btnGroupDrop1" type="button" class="dropdown-toggle badge border text-dark" data-bs-toggle="dropdown" aria-expanded="false">
                                    setting
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="btnGroupDrop1">
                                    ' . $btnEdit . '
                                    ' . $btnDelete . '
                                    ' . $btnReload . '
                                </ul>
                            </div>
                        </div>';
            })
            ->rawColumns(['aksi', 'foto', 'produk', 'status'])
            ->make(true);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('member.produk.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('member.produk.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProdukCreateRequest $request)
    {
        DB::beginTransaction();
        try {
            Produk::create($request->only(['kategori_id', 'barcode', 'produk', 'keterangan', 'harga', 'stok_warning', 'foto', 'unit_id', 'is_app']));
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
    public function show($produk)
    {
        try {
            $produk = Produk::where('uuid', $produk)->firstOrFail();
            return new ProdukResource($produk);
        } catch (\Throwable $th) {
            Log::warning($th->getMessage());
            return new errorResource();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($produk)
    {
        $produk = Produk::where('uuid', $produk)->firstOrFail();

        return view('member.produk.edit', compact('produk'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProdukEditRequest $request, Produk $produk)
    {
        DB::beginTransaction();
        try {
            Produk::find($produk->id)->update($request->only(['kategori_id', 'barcode', 'produk', 'keterangan', 'harga', 'stok_warning', 'foto', 'unit_id', 'is_app']));
            DB::commit();

            return redirect()->back()->with('pesan', '<div class="alert alert-success">Data berhasil diperbaruhi</div>');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::warning($th->getMessage());

            return redirect()->back()->with('pesan', '<div class="alert alert-danger">Terjadi kesalahan, cobalah kembali</div>');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($produk)
    {
        DB::beginTransaction();
        try {
            $produk = Produk::where('uuid', $produk)->firstOrFail();
            $status = $produk->status;

            if ($status == true) {
                Produk::find($produk->id)->update(['status' => false]);
            } else {
                Produk::find($produk->id)->update(['status' => true]);
            }

            DB::commit();

            return response()->json([
                'pesan' => 'Data berhasil dihapus',
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::warning($th->getMessage());
            return response()->json([
                'pesan' => 'Terjadi kesalahan'
            ], 500);
        }
    }

    public function showById($id)
    {
        try {
            $produk = Produk::where('id', $id)->firstOrFail();
            return new ProdukResource($produk);
        } catch (\Throwable $th) {
            Log::warning($th->getMessage());
            return new errorResource();
        }
    }

    public function cariProduk(Request $request)
    {
        $key = $request->key;
        $qty = $request->qty;

        try {
            $produk = Produk::query()
                ->where('barcode', $key)
                ->where('stok', '>', 0)
                ->firstOrFail();

            if ($produk->stok < $qty) {
                return new errorResource(['message' => 'Stok tidak mencukupi']);
            }

            return new ProdukResource($produk);
        } catch (\Throwable $th) {
            Log::warning($th->getMessage());
            return new errorResource(['message' => 'Produk tidak tersedia', 'status' => 404]);
        }
    }
}
