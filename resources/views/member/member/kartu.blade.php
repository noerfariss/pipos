<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>KARTU ANGGOTA</title>
    <style>
        .page-break {
            page-break-after: always;
        }

        @page {
            margin: 0;
            font-size: 11px;
            background-image: url({{ public_path('backend/sneat-1.0.0/assets/img/avatars/bg-kartu.jpg') }})
        }

        .wrapper {
            background: url({{ public_path('backend/sneat-1.0.0/assets/img/avatars/bg-kartu.jpg') }}) no-repeat;
            background-size: cover;
            width: 100%;
            height: 100%;
        }

        .wrapper-back {
            background: #101D93;
            width: 100%;
            height: 100%;
            color: white;
        }

        .container {
            padding: 3mm 4mm;
        }

        header {
            margin: 0 auto;
            display: block;
            padding-bottom: 3px;
        }

        #logo-kiri {
            width: 50px;
            height: 50px;
        }

        #logo-kanan {
            width: 50px;
            height: 50px;
            display: inline-block;
        }

        header h2 {
            margin: 0 auto;
            text-align: center;
            text-transform: uppercase;
            padding: 0;
            font-size: 11px;
        }

        header h1 {
            margin: 0 auto;
            text-align: center;
            text-transform: uppercase;
            padding: 0;
            font-size: 13px;
        }

        header p {
            margin: 3px auto;
            text-align: center;
            padding: 0;
            font-size: 8px;
        }

        #body h1 {
            padding: 0;
            margin: 0;
            font-size: 12px;
        }

        #body ol {
            padding-left: 4mm;
        }

        #body ol li {
            font-size: 9px;
        }

        table {
            margin-top: 5px;
            width: 100%;
            border-collapse: collapse;
        }

        table tr td {
            font-size: 10px;
        }

        tr td {
            padding: 0;
        }

        #foto {
            width: 2cm;
            height: 2.5cm;
            border-radius: 8px;
            overflow: hidden;
            position: absolute;
            right: 0;
            background: #999;
            margin-top: -35px;
            margin-right: 16px;
        }

        #barcode {
            margin: 8px 0 0 0;
            display: inline-block;
            background: white;
            border: 1px solid rgb(191, 191, 191);
            padding: 4px;
            border-radius: 4px;
        }
    </style>
</head>

<body>
    <section class="wrapper">
        <div class="container">
            <header>
                <table>
                    <tr>
                        <td width="35" valign="top">
                            <div id="logo-kiri"><img
                                    src="{{ public_path('backend/sneat-1.0.0/assets/img/avatars/logo-kartu.png') }}"
                                    width="50"></div>
                        </td>
                        <td valign="top">
                            <h2>Kartu Anggota perpustakaan</h2>
                            <h1>{{ $sekolah->nama }}</h1>
                            <p>{{ $sekolah->alamat }}, {{ ucfirst(strtolower($sekolah->kota?->kota)) }} <br>
                                {{ ucwords(strtolower($sekolah->provinsi?->provinsi)) }}</p>
                            <p>Telp: {{ $sekolah->telpon }} | Email: {{ $sekolah->email }} </p>
                        </td>
                        <td width="35" align="right" valign="top">
                            <div id="logo-kanan"><img
                                    src="{{ public_path('backend/sneat-1.0.0/assets/img/avatars/logo-kartu.png') }}"
                                    width="50"></div>
                        </td>
                    </tr>
                </table>
            </header>
            <section id="body">
                <table>
                    <tr>
                        <td colspan="3">
                            <h1>{{ strtoupper($anggota->nama) }}</h1>
                        </td>
                    </tr>
                    <tr>
                        <td width="22%">No. Anggota</td>
                        <td width="1%">:</td>
                        <td width="75%">{{ $anggota->nomor_anggota }}</td>
                    </tr>
                    <tr>
                        <td>Kelas</td>
                        <td>:</td>
                        <td>{{ $anggota->kelas?->kelas }}</td>
                    </tr>
                    <tr>
                        <td>Jenis Kelamin</td>
                        <td>:</td>
                        <td>{{ $anggota->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                    </tr>
                </table>

                <div id="foto">
                    @php
                        if($anggota->foto === null || $anggota->foto == ''){
                            $foto_path = public_path('backend/sneat-1.0.0/assets/img/avatars/user-avatar.png');
                        }else{
                            if(env('FILESYSTEM_DISK') === 's3'){
                                $foto_path = public_path('storage/export/' . $anggota->namafoto);
                            }else{
                                $foto_path = public_path('storage/' . $anggota->namafoto);
                            }
                        }
                    @endphp

                    <img src="{{ $foto_path }}"
                        width="100%">
                </div>

                <div id="barcode">
                    {!! $barcode !!}
                </div>
            </section>
        </div>
    </section>

    <section class="page-break"></section>

    <section class="wrapper-back">
        <div class="container">
            <header>
                <table>
                    <tr>
                        <td width="35" valign="top">

                        </td>
                        <td valign="top">
                            <h2>TATA tertib perpustakaan</h2>
                            <h1>{{ $sekolah->nama }}</h1>
                        </td>
                        <td width="35" align="right" valign="top">

                        </td>
                    </tr>
                </table>
            </header>
            <section id="body">
                <ol>
                    <li>Berpakaian sopan dan tidak diperkenankan memakai kaos oblong, jaket dan sandal.</li>
                    <li>Mengisi daftar pengunjung yang sudah disediakan.</li>
                    <li>Menjaga kerapihan bahan pustaka, kebersihan, keamanan dan ketenangan belajar.</li>
                    <li>Tidak diperkenankan membawa makanan dan minuman atau pun makan-makan dan merokok di ruang
                        perpustakaan.</li>
                    <li>Memperlihatkan kepada petugas barang/buku yang dibawa pada saat masuk dan keluar perpustakaan.
                    </li>
                </ol>
            </section>
        </div>
    </section>
</body>

</html>
