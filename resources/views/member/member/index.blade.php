@extends('member.layouts.layout')

@section('konten')
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="card mb-4">
            <h5 class="card-header">member
                {!! statusBtn() !!}
            </h5>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-4 col-6 mt-2"><input type="text" id="cari" class="form-control"
                            placeholder="Cari...">
                    </div>
                    <div class="col-sm-8 col-6 mt-2">
                        @haspermission('MEMBER_CREATE')
                            <a href="{{ route('member.create') }}" class="btn btn-sm btn-primary float-end">Tambah</a>
                        @endhaspermission

                        @haspermission('MEMBER_PRINT')
                            {!! exportBtn('data', route('member.ajax'), 'DATA MEMBER') !!}
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
                        <th>foto</th>
                        <th>member</th>
                        <th>whatsapp</th>
                        <th>email</th>
                        <th>gender</th>
                        <th>status</th>
                        <th>register</th>
                        <th></th>
                    </tr>
                </thead>
            </table>

        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="modalGantiPassword" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="modalGantiPasswordLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <form action="{{ route('member.password')}}" method="POST" enctype="multipart/form-data" id="form-ganti-password">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalGantiPasswordLabel">Ganti Password</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <span id="notif"></span>

                        @csrf
                        <div class="row mb-3">
                            <label class="col-sm-4 col-form-label" for="nama">Nama</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control nama" disabled>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-4 col-form-label" for="phone">Whatsapp</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control phone" disabled>
                            </div>
                        </div>
                         <div class="row mb-3">
                            <label class="col-sm-4 col-form-label" for="email">Email</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control email" disabled>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <input type="hidden" id="id" name="id">
                            <label class="col-sm-4 col-form-label" for="password">Password</label>
                            <div class="col-sm-8">
                                <input type="password" class="form-control" id="password" name="password">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-4 col-form-label" for="password_confirmation">Konfirmasi Password</label>
                            <div class="col-sm-8">
                                <input type="password" class="form-control" id="password_confirmation"
                                    name="password_confirmation">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-sm btn-simpan">Perbarui</button>
                    </div>
                </form>
            </div>
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

    <script>
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
                [6, 'desc']
            ],
            ajax: {
                url: "{{ route('member.ajax') }}",
                type: "POST",
                data: function(d) {
                    d._token = $("input[name=_token]").val();
                    d.status = $('.btn-check:checked').val();
                    d.cari = $('#cari').val();
                },
            },
            columns: [{
                    data: 'foto'
                },
                {
                    data: 'member'
                },
                {
                    data: 'phone'
                },
                {
                    data: 'email'
                },
                {
                    data: 'jenis_kelamin',
                },
                {
                    data: 'status'
                },
                {
                    data: 'created_at'
                },
                {
                    data: 'aksi'
                },
            ]
        });

        $('#cari').keyup(function() {
            datatables.search($('#cari').val()).draw();
        });

        $('.table').on('click', '.btn-open-ganti-password', function(e) {
            e.preventDefault();
            let nama = $(this).data('nama');
            let phone = $(this).data('phone');
            let email = $(this).data('email');
            let uuid = $(this).data('uuid');

            $('#modalGantiPassword').modal('show');
            $('#notif').html('');
            $('#password, #password_confirmation').val('');

            $('#id').val(uuid);
            $('.nama').val(nama);
            $('.phone').val(phone);
            $('.email').val(email);
        });

        $('#form-ganti-password').submit(function(e) {
            e.preventDefault();
            let data = $(this).serialize();
            let url = $(this).attr('action');

            $.ajax({
                    type: 'POST',
                    url: url,
                    data: data,
                })
                .done(function(e) {
                    $('#notif').html(`<div class="alert alert-success">Password berhasil dirubah</div>`);
                    setTimeout(() => {
                        $('#modalGantiPassword').modal('hide');
                    }, 1500);
                })
                .fail(function(e) {
                    let response = e.responseJSON;
                    let errors = response.errors.password;

                    let notif = '<ul>';
                    $.each(errors, function(i, val) {
                        notif += `<li>${val}</li>`;
                    });
                    notif += '</ul>';

                    $('#notif').html('');
                    $('#notif').html(`<div class="alert alert-danger">${notif}</div>`);
                });
        });
    </script>
@endsection
