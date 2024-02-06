@extends('member.layouts.layout')

@section('konten')
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="card mb-4">
            <h5 class="card-header">transaksi</h5>

            <div class="card-body">
                <div class="row">
                    <div class="col-sm-3 mt-2">
                        <input type="text" id="tanggal" class="form-control">
                    </div>
                    <div class="col-sm-4 mt-2">
                        <input type="text" id="cari" class="form-control" placeholder="Cari...">
                    </div>
                    <div class="col-sm-5 mt-2">
                        @haspermission('TRANSAKSI_PRINT')
                            {!! exportBtn('data', route('kasir.transaksi'), 'DATA TRANSAKSI') !!}
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
                        <th>tanggal</th>
                        <th>no. transaksi</th>
                        <th>total</th>
                        <th>bayar</th>
                        <th>kembali</th>
                        <th>item</th>
                        <th>member</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>


    <!-- bayar -->
    <div class="modal fade" id="modalTransaksiDetail" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1"
        aria-labelledby="modalTransaksiDetailLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTransaksiDetailLabel">Pembayaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-sm display nowrap">
                        <tbody>
                            <tr>
                                <td width="120"><span class="col-form-label">tanggal</span></td>
                                <td width="10">:</td>
                                <td id="created_at"></td>
                            </tr>
                            <tr>
                                <td><span class="col-form-label">total</span></td>
                                <td>:</td>
                                <td id="total"></td>
                            </tr>
                            <tr>
                                <td><span class="col-form-label">bayar</span></td>
                                <td>:</td>
                                <td id="bayar"></td>
                            </tr>
                            <tr>
                                <td><span class="col-form-label">kembali</span></td>
                                <td>:</td>
                                <td id="kembali"></td>
                            </tr>
                            <tr>
                                <td><span class="col-form-label">member</span></td>
                                <td>:</td>
                                <td id="member"></td>
                            </tr>
                        </tbody>
                    </table>

                    <table class="table table-sm table-bordered table-light text-dark">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>ITEM</th>
                                <th>HARGA</th>
                                <th>QTY</th>
                                <th>SUBTOTAL</th>
                            </tr>
                        </thead>
                        <tbody id="itemsBox"></tbody>
                    </table>

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
        $('#cari').focus();

        $('#tanggal').flatpickr({
            mode: 'range',
            defaultDate: ["{{ date('Y-m-01') }}", "{{ date('Y-m-d') }}"],
            maxDate: '{{ date('Y-m-d') }}',
            onChange: function() {
                datatables.ajax.reload();
                $('#cari').focus();
            }
        });

        var datatables = $('#datatable').DataTable({
            scrollY: false,
            scrollX: false,
            processing: true,
            serverSide: true,
            searching: false,
            lengthChange: false,
            pageLength: 10,
            bDestroy: true,
            info: false,
            order: [
                [0, 'desc']
            ],
            ajax: {
                url: "{{ route('kasir.transaksi') }}",
                type: "POST",
                data: function(d) {
                    d._token = $("input[name=_token]").val();
                    d.cari = $('#cari').val();
                    d.tanggal = $('#tanggal').val();
                },
            },
            columns: [{
                    data: 'created_at'
                },
                {
                    data: 'no_transaksi'
                },
                {
                    data: 'total',
                    render: function(data) {
                        return formatRupiah(data);
                    }
                },
                {
                    data: 'bayar',
                    render: function(data) {
                        return formatRupiah(data);
                    }
                },
                {
                    data: 'kembali',
                    render: function(data) {
                        return formatRupiah(data);
                    }
                },
                {
                    data: 'items_count'
                },
                {
                    data: 'member'
                },
            ],
            columnDefs: [{
                targets: [1],
                className: 'fw-bold'
            }]
        });

        $('#cari').keyup(function() {
            datatables.search($('#cari').val()).draw();
        });

        $('#datatable tbody').on('click', 'tr', function() {
            $('#modalTransaksiDetail').modal('show');

            const data = datatables.row(this).data();

            $('#modalTransaksiDetailLabel').text(data.no_transaksi);
            $("#created_at").text(data.created_at);
            $("#total").html('<b>' + formatRupiah(data.total) + '</b>');
            $("#bayar").text(formatRupiah(data.bayar));
            $("#kembali").text(formatRupiah(data.kembali));
            $("#member").text(data.member);

            let dataItems = '';
            let nomor = 1;
            for (const item of data.items) {
                dataItems += `<tr>
                                <td>${nomor++}</td>
                                <td>${item.produk}</td>
                                <td>${formatRupiah(item.harga)}</td>
                                <td>${item.qty}</td>
                                <td>${formatRupiah(item.subtotal)}</td>
                             </tr>`;
            }

            $('#itemsBox').html(dataItems);
        });

        function formatRupiah(angka) {
            var reverse = angka.toString().split('').reverse().join('');
            var ribuan = reverse.match(/\d{1,3}/g);
            var hasil = ribuan.join('.').split('').reverse().join('');
            return 'Rp ' + hasil;
        }
    </script>
@endsection
