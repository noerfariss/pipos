@extends('member.layouts.layout')

@section('konten')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-sm-12">
                <ul class="nav nav-pills flex-column flex-md-row mb-3">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('pengaturan.show') }}"><i class="bx bxs-cog me-1"></i>
                            Pengaturan</a>
                    </li>
                    @haspermission('PENGATURAN_PEMINJAMAN')
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ route('pinjaman.show') }}"><i class='bx bx-up-arrow-alt'></i>
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
                            <label class="col-sm-3 col-form-label" for="nama">Durasi pinjam</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="username"
                                    value="{{ $pengaturan->durasi_pinjam }}" disabled>
                                <small>Durasi peminjaman buku dihitung dalam satuan hari </small>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label" for="nama">Batas peminjaman</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" value="{{ $pengaturan->batas_pinjam }}" disabled>
                                <small>Batas jumlah buku dalam sekali pinjam. 0 tidak terbatas</small>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label" for="nama">Denda</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" value="{{ 'Rp ' . $pengaturan->denda_pinjam }}"
                                    disabled>
                                <small>Denda yang diberlakukan dalam per hari jika terlambat mengembalikan buku dari batas
                                    pengembalian yang ditentukan</small>
                            </div>
                        </div>

                        @haspermission('PENGATURAN_PEMINJAMAN_EDIT')
                            <div class="row justify-content-end">
                                <div class="col-sm-9">
                                    <a href="{{ route('pinjaman.edit') }}" class="btn btn-primary btn-sm">edit</a>
                                </div>
                            </div>
                        @endhaspermission

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
