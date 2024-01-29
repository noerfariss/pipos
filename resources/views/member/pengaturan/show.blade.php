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

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label" for="nama">Sekolah</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="username"
                                    value="{{ $pengaturan->sekolah }}" disabled>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label" for="nama">npsn</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="npsn" value="{{ $pengaturan->npsn }}"
                                    disabled>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label" for="nama">whatsapp</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="username"
                                    value="{{ $pengaturan->whatsapp }}" disabled>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label" for="nama">Alamat</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" value="{{ $pengaturan->alamat_sekolah }}"
                                    disabled>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label" for="nama">Provinsi</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control"
                                    value="{{ $pengaturan->kecamatan?->kota?->provinsi?->provinsi }}" disabled>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label" for="nama">Kota</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control"
                                    value="{{ $pengaturan->kecamatan?->kota?->kota }}" disabled>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label" for="nama">Kecamatan</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" value="{{ $pengaturan->kecamatan?->kecamatan }}"
                                    disabled>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label" for="nama">Telpon</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" value="{{ $pengaturan->telpon }}" disabled>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label" for="nama">Email</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" value="{{ $pengaturan->email }}" disabled>
                            </div>
                        </div>


                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label" for="nama">Timezone</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" value="{{ $pengaturan->timezone }}" disabled>
                            </div>
                        </div>

                        @haspermission('PENGATURAN_EDIT')
                            <div class="row justify-content-end">
                                <div class="col-sm-9">
                                    <a href="{{ route('pengaturan.edit') }}" class="btn btn-primary btn-sm">edit</a>
                                </div>
                            </div>
                        @endhaspermission

                    </div>
                    <!-- /Account -->
                </div>

            </div>
        </div>
    </div>
@endsection
