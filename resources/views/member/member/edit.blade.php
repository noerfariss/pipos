@extends('member.layouts.layout')

@section('konten')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <h5 class="card-header">Edit member</h5>
                    <div class="card-body">
                        @if (session()->has('pesan'))
                            {!! session('pesan') !!}
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('member.update', ['member' => $member->id]) }}" method="POST"
                            enctype="multipart/form-data" id="my-form">
                            @csrf
                            @method('PATCH')

                            <input type="hidden" name="id" value="{{ $member->id }}">

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">nama</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="nama" value="{{ $member->nama }}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">jenis kelamin</label>
                                <div class="col-sm-9">
                                    <select name="jenis_kelamin" id="jenis_kelamin" class="form-control select2">
                                        <option value="1" {{ $member->jenis_kelamin ? 'selected' : '' }}>
                                            Laki-laki</option>
                                        <option value="0" {{ !$member->jenis_kelamin ? 'selected' : '' }}>
                                            Perempuan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">whatsapp</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="phone" value="{{ $member->phone }}" placeholder="081234xxxx">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">email</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="email" value="{{ $member->email }}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">alamat</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="alamat" value="{{ $member->alamat }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">kota</label>
                                <div class="col-sm-9">
                                    <select name="kota_id" id="kota" class="form-control kota-select"
                                        data-ajax--url="{{ route('drop-kota') }}">
                                        <option value="{{ $member->kota_id }}">{{ $member->kota->kota }}</option>
                                    </select>
                                </div>
                            </div>

                            @include('member.layouts.uploadfoto', [
                                'path' => 'member',
                                'foto' => $member->foto,
                            ])

                            <div class="row ">
                                <div class="col-sm-12">
                                    <a href="{{ route('member.index') }}" class="btn btn-link btn-sm">Kembali</a>
                                    <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- /Account -->
                </div>

            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js') }}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\Member\MemberUpdateRequest', '#my-form') !!}
@endsection
