@extends('member.layouts.layout')

@section('konten')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <h5 class="card-header">Tambah Stok masuk</h5>
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

                        <form action="{{ route('stokin.store') }}" method="POST" enctype="multipart/form-data" id="my-form">
                            @csrf

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">produk <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <select name="produk_id" id="produk_id" class="form-control produk-select"
                                        data-ajax--url="{{ route('drop-produk') }}"></select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">qty <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="qty">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">suplier</label>
                                <div class="col-sm-9">
                                    <select name="suplier_id" id="suplier_id" class="form-control suplier-select"
                                        data-ajax--url="{{ route('drop-suplier') }}"></select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">keterangan</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="keterangan">
                                </div>
                            </div>


                            <div class="row mt-5">
                                <div class="col-sm-12">
                                    <a href="{{ route('stokin.index') }}" class="btn btn-link btn-sm">Kembali</a>
                                    <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>

            <div class="col-sm-6" id="detailProdukBox" style="display: none">
                <div class="card">
                    <h5 class="card-header">Detail Produk</h5>
                    <div class="card-body">
                        <table class="table table-sm table-hover">
                            <tr>
                                <td width="60">Kode</td>
                                <td wdith="200" id="detail_kode"></td>
                            </tr>
                            <tr>
                                <td>Produk</td>
                                <td id="detail_produk"></td>
                            </tr>
                            <tr>
                                <td>Kategori</td>
                                <td id="detail_kategori"></td>
                            </tr>
                            <tr>
                                <td>Stok</td>
                                <td id="detail_stok"></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js') }}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\Stok\StokInRequest', '#my-form') !!}

    <script>
        $(document).ready(function() {
            $('.produk-select').change(function(e) {
                const id = $(this).val();
                const base_url = '{{ url('/') }}';

                const getData = async () => {
                    const req = await fetch(`${base_url}/auth/produk/id/${id}`);
                    const res = await req.json();
                    const data = res.data;

                    $('#detailProdukBox').show();

                    $('#detail_kode').text(data.kode);
                    $('#detail_produk').text(data.produk);
                    $('#detail_kategori').text(data.kategori);
                    $('#detail_stok').text(data.stok);
                }

                getData();

            })
        });
    </script>
@endsection
