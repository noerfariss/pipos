@extends('member.layouts.layout')

@section('konten')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-sm-12">
                <ul class="nav nav-pills flex-column flex-md-row mb-3">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('pengaturan.show') }}"><i class="bx bxs-cog me-1"></i>
                            Pengaturan</a>
                    </li>
                    @haspermission('PENGATURAN_PEMINJAMAN')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('pinjaman.show') }}"><i class='bx bx-up-arrow-alt'></i>
                                Peminjaman</a>
                        </li>
                    @endhaspermission
                </ul>
            </div>
            <div class="col-md-6">
                <div class="card mb-4">
                    <h5 class="card-header">Pengaturan</h5>
                    <div class="card-body">
                        @if (session()->has('pesan'))
                            {!! session('pesan') !!}
                        @endif

                        <form action="{{ route('pengaturan.update') }}" method="POST" enctype="multipart/form-data"
                            id="my-form">
                            @csrf
                            @method('PATCH')

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label" for="nama">Sekolah</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="sekolah"
                                        value="{{ $pengaturan->sekolah }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label" for="nama">npsn</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="npsn"
                                        value="{{ $pengaturan->npsn }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label" for="nama">whatsapp</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="whatsapp"
                                        value="{{ $pengaturan->whatsapp }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label" for="nama">Alamat</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="alamat_sekolah"
                                        value="{{ $pengaturan->alamat_sekolah }}">
                                </div>
                            </div>

                            {{-- LOGO --}}
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label" for="email">Logo</label>
                                <div class="col-sm-9">
                                    <div class="button-wrapper">
                                        <button type="button" class="account-file-input btn btn-sm btn-outline-primary"
                                            data-bs-toggle="modal" data-bs-target="#modalUploadFoto">
                                            <span class="d-none d-sm-block">Ganti logo</span>
                                            <i class="bx bx-upload d-block d-sm-none"></i>
                                        </button>
                                        <input type="hidden" name="logo" id="foto" value="">
                                        <div><small class="text-muted mb-0">JPG, GIF, PNG. Maksimal ukuran 2000 Kb</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label" for="email"></label>
                                <div class="col-sm-9">
                                    <div id="box-foto">
                                        @if ($pengaturan->logo === 'logo' || $pengaturan->logo === null || $pengaturan->logo == '')
                                            Logo belum diset
                                        @else
                                            <img src="{{ url('/storage' . '/' . $pengaturan->logo) }}" class="rounded">
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label" for="nama">Provinsi</label>
                                <div class="col-sm-9">
                                    <select name="provinsi" id="" class="form-control provinsi-select"
                                        data-ajax--url="{{ route('drop-provinsi') }}">
                                        @if ($pengaturan->kecamatan_id)
                                            <option value="{{ $pengaturan->kecamatan?->kota->provinsi_id }}" selected>
                                                {{ $pengaturan->kecamatan->kota->provinsi->provinsi }}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label" for="nama">Kota</label>
                                <div class="col-sm-9">
                                    <select name="kota" id="" class="form-control kota-select"
                                        data-ajax--url="{{ route('drop-kota') }}">
                                        @if ($pengaturan->kecamatan_id)
                                            <option value="{{ $pengaturan->kecamatan->kota_id }}" selected>
                                                {{ $pengaturan->kecamatan?->kota?->kota }}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label" for="nama">Kecamatan</label>
                                <div class="col-sm-9">
                                    <select name="kecamatan" id="" class="form-control kecamatan-select"
                                        data-ajax--url="{{ route('drop-kecamatan') }}">
                                        @if ($pengaturan->kecamatan_id)
                                            <option value="{{ $pengaturan->kecamatan_id }}" selected>
                                                {{ $pengaturan->kecamatan->kecamatan }}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>


                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label" for="nama">Telpon</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="telpon"
                                        value="{{ $pengaturan->telpon }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label" for="nama">Email</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="email"
                                        value="{{ $pengaturan->email }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label" for="nama">Timezone</label>
                                <div class="col-sm-9">
                                    <select name="timezone" id="timezone" class="form-control select2">
                                        <option value="Asia/Jakarta"
                                            {{ $pengaturan->timezone === 'Asia/Jakarta' ? 'selected' : '' }}>Asia/Jakarta
                                        </option>
                                        <option value="Asia/Makassar"
                                            {{ $pengaturan->timezone === 'Asia/Makassar' ? 'selected' : '' }}>Asia/Makassar
                                        </option>
                                        <option value="Asia/Jayapura"
                                            {{ $pengaturan->timezone === 'Asia/Jayapura' ? 'selected' : '' }}>Asia/Jayapura
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="row justify-content-end">
                                <div class="col-sm-9">
                                    <a href="{{ route('pengaturan.show') }}" class="btn btn-link btn-sm">Kembali</a>
                                    <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <!-- Modal LOGO-->
    <div class="modal fade" id="modalUploadFoto" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="modalUploadFotoLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalUploadFotoLabel">Unggah logo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <span id="notif"></span>
                    <form action="{{ route('master.foto') }}" class="dropzone" id="upload-image" method="POST"
                        enctype="multipart/form-data">
                        <input type="hidden" name="path" value="foto">
                        @csrf
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-sm btn-simpan"
                        onclick="simpanFoto()">Tambahkan</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js') }}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\Pengaturan\PengaturanUpdateRequest', '#my-form') !!}

    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />

    <script>
        // ------------- Logo
        Dropzone.options.uploadImage = {
            maxFilesize: 2000,
            acceptedFiles: ".jpeg,.jpg,.png",
            method: 'post',
            createImageThumbnails: true,
            init: function() {
                this.on("addedfile", file => {
                    $('.btn-simpan').attr('disabled', 'disabled').text('Loading...');
                });
            },
            success: function(file, response) {
                $('.btn-simpan').removeAttr('disabled').text('Tambahkan');
                const foto = response.file;
                $('.modal-body #notif').html(`<div class="alert alert-success">Foto berhasil diunggah</div>`);
                $('#foto').val(foto);
            },
            error: function(file, response) {
                $('.btn-simpan').removeAttr('disabled').text('Tambahkan');
                const pesan = response.message;
                $('.modal-body #notif').html(`<div class="alert alert-danger">${pesan}</div>`);
            }
        };

        function simpanFoto($tipe = '') {
            let title = '';
            let foto = '';
            let boxImage = '';
            let notif = '';

            if ($tipe == '') {
                title = 'Logo';
                foto = $('#foto').val();
                boxImage = $('#box-foto');
                notif = $('#notif');
            } else {
                title = 'Favicon';
                foto = $('#favicon').val();
                boxImage = $('#box-favicon');
                notif = $('#notif-favicon');
            }

            if (foto === '' || foto === null) {
                $(notif).html(`<div class="alert alert-danger">Tidak dapat menambahkan ${title}</div>`);
            } else {
                $('#modalUploadFoto, #modalUploadFavicon').modal('hide');
                $(boxImage).html(`<img src="{{ base_url('${foto}') }}" class="rounded">`);
            }
        }
    </script>
@endsection
