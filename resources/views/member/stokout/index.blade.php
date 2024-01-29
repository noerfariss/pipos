@extends('member.layouts.layout')

@section('konten')
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="card mb-4">
            <h5 class="card-header">Stok keluar
            </h5>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-3 col-6 mt-2">
                        <input type="text" name="tanggal" id="tanggal" class="form-control">
                    </div>
                    <div class="col-sm-2 col-6 mt-2">
                        <select name="tipe" id="tipe" class="form-control select2" onchange="datatables.ajax.reload()">
                            <option value="">-- Tipe --</option>
                            <option value="2">Stok Keluar</option>
                            <option value="3">Penjualan</option>
                        </select>
                    </div>
                    <div class="col-sm-4 col-6 mt-2">
                        <input type="text" id="cari" class="form-control" placeholder="Cari...">
                    </div>
                    <div class="col-sm-3 col-6 mt-2">
                        @haspermission('STOKOUT_CREATE')
                            <a href="{{ route('stokout.create') }}" class="btn btn-sm btn-primary float-end">Tambah</a>
                        @endhaspermission

                        @haspermission('STOKOUT_PRINT')
                            {!! exportBtn('data', route('stokout.ajax'), 'DATA STOK KELUAR') !!}
                        @endhaspermission
                    </div>
                </div>

                @if (session()->has('pesan'))
                    {!! session('pesan') !!}
                @endif
            </div>

            <table class="table table-hover display nowrap mb-4" id="datatable">
                <thead>
                    <tr>
                        <th>tanggal</th>
                        <th>tipe</th>
                        <th>kode produk</th>
                        <th>produk</th>
                        <th>qty</th>
                        <th>suplier</th>
                        <th>alasan</th>
                        <th>keterangan</th>
                    </tr>
                </thead>
            </table>

        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="modalDetailTable" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="modalDetailTableLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDetailTableLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalDetailTableBody">

                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap5.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
        $('#tanggal').flatpickr({
            mode: 'range',
            defaultDate: ["{{ date('Y-m-01') }}", "{{ date('Y-m-d') }}"],
            maxDate: '{{ date('Y-m-d') }}',
            onClose: function() {
                datatables.ajax.reload();
            }
        });


        var datatables = $('#datatable').DataTable({
            scrollX: true,
            scrollY: false,
            processing: true,
            serverSide: true,
            searching: false,
            lengthChange: false,
            pageLength: 10,
            bDestroy: true,
            info: false,
            responsive: true,
            order: [
                [0, 'desc']
            ],
            ajax: {
                url: "{{ route('stokout.ajax') }}",
                type: "POST",
                data: function(d) {
                    d._token = $("input[name=_token]").val();
                    d.tanggal = $('#tanggal').val();
                    d.tipe = $('#tipe').val();
                    d.cari = $('#cari').val();
                },
            },
            columns: [{
                    data: 'created_at'
                },
                {
                    data: 'tipe'
                },
                {
                    data: 'kode_produk'
                },
                {
                    data: 'produk'
                },
                {
                    data: 'qty',
                },
                {
                    data: 'suplier'
                },
                {
                    data: 'reason'
                },
                {
                    data: 'keterangan'
                },
            ]
        });

        $('#cari').keyup(function() {
            datatables.search($('#cari').val()).draw();
        });
    </script>
@endsection
