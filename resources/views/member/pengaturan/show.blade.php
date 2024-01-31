@extends('member.layouts.layout')

@section('konten')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <h5 class="card-header">Pengaturan</h5>
                    <div class="card-body">

                        @if (session()->has('pesan'))
                            {!! session('pesan') !!}
                        @endif

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label" for="nama">Nama</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="username" value="{{ $pengaturan->nama }}"
                                    disabled>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label" for="nama">Alamat</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" value="{{ $pengaturan->alamat }}" disabled>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label" for="nama">Provinsi</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control"
                                    value="{{ $pengaturan->kota?->provinsi?->provinsi }}" disabled>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label" for="nama">Kota</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" value="{{ $pengaturan->kota->kota }}" disabled>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label" for="nama">phone</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" value="{{ $pengaturan->phone }}" disabled>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label" for="nama">phone 2</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" value="{{ $pengaturan->phone2 }}" disabled>
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
