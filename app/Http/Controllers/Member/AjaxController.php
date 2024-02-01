<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Kecamatan;
use App\Models\Kota;
use App\Models\Produk;
use App\Models\Provinsi;
use App\Models\Suplier;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class AjaxController extends Controller
{
    public function provinsi(Request $request)
    {
        $term = $request->term;

        $data = Provinsi::query()
            ->when($term, function ($e, $term) {
                $e->where('provinsi', 'like', '%' . $term . '%');
            })
            ->where('status', true)
            ->select('id', 'provinsi as label');

        if ($data->count() > 0) {
            return response()->json([
                'data'  => $data->get(),
                'status' => true
            ]);
        } else {
            return response()->json([
                'status' => false,
                'data'  => null,
            ]);
        }
    }

    public function kota(Request $request)
    {
        $term = $request->term;
        $provinsi = $request->provinsi;

        $data = Kota::query()
            ->when($term, function ($e, $term) {
                $e->where('kota', 'like', '%' . $term . '%');
            })
            ->where('status', true)
            ->when($provinsi, function ($e, $provinsi) {
                $e->where('provinsi_id', $provinsi);
            })
            ->select('id', 'kota as label');

        if ($data->count() > 0) {
            return response()->json([
                'data'  => $data->get(),
                'status' => true
            ]);
        } else {
            return response()->json([
                'status' => false,
                'data'  => null,
            ]);
        }
    }

    public function kecamatan(Request $request)
    {
        $term = $request->term;
        $kota = $request->kota;

        $data = Kecamatan::query()
            ->when($term, function ($e, $term) {
                $e->where('kecamatan', 'like', '%' . $term . '%');
            })
            ->where('status', true)
            ->where('kota_id', $kota)
            ->select('id', 'kecamatan as label');

        if ($data->count() > 0) {
            return response()->json([
                'data'  => $data->get(),
                'status' => true
            ]);
        } else {
            return response()->json([
                'status' => false,
                'data'  => null,
            ]);
        }
    }

    public function kategori(Request $request)
    {
        $term = $request->term;

        $data = Kategori::query()
            ->when($term, function ($e, $term) {
                $e->where('kategori', 'like', '%' . $term . '%');
            })
            ->where('status', true)
            ->select('id', 'kategori as label');

        if ($data->count() > 0) {
            return response()->json([
                'data'  => $data->get(),
                'status' => true
            ]);
        } else {
            return response()->json([
                'status' => false,
                'data'  => null,
            ]);
        }
    }

    public function unit(Request $request)
    {
        $term = $request->term;

        $data = Unit::query()
            ->when($term, function ($e, $term) {
                $e->where('unit', 'like', '%' . $term . '%');
            })
            ->where('status', true)
            ->select('id', 'unit as label');

        if ($data->count() > 0) {
            return response()->json([
                'data'  => $data->get(),
                'status' => true
            ]);
        } else {
            return response()->json([
                'status' => false,
                'data'  => null,
            ]);
        }
    }

    public function produk(Request $request)
    {
        $term = $request->term;

        $data = Produk::query()
            ->when($term, function ($e, $term) {
                $e->where('barcode', 'like', '%' . $term . '%')->orWhere('produk', 'like', '%' . $term . '%');
            })
            ->where('status', true)
            ->select('id', DB::raw('CONCAT(barcode, " - ", produk) as label'));

        if ($data->count() > 0) {
            return response()->json([
                'data'  => $data->get(),
                'status' => true
            ]);
        } else {
            return response()->json([
                'status' => false,
                'data'  => null,
            ]);
        }
    }

    public function suplier(Request $request)
    {
        $term = $request->term;

        $data = Suplier::query()
            ->when($term, function ($e, $term) {
                $e->where('kode_label', 'like', '%' . $term . '%')->orWhere('suplier', 'like', '%' . $term . '%');
            })
            ->where('status', true)
            ->select('id', DB::raw('CONCAT(kode_label, " - ", suplier) as label'));

        if ($data->count() > 0) {
            return response()->json([
                'data'  => $data->get(),
                'status' => true
            ]);
        } else {
            return response()->json([
                'status' => false,
                'data'  => null,
            ]);
        }
    }

    public function ganti_foto(Request $request)
    {
        if ($request->has('file')) {
            $file = $request->file;
            $path = $request->path;

            switch ($path) {

                case 'foto':
                    $size_gambar = 400;
                    break;

                case 'banner':
                    $size_gambar = 1024;
                    break;

                default:
                    $size_gambar = 300;
                    break;
            }

            $request->validate([
                'file' => 'required|image|max:7000'
            ]);

            $name = time();
            $ext  = $file->getClientOriginalExtension();
            $foto = $name . '.' . $ext;

            $fullPath = $path . '/'  . $foto;

            $path = $file->getRealPath();
            $thum = Image::make($path)->resize($size_gambar, $size_gambar, function ($size) {
                $size->aspectRatio();
            });

            $path = Storage::put($fullPath, $thum->stream());

            return response()->json([
                'file' => $fullPath,
            ]);
        }
    }

    public function ganti_pdf(Request $request)
    {
        if ($request->has('file')) {
            $file = $request->file;
            $request->validate([
                'file' => 'required|mimes:pdf|max:10000'
            ]);

            $path = Storage::put('buku/pdf/' . pecahEmail(Auth::user()->email), $file);

            return response()->json([
                'file' => $path,
            ]);
        }
    }
}
