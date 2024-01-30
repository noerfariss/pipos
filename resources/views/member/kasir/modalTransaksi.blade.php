<div class="modal fade" id="modalTransaksi" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1"
    aria-labelledby="modalTransaksiLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Transaksi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <input type="text" id="cariTransaksiTable" class="form-control"
                            placeholder="Cari kode transaksi...">
                    </div>
                </div>
                <table class="table table-sm nowrap display" id="transaksiTable">
                    <thead>
                        <tr>
                            <th>kode</th>
                            <th>total</th>
                            <th>bayar</th>
                            <th>kembali</th>
                            <th>tanggal</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
