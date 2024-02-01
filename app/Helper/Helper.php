<?php

use Illuminate\Support\Facades\Auth;

function menuAktif($url = NULL)
{
    $openUrl = url()->current();
    $parse = parse_url($openUrl, PHP_URL_PATH);
    $explode = explode('/', $parse);
    $newUrl = '/' . $explode[1] . '/' . (isset($explode[2]) ? $explode[2] : '');

    $path = '/auth';

    if ($url <> NULL) {
        if (gettype($url) === 'string') {
            return ($newUrl === $path . '/' . $url) ? 'active' : '';
        } else {
            $listUrl = [];
            foreach ($url as $item) {
                $listUrl[] = $path . '/' . $item;
            }

            if (in_array($newUrl, $listUrl)) {
                return 'open active';
            }
        }
    } else {
        return ($newUrl === $path . '/' . $url) ? 'active' : '';
    }
}

function menuAktifProfil($path)
{
    $openUrl = url()->current();
    $parse = parse_url($openUrl, PHP_URL_PATH);
    $explode = explode('/', $parse);

    if ($path === $explode[2]) {
        return 'active';
    }
}


function statusBtn()
{
    return '<div class="form-check form-switch float-end">
                <input class="form-check-input btn-check" type="checkbox" role="switch" id="flexSwitchCheckDefault" name="status" onclick="datatables.ajax.reload()" checked>
            </div>
            ';
}

function cekStatus($request)
{
    return $request ? true : false;
}

function exportBtn($tipe = [], $url = '', $filename = '')
{
    if (gettype($tipe) === 'array' && !empty($tipe)) {
        $btn = '<div class="float-end me-2">
                <button class="btn btn-sm btn-default dropdown-toggle" type="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bx bxs-file-doc"></i> Export
                </button>
                <ul class="dropdown-menu dropdown-menu-end" style="">';
        $btn .= (in_array('data', $tipe)) ? '<li><a class="dropdown-item btn-export" href="' . $url . '" data-ext="data" data-filename="' . $filename . '"><i class="bx bxs-spreadsheet"></i> Data</a></li>' : '';
        $btn .= (in_array('foto', $tipe)) ? '<li><a class="dropdown-item btn-export" href="' . $url . '" data-ext="foto" data-filename="' . $filename . '"><i class="bx bxs-file-image"></i> Data + Foto</a></li>' : '';
        $btn .= (in_array('pdf', $tipe)) ? '<li><a class="dropdown-item btn-export" href="' . $url . '" data-ext="pdf" data-filename="' . $filename . '"><i class="bx bxs-file-pdf"></i> PDF</a></li>' : '';
        $btn .= '</ul>
                </div>';

        return $btn;
    } else if (gettype($tipe) === 'string') {
        $btn = '<div class="float-end me-2">
                    <a class="btn btn-sm btn-default btn-export" href="' . $url . '" data-ext="data" data-filename="' . $filename . '"><i class="bx bxs-file-doc float-start"></i> Export</a>';
        $btn .= '</div>';

        return $btn;
    }
}

function pecahTanggal($tanggal)
{
    if (str_contains($tanggal, 'to')) {
        $pecah = explode(' ', $tanggal);
        $tmulai = trim($pecah[0]);
        $tkahir = trim($pecah[2]);
    } else {
        $tmulai = $tanggal;
        $tkahir = $tanggal;
    }

    return [$tmulai, $tkahir];
}

function pecahEmail($email)
{
    $explode = explode('@', $email);
    return $explode[0];
}

function genderTable($id)
{
    return $id ? '<span class="badge bg-success rounded-pill text-dark">pria</span>' : '<span class="badge bg-danger rounded-pill">wanita</span>';
}

function genderResource($id)
{
    return $id ? 'Pria' : 'Wanita';
}

function statusTable($status, $html = true)
{
    if ($html) {
        return $status ? '<span class="badge bg-success rounded-pill text-dark">ON</span>' : '<span class="badge bg-secondary rounded-pill">OFF</span>';
    } else {
        return $status ? 'ON' : 'OFF';
    }
}

function fotoProfil($foto, $jenisKelamin = 1, $onlyUrl = false)
{
    $priawanita = $jenisKelamin == 1 ? 'pria.jpg' : 'wanita.jpg';

    if ($onlyUrl) {
        return $foto ? url('/storage' . '/' . $foto) : url('/images/anggota/' . $priawanita);
    } else {
        return $foto ? '<img src="' . url('/storage' . '/' . $foto) . '" class="rounded-circle">' : '<img src="' . url('/images/anggota/' . $priawanita) . '" class="rounded-circle">';
    }
}


function fotoProduk($foto, $onlyUrl = false)
{
    if ($onlyUrl) {
        return $foto ? url('/storage' . '/' . $foto) : url('/images/produk.jpg');
    } else {
        return $foto ? '<img src="' . url('/storage' . '/' . $foto) . '" class="rounded-circle">' : '<img src="' . url('/images/produk.jpg') . '" class="rounded-circle">';
    }
}

function stokTipe($kode)
{
    switch ($kode) {
        case 1:
            return 'STOK MASUK';
            break;

        case 2:
            return 'STOK KELUAR';
            break;

        case 3:
            return 'PENJUALAN';
            break;

        default:
            return 'IN';
            break;
    }
}


function stokTipeTable($kode)
{
    switch ($kode) {
        case 1:
            return '<span class="badge bg-success text-dark">STOK MASUK</span>';
            break;

        case 2:
            return '<span class="badge bg-danger">STOK KELUAR</span>';
            break;

        case 3:
            return '<span class="badge bg-primary">PENJUALAN</span>';
            break;

        default:
            return '<span class="badge bg-success text-dark">IN</span>';
            break;
    }
}
