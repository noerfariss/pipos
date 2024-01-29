<div class="col-sm-2">
    <button class="btn btn-sm btn-dark" onclick="openKategoriModal()" type="button"><i
            class='bx bx-plus-circle'></i></button>
</div>

<!-- kategori -->
<div class="modal fade" id="modalKategori" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="modalKategoriLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="modalKategoriLabel">Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <span id="notif_kategori"></span>

                <div class="row mb-3">
                    <label class="col-sm-4 col-form-label">kategori</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="kategori_modal">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-sm btn-simpan btn-kategori">Tambahkan</button>
            </div>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script>
    function openKategoriModal() {
        $('#modalKategori').modal('show');
    }

    // --- kategori FORM
    $('.btn-kategori').click(function(e) {
        e.preventDefault();

        let notif_kategori = $('#notif_kategori');
        const url = "{{ route('kategori.store') }}";

        $.ajax({
                type: 'POST',
                url: url,
                data: {
                    _token: $('input[name="_token"]').val(),
                    kategori: $('input[name="kategori_modal"]').val(),
                }
            })
            .done(function(e) {
                const msg = e.message;
                $(notif_kategori).html(
                    `<div class="alert alert-success">kategori berhasil ditambahkan</div>`);

                setTimeout(() => {
                    $('#modalkategori').modal('hide');
                    $(notif_kategori).html('');
                    $('input[name="kategori_modal"]').val('');
                }, 1500);
            })
            .fail(function(err) {
                const errors = err.responseJSON.errors;
                let show_notif = '';

                $.each(errors, function(i, val) {
                    $.each(val, function(x, y) {
                        show_notif +=
                            `<div class="alert alert-danger">${y}</div>`;
                    });
                });

                $(notif_kategori).html(show_notif);
            });
        return false;
    });
</script>
