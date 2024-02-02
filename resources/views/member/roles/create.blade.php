@extends('member.layouts.layout')

@section('konten')
    <div class="container-xxl flex-grow-1 container-p-y">
        <form action="{{ route('role.store') }}" method="POST" enctype="multipart/form-data" id="my-form">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-4">
                        <h5 class="card-header">Tambah role</h5>
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



                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">role</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="name">
                                </div>
                            </div>

                            <div class="row mt-5">
                                <div class="col-sm-12">
                                    <a href="{{ route('role.index') }}" class="btn btn-link btn-sm">Kembali</a>
                                    <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                                </div>
                            </div>

                        </div>
                        <!-- /Account -->
                    </div>

                </div>

                <div class="col-md-6">
                    <div class="card mb-4">
                        <h5 class="card-header">Permission</h5>
                        <div class="card-body">
                            <table class="table table-sm table-hover" id="datatable">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Permission</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <!-- /Account -->
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('script')
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js') }}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\Role\RoleCreateRequest', '#my-form') !!}

    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap5.min.js"></script>

    <style>
        #datatable_filter {
            display: block !important;
        }
    </style>

    <script>
        var datatables = $('#datatable').DataTable({
            scrollY: '300px',
            scrollX: false,
            processing: true,
            serverSide: false,
            // searching: true,
            lengthChange: false,
            paging: false,
            bDestroy: true,
            info: false,
            ajax: {
                url: "{{ route('permission.ajax') }}",
                type: "POST",
                data: function(d) {
                    d._token = $("input[name=_token]").val();
                    d.cari = $('#cari').val();
                },
            },
            columns: [{
                    data: 'id',
                    render: function(data) {
                        return `<input type="checkbox" value="${data}" name="permission[]">`;
                    }
                },
                {
                    data: 'name'
                },
            ],
        });

        $('#cari').keyup(function() {
            datatables.search($('#cari').val()).draw();
        });
    </script>
@endsection
