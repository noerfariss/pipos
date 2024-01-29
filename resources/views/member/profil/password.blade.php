@extends('member.layouts.layout')

@section('konten')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-12">
                <ul class="nav nav-pills flex-column flex-md-row mb-3">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('password.index') }}"><i class='bx bxs-key'></i> Ganti
                            Password</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href=""><i class='bx bx-bar-chart'></i> Aktivitas</a>
                    </li>
                </ul>

                <div class="card mb-4">
                    <h5 class="card-header">Password</h5>

                    <div class="card-body">
                        @if (session()->has('pesan'))
                            {!! session('pesan') !!}
                        @endif

                        <form action="" method="POST" enctype="multipart/form-data" id="my-form">
                            @csrf
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="password_lama">Password Lama</label>
                                <div class="col-sm-4">
                                    <input type="password" class="form-control" name="password_lama">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="password">Password Baru</label>
                                <div class="col-sm-4">
                                    <input type="password" class="form-control" name="password">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="password">Konfirmasi Password</label>
                                <div class="col-sm-4">
                                    <input type="password" class="form-control" name="password_confirmation">
                                </div>
                            </div>

                            <div class="row justify-content-end">
                                <div class="col-sm-0">
                                    <button type="submit" class="btn btn-sm btn-primary">Perbarui</button>
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
    {!! JsValidator::formRequest('App\Http\Requests\User\PasswordUpdateRequest', '#my-form') !!}
@endsection
