@extends('member.layouts.layout')

@section('konten')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-8 mb-4 order-0">
                <div class="card">
                    <div class="d-flex align-items-end row">
                        <div class="col-sm-7">
                            <div class="card-body">
                                <h5 class="card-title text-primary">Halooo <b>{{ Auth::user()->nama }}</b> Selamat datang
                                    kembali ! 🎉</h5>
                                <p class="mb-4">
                                    Selamat beraktifitas & tetap semangat!
                                </p>

                                <a href="https://wa.me/6285171737359" target="_blank" class="btn btn-sm btn-outline-primary">help desk</a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="row">



        </div>
    </div>
@endsection
