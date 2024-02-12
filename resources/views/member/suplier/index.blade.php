@extends('member.layouts.layout')

@section('konten')
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="card mb-4">
            <h5 class="card-header">suplier
                {!! statusBtn() !!}
            </h5>

            <div class="card-body">
                <div class="row">
                    <div class="col-sm-3 mt-2">
                        <input type="text" id="cari" class="form-control" placeholder="Cari...">
                    </div>
                    <div class="col-sm-9 mt-2">
                        @haspermission('SUPLIER_CREATE')
                            <a href="{{ route('suplier.create') }}" class="btn btn-sm btn-outline-primary float-end">
                                <i class='bx bx-plus'></i> tambah
                            </a>
                        @endhaspermission

                        @haspermission('SUPLIER_PRINT')
                            {!! exportBtn('data', route('suplier.ajax'), 'DATA suplier') !!}
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
                        <th>suplier</th>
                        <th>alamat</th>
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
            scrollY: true,
            scrollX: false,
            processing: true,
            serverSide: true,
            searching: false,
            lengthChange: false,
            pageLength: 10,
            bDestroy: true,
            ajax: {
                url: "{{ route('suplier.ajax') }}",
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
                    data: 'suplier'
                },
                {
                    data: 'alamat',
                    render: function(data) {
                        return potongTeks(data, 7);
                    }
                },
                {
                    data: 'status'
                },
                {
                    data: 'aksi'
                },
            ]
        });

        $('#datatable tbody').on('click', 'tr td:not(:last-child)', function() {
            $('#modalTransaksiDetail').modal('show');

            const data = datatables.row(this).data();
            $('#modalDetailTable').modal('show');
            $('#modalDetailTableLabel').text('SUPLIER DETAIL');

            const dataTable = `
                <table class="table table-sm table-hover">
                    <tbody>
                        <tr>
                            <td class="col-form-label">kode</td>
                            <td>:</td>
                            <td>${data.kode_label}</td>
                        </tr>
                        <tr>
                            <td class="col-form-label">suplier</td>
                            <td>:</td>
                            <td>${data.suplier}</td>
                        </tr>
                        <tr>
                            <td class="col-form-label">alamat</td>
                            <td>:</td>
                            <td>${data.alamat}</td>
                        </tr>
                    </tbody>
                </table>
            `;

            $('#modalDetailTableBody').html(dataTable);
        });

        $('#cari').keyup(function() {
            datatables.search($('#cari').val()).draw();
        });

        function potongTeks(teks, jumlahKata) {
            // Pisahkan teks menjadi array kata-kata
            var kata = teks.split(' ');

            // Periksa apakah jumlah kata dalam teks lebih besar dari jumlah yang diinginkan
            if (kata.length > jumlahKata) {
                // Potong array kata-kata hingga jumlah kata yang diinginkan
                kata = kata.slice(0, jumlahKata);

                // Gabungkan kembali array kata-kata menjadi teks
                teks = kata.join(' ') + '...'; // Tambahkan elipsis sebagai penanda bahwa teks dipotong
            }

            return teks;
        }
    </script>
@endsection
