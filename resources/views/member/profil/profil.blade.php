@extends('member.layouts.layout')

@section('konten')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-12">
                <ul class="nav nav-pills flex-column flex-md-row mb-3">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('password.index') }}"><i class="bx bx-bell me-1"></i> Ganti
                            Password</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href=""><i class="bx bx-link-alt me-1"></i>
                            Aktivitas</a>
                    </li>
                </ul>

                <div class="card mb-4">
                    <h5 class="card-header">Detail Profil</h5>
                    <!-- Account -->
                    <div class="card-body">
                        <div class="d-flex align-items-start align-items-sm-center gap-4">
                            <img src="{{ Auth::user()->foto === null || Auth::user()->foto == '' ? asset('images/anggota/pria.jpg') : url('/storage' . '/' . Auth::user()->foto) }}"
                                alt="user-avatar" class="d-block rounded" height="100" width="100"
                                id="uploadedAvatar" />

                            <div class="button-wrapper">
                                <button class="account-file-input btn btn-sm btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#modalUploadFoto">
                                    <span class="d-none d-sm-block">Ganti foto</span>
                                    <i class="bx bx-upload d-block d-sm-none"></i>
                                </button>

                                <small class="text-muted mb-0">JPG, GIF, PNG. Maksimal ukuran 2000 Kb</smal>
                            </div>
                        </div>
                    </div>

                    <hr class="my-0" />
                    <div class="card-body">
                        @if (session()->has('pesan'))
                            {!! session('pesan') !!}
                        @endif

                        <form id="my-form" method="POST" enctype="multipart/form-data" action="">
                            @csrf
                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label for="username" class="form-label">Username</label>
                                    <input class="form-control" type="text" id="username" name="username" value=""
                                        readonly />
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label for="nama" class="form-label">Nama user</label>
                                    <input class="form-control" type="text" id="nama" name="nama" value=""
                                        autofocus />
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label for="email" class="form-label">E-mail</label>
                                    <input class="form-control" type="text" id="email" name="email"
                                        value="" />
                                </div>
                            </div>

                            <div class="mt-2">
                                <button type="submit" class="btn btn-primary me-2">Perbarui</button>
                            </div>
                        </form>
                    </div>
                    <!-- /Account -->
                </div>

            </div>
        </div>
    </div>
@endsection
