@extends('member.layouts.layout')

@section('konten')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-12">
                @include('member.profil.headerprofil')

                <form id="my-form" method="POST" enctype="multipart/form-data" action="{{ route('profil.update') }}"
                    id="my-form">
                    @csrf
                    @method('PATCH')
                    <div class="card mb-4">
                        <h5 class="card-header">Detail Profil</h5>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-4">
                                    @include('member.layouts.uploadfoto', [
                                        'foto' => Auth::user()->foto,
                                        'path' => 'foto',
                                    ])
                                </div>
                            </div>
                        </div>

                        <hr class="my-0" />
                        <div class="card-body">
                            @if (session()->has('pesan'))
                                {!! session('pesan') !!}
                            @endif


                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label for="nama" class="form-label">Nama</label>
                                    <input class="form-control" type="text" id="nama" name="nama"
                                        value="{{ Auth::user()->nama }}" autofocus />
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label for="email" class="form-label">E-mail</label>
                                    <input class="form-control" type="text" id="email" name="email"
                                        value="{{ Auth::user()->email }}" />
                                </div>
                            </div>

                            <div class="mt-2">
                                <button type="submit" class="btn btn-primary me-2">Perbarui</button>
                            </div>

                        </div>
                        <!-- /Account -->
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js') }}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\User\ProfilUpdateRequest', '#my-form') !!}
@endsection
