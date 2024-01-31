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

                        <form action="{{ route('pengaturan.update') }}" method="POST" enctype="multipart/form-data"
                            id="my-form">
                            @csrf
                            @method('PATCH')

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label" for="nama">Nama</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="nama"
                                        value="{{ $pengaturan->nama }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label" for="nama">Alamat</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="alamat"
                                        value="{{ $pengaturan->alamat }}">
                                </div>
                            </div>

                            @include('member.layouts.uploadfoto', [
                                'foto' => $pengaturan->logo,
                                'path' => 'foto',
                                'title' => 'Logo',
                            ])

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label" for="nama">Provinsi</label>
                                <div class="col-sm-9">
                                    <select name="provinsi" id="" class="form-control provinsi-select"
                                        data-ajax--url="{{ route('drop-provinsi') }}">
                                        @if ($pengaturan->kota_id)
                                            <option value="{{ $pengaturan->kota?->provinsi_id }}" selected>
                                                {{ $pengaturan->kota?->provinsi->provinsi }}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label" for="nama">Kota</label>
                                <div class="col-sm-9">
                                    <select name="kota_id" id="" class="form-control kota-select"
                                        data-ajax--url="{{ route('drop-kota') }}">
                                        @if ($pengaturan->kota_id)
                                            <option value="{{ $pengaturan->kota_id }}" selected>
                                                {{ $pengaturan->kota?->kota }}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label" for="nama">phone</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="phone"
                                        value="{{ $pengaturan->phone }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label" for="nama">phone2</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="phone2"
                                        value="{{ $pengaturan->phone2 }}">
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
@endsection

@section('script')
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js') }}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\Pengaturan\PengaturanUpdateRequest', '#my-form') !!}
@endsection
