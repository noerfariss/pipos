<div class="modal fade" id="modalMember" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1"
    aria-labelledby="modalMemberLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Daftar Member</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <input type="text" id="cariMemberTable" class="form-control"
                            placeholder="Cari nama atau nomor whatsapp...">
                    </div>
                </div>
                <table class="table table-sm nowrap display" id="memberTable">
                    <thead>
                        <tr>
                            <th>member</th>
                            <th>whatsapp</th>
                            <th>email</th>
                            <th>gender</th>
                            <th></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap5.min.js"></script>
