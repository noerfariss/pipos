@extends('member.layouts.layout')

@section('konten')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <h5 class="card-header">Edit user</h5>
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

                        <form action="{{ route('user.update', ['user' => $user->id]) }}" method="POST"
                            enctype="multipart/form-data" id="my-form">
                            @csrf
                            @method('PATCH')

                            <input type="hidden" name="id" value="{{ $user->id }}">

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">nama</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="nama" value="{{ $user->nama }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">email</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="email" value="{{ $user->email }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">whatsapp</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="whatsapp"
                                        value="{{ $user->whatsapp }}">
                                </div>
                            </div>

                            @include('member.layouts.uploadfoto', [
                                'foto' => $user->foto,
                                'path' => 'foto',
                            ])

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">alamat</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="alamat" value="{{ $user->alamat }}">
                                </div>
                            </div>


                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">Roles</label>
                                <div class="col-sm-9">
                                    <select name="roles[]" id="roles" class="form-control select2" multiple>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}"
                                                {{ $user->roles->contains('id', $role->id) ? 'selected' : '' }}>
                                                {{ $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row ">
                                <div class="col-sm-9">
                                    <a href="{{ route('user.index') }}" class="btn btn-link btn-sm">Kembali</a>
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
    {!! JsValidator::formRequest('App\Http\Requests\User\UserCreateRequest', '#my-form') !!}
@endsection
