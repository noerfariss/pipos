    <div class="row mb-3">
        <label class="col-sm-3 col-form-label" for="box-foto">PDF</label>
        <div class="col-sm-9">
            <div class="button-wrapper">
                <button type="button" class="account-file-input btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                    data-bs-target="#modalUploadPDF">
                    <span class="d-none d-sm-block">Ganti {{ isset($title) ? $title : 'Foto' }}</span>
                    <i class="bx bx-upload d-block d-sm-none"></i>
                </button>
                <input type="hidden" name="pdf" id="pdf" value="">
                <div>
                    <small class="text-muted mb-0">Format : JPG, GIF, PNG. Maksimal ukuran 2000 Kb</small>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <label class="col-sm-3 col-form-label" for="box-pdf"></label>
        <div class="col-sm-9">
            <div id="box-pdf">
                @isset($pdf)
                    @if ($pdf)
                        <img src="{{ asset('images/buku/pdf-icon.png') }}" width="60">
                        <button type="button" class="btn btn-xs btn-outline-danger mt-2">Lihat PDF</button>
                    @endif
                @endisset
            </div>
        </div>
    </div>

    <!-- UPLOAD PDF -->
    <div class="modal fade" id="modalUploadPDF" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="modalUploadPDFLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalUploadPDFLabel">Unggah Berkas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <span id="notif-pdf"></span>

                    <div class="dropzone" id="mydropzonepdf"></div>
                    @csrf
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-sm btn-simpan"
                        onclick="simpanPDF()">Tambahkan</button>
                </div>
            </div>
        </div>
    </div>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />

    <script>
        // If you use jQuery, you can use the jQuery plugin Dropzone ships with:
        Dropzone.autoDiscover = false;

        // Dropzone class:
        var myDropzone = new Dropzone("div#mydropzonepdf", {
            url: "{{ route('master.pdf') }}",
            maxFilesize: 10000,
            acceptedFiles: ".pdf",
            method: 'post',
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val(),
            },
            init: function() {
                this.on("addedfile", file => {
                    $('.btn-simpan').attr('disabled', 'disabled').text('Loading...');
                });
            },
            success: function(file, response) {
                $('.btn-simpan').removeAttr('disabled').text('Tambahkan');
                const foto = response.file;
                $('.modal-body #notif-pdf').html(
                    `<div class="alert alert-success">Berkas berhasil diunggah</div>`);
                $('#pdf').val(foto);
            },
            error: function(file, response) {
                $('.btn-simpan').removeAttr('disabled').text('Tambahkan');
                const pesan = response.message;
                $('.modal-body #notif-pdf').html(`<div class="alert alert-danger">${pesan}</div>`);
            }
        });


        function simpanPDF() {
            let pdf = $('#pdf').val();

            if (pdf === '' || pdf === null) {
                $('#notif-pdf').html(`<div class="alert alert-danger">Tidak dapat menambahkan berkas</div>`);
            } else {
                $('#modalUploadPDF').modal('hide');
                $('#box-pdf').html(
                    `<img src="{{ asset('images/buku/pdf-icon.png') }}" width="60">`);
            }
        }
    </script>
