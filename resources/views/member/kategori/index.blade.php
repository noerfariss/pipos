@extends('member.layouts.layout')

@section('konten')
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="card mb-4">
            <h5 class="card-header">kategori
                {!! statusBtn() !!}
            </h5>

            <div class="card-body">
                <div class="row">
                    <div class="col-sm-3 mt-2">
                        <input type="text" id="cari" class="form-control" placeholder="Cari...">
                    </div>
                    <div class="col-sm-9 mt-2">
                        @haspermission('KATEGORI_CREATE')
                            <a href="{{ route('kategori.create') }}" class="btn btn-sm btn-outline-primary float-end">
                                <i class='bx bx-plus'></i> tambah
                            </a>
                        @endhaspermission

                        @haspermission('KATEGORI_PRINT')
                            {!! exportBtn('data', route('kategori.ajax'), 'DATA KATEGORI') !!}
                        @endhaspermission
                    </div>
                </div>

                @if (session()->has('pesan'))
                    {!! session('pesan') !!}
                @endif
            </div>

            <table class="table table-hover display nowrap noscroll mb-4" id="datatable">
                <thead>
                    <tr>
                        <th>kode</th>
                        <th>kategori</th>
                        <th>item</th>
                        <th>sku stok</th>
                        <th>status</th>
                        <th></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    @include('member.layouts.modalDetailTable')
@endsection

@section('script')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap5.min.js"></script>

    <script>
        var datatables = $('#datatable').DataTable({
            scrollY: false,
            scrollX: false,
            processing: true,
            serverSide: true,
            searching: false,
            lengthChange: false,
            pageLength: 10,
            bDestroy: true,
            ajax: {
                url: "{{ route('kategori.ajax') }}",
                type: "POST",
                data: function(d) {
                    d._token = $("input[name=_token]").val();
                    d.status = $('.btn-check:checked').val();
                    d.cari = $('#cari').val();
                },
            },
            columns: [{
                    data: 'kode_label'
                },
                {
                    data: 'kategori'
                },
                {
                    data: 'produk_count'
                },
                {
                    data: 'total_stok'
                },
                {
                    data: 'status'
                },
                {
                    data: 'aksi'
                },
            ]
        });

        $('#cari').keyup(function() {
            datatables.search($('#cari').val()).draw();
        });

        $('#datatable tbody').on('click', 'tr td:not(:last-child)', function() {
            $('#modalTransaksiDetail').modal('show');

            const data = datatables.row(this).data();
            $('#modalDetailTable').modal('show');
            $('#modalDetailTableLabel').text('KATEGORI DETAIL');

            const dataTable = `
                <table class="table table-sm table-hover">
                    <tbody>
                        <tr>
                            <td class="col-form-label">kode</td>
                            <td>:</td>
                            <td>${data.kode_label}</td>
                        </tr>
                        <tr>
                            <td class="col-form-label">kategori</td>
                            <td>:</td>
                            <td>${data.kategori}</td>
                        </tr>
                        <tr>
                            <td class="col-form-label">items</td>
                            <td>:</td>
                            <td>${data.produk_count}</td>
                        </tr>
                        <tr>
                            <td class="col-form-label">sku stok</td>
                            <td>:</td>
                            <td>${data.total_stok}</td>
                        </tr>
                    </tbody>
                </table>
            `;

            $('#modalDetailTableBody').html(dataTable);
        });
    </script>
@endsection
