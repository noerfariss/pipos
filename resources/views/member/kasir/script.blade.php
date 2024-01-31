<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script src="{{ asset('sneat-1.0.0/assets/vendor/js/helpers.js') }}"></script>
<script src="{{ asset('sneat-1.0.0/assets/vendor/js/bootstrap.js') }}"></script>
<script src="{{ asset('sneat-1.0.0/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
<script src="{{ asset('sneat-1.0.0/assets/vendor/js/menu.js') }}"></script>
<script src="{{ asset('sneat-1.0.0/assets/js/main.js') }}"></script>

<link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap5.min.js"></script>

<script>
    let itemsArray = [];

    myTable();

    function myTable() {
        let myTable = $('#myTable').DataTable({
            scrollX: false,
            scrollY: "400px",
            searching: false,
            lengthChange: false,
            paging: false,
            info: false,
            responsive: true,
            bDestroy: true,
            data: itemsArray,
            columns: [{
                    data: 'nomor'
                },
                {
                    data: 'produk'
                },
                {
                    data: 'harga',
                    render: function(data) {
                        return formatRupiah(data);
                    }
                },
                {
                    data: 'qty',
                    render: function(data, type, row) {
                        const id = row.id;
                        return `<input type="number" class="updateQty" data-pre="${data}" data-uuid="${id}" style="width:70px;" value="${data}">`;
                    }
                },
                {
                    data: 'subtotal',
                    render: function(data) {
                        return formatRupiah(data);
                    }
                },
                {
                    data: 'id',
                    render: function(data) {
                        return `<button type="button" onclick="hapusItemById('${data}')" class="float-end btn btn-xs rounded btn-danger"><i class='bx bx-trash me-2'></i> Delete</button>`;
                    }
                },
            ]
        });
    }

    $(document).ready(function() {
        // =---- Init
        $('.member[value="0"]').prop('checked', true);
        const memberCheck = $('.member:checked').val();
        $('#member_cari').attr('disabled', 'disabled').val('');
        $('#member_detail').hide();
        $('#member_item_qty').val(1);
        $('#member_item_cari').val('');
        $('.totalAll').text(0);
        $('.totalAllModal').text(0);
        $('#totalAllModalValue').val(0);
        $('#modalBayarKembaliValue').val(0);
        $('#modalBayarKembali').text(0);
        $('#modal_bayar_input').simpleMoneyFormat();
        $('#btn-bayar').attr('disabled', 'disabled');

        // pilihan member atau umum
        $(".member").click(function() {
            const val = $(this).val();
            if (val == 1) {
                $('#member_cari').removeAttr('disabled').focus();

            } else {
                $('#member_cari').attr('disabled', 'disabled').val('');
                $('#member_detail').hide();
            }
        });


        // ------------ CARI MEMBER JIKA ADA
        $('#member_cari_form').submit(function(e) {
            e.preventDefault();
            const memberCode = $('#member_cari').val();
            const baseUrl = '{{ url('/') }}';
            const token = $('input[name="_token"]').val();

            if (memberCode !== '' && memberCode !== null) {
                const getData = async () => {
                    try {
                        const req = await fetch(`${baseUrl}/auth/member/customer`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json' // Anda dapat mengubah tipe konten sesuai kebutuhan
                            },
                            body: JSON.stringify({
                                _token: token,
                                key: memberCode
                            })
                        });

                        const res = await req.json();

                        if (!req.ok) {
                            $('#member_detail').hide();

                            Swal.fire({
                                title: res.message,
                                icon: 'error',
                                showCancelButton: false,
                                showConfirmButton: false,
                                timer: 3000
                            });

                        } else {
                            const data = res.data;

                            $('#member_detail').show();
                            $('#member_uuid').val(data.uuid);
                            $('#member_nama').text(data.nama);
                            $('#member_phone').text(data.whatsapp);
                            $('#member_alamat').text(data.alamat);
                        }




                    } catch (error) {
                        console.log(error);
                        $('#member_detail').hide();
                    }
                }

                getData();
            }

        });

        // ----------- INPUT PRODUK / ITEM
        $('#member_item_cari_form input').keypress(function(e) {
            if (e.which === 13) {
                e.preventDefault();

                const baseUrl = '{{ url('/') }}';
                const token = $('input[name="_token"]').val();
                const item = $('#member_item_cari').val();
                const qty = $('#member_item_qty').val();

                if (item !== '' && item !== null) {
                    const getData = async () => {
                        try {
                            const req = await fetch(`${baseUrl}/auth/produk/cariproduk`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json' // Anda dapat mengubah tipe konten sesuai kebutuhan
                                },
                                body: JSON.stringify({
                                    _token: token,
                                    key: item,
                                    qty: qty
                                })
                            });

                            const res = await req.json();

                            if (!req.ok) {
                                $('#member_item_cari').val('').focus();

                                Swal.fire({
                                    title: res.message,
                                    icon: 'error',
                                    showCancelButton: false,
                                    showConfirmButton: false,
                                    timer: 3000
                                });

                            } else {
                                const data = res.data;

                                const id = data.uuid;
                                const produk = data.produk;
                                const harga = data.harga_asli;
                                const subtotal = harga * qty;
                                const currentStok = data.stok;

                                // cek apakah di dalam items array sudah ada produknya?
                                const produkYangAda = itemsArray.find(function(item) {
                                    return item.id === id;
                                });

                                if (produkYangAda) {
                                    const prediksiStok = Number(qty) + Number(produkYangAda
                                        .qty);

                                    if (currentStok < prediksiStok) {
                                        Swal.fire({
                                            title: 'Stok tidak mencukupi ',
                                            icon: 'error',
                                            showCancelButton: false,
                                            showConfirmButton: false,
                                            timer: 3000
                                        });

                                    } else {
                                        // jika produk sudah diiinputkan, update qty dan subtotalnya
                                        const newQty = Number(produkYangAda.qty) + Number(qty);
                                        const newSubtotal = Number(produkYangAda.harga) *
                                            newQty;

                                        produkYangAda.qty = newQty;
                                        produkYangAda.subtotal = newSubtotal;
                                    }

                                } else {
                                    let nomor = 1;
                                    if (itemsArray.length > 0) {
                                        nomor = itemsArray.length + 1;
                                    }

                                    // jika produk belum dimasukkan, inputkan baru
                                    const newItems = {
                                        'nomor': nomor,
                                        'id': id,
                                        'produk': produk,
                                        'harga': harga,
                                        'qty': qty,
                                        'subtotal': subtotal
                                    }

                                    itemsArray.push(newItems);
                                }

                                UpdateTableItem();

                                $('#member_item_cari').val('').focus();
                                $('#member_item_qty').val(1);

                            }

                        } catch (error) {
                            console.log(error);
                        }
                    }

                    getData();
                }

            }
        });

        // =========== update produk qty items
        $(document).on('change', '.updateQty', function(e) {
            e.preventDefault();

            const id = $(this).data('uuid');
            const updateQty = $(this).val();
            const prevQty = $(this).data('pre');
            let stok = 0;

            $.ajax({
                    type: 'GET',
                    url: `{{ url('/auth/produk/${id}') }}`,
                })
                .done(function(msg) {
                    stok = msg.data.stok;

                    if (stok < updateQty) {

                        $(`.updateQty[data-uuid="${id}"]`).val(prevQty);

                        Swal.fire({
                            title: 'Stok tidak mencukupi',
                            icon: 'error',
                            showCancelButton: false,
                            showConfirmButton: false,
                            timer: 3000
                        });

                    } else {
                        // cek apakah di dalam items array sudah ada produknya?
                        const produkYangAda = itemsArray.find(function(item) {
                            return item.id === id;
                        });

                        if (produkYangAda) {
                            // jika produk sudah diiinputkan, update qty dan subtotalnya
                            const newQty = Number(updateQty);
                            const newSubtotal = Number(produkYangAda.harga) * Number(newQty);

                            produkYangAda.qty = newQty;
                            produkYangAda.subtotal = newSubtotal;
                        }

                        UpdateTableItem();
                    }

                })
                .fail(function(err) {
                    Swal.fire({
                        title: err.message,
                        icon: 'error',
                        showCancelButton: false,
                        showConfirmButton: false,
                        timer: 3000
                    });
                });
        });


        // ========== Cetak Resi
        $("#modalCetakResi").on('shown.bs.modal', function() {
            $(this).find('#btnPrintResi').focus();
        });

        $("#modalCetakResi").on('hidden.bs.modal', function() {
            $('#modal_bayar_input').focus();
        });

        $('#modalCetakResiForm').submit(function(e) {
            e.preventDefault();

            console.log(itemsArray);
            itemsArray = [];

            $('#modalCetakResi').modal('hide');
            $('#modalBayar').modal('hide');
            UpdateTableItem();
        });

        // ========= Bayar
        $("#modalBayar").on('shown.bs.modal', function() {
            $(this).find('#modal_bayar_input').val('').focus();

            $('#modal_bayar_input').keyup(function() {
                const total = $('#totalAllModalValue').val();
                const bayar = $(this).val();
                const kembali = Number(formatAngkaTanpaNol(bayar)) - Number(total) < 0 ?
                    0 :
                    Number(formatAngkaTanpaNol(bayar)) - Number(total);

                $('#modalBayarKembaliValue').val(kembali);
                $('#modalBayarKembali').text(formatRupiah(kembali));

            });
        });

        $('#bayarForm').submit(function(e) {
            e.preventDefault();

            const url = $(this).attr('action');
            const bayar = $('#modal_bayar_input').val();
            const total = $('#totalAllModalValue').val();
            const kembali = $('#modalBayarKembaliValue').val();

            $.ajax({
                    type: 'POST',
                    url: url,
                    data: {
                        _token: $('input[name="_token"]').val(),
                        total: total,
                        bayar: formatAngkaTanpaNol(bayar),
                        kembali: kembali,
                        items: itemsArray,
                    },
                })
                .done(function(msg) {
                    itemsArray = [];
                    $('#modalBayar').modal('hide');
                    UpdateTableItem();

                    $('#modalCetakResi').modal('show');

                })
                .fail(function(error) {
                    console.log(error);
                })

        });
    });



    // ==================================  kumpulan function  ==========================================

    function UpdateTableItem() {
        myTable();

        // Menghitung grand total dari subtotal di dalam itemsArray
        const grandTotal = itemsArray.reduce((total, item) => total + item.subtotal, 0);
        $('.totalAll').text(formatRupiah(grandTotal));
        $('.totalAllModal').text(formatRupiah(grandTotal));
        $('#totalAllModalValue').val(grandTotal);

        if (itemsArray.length > 0) {
            $('#btn-bayar').removeAttr('disabled');
        } else {
            $('#btn-bayar').attr('disabled', 'disabled');
        }

    }

    function hapusItemById(id) {
        const index = itemsArray.findIndex(item => item.id === id);
        if (index !== -1) {
            itemsArray.splice(index, 1);
        }

        UpdateTableItem();
    };

    function formatRupiah(angka) {
        var reverse = angka.toString().split('').reverse().join('');
        var ribuan = reverse.match(/\d{1,3}/g);
        var hasil = ribuan.join('.').split('').reverse().join('');
        return 'Rp ' + hasil;
    }

    function formatAngkaTanpaNol(angka) {
        return angka.toString().replace(/[.,]/g, "");
    }

    function modalBayar() {
        $('#modalBayar').modal('show');
    }

    function modalProduk(e) {
        e.preventDefault();
        $('#modalProduk').modal('show');
        $('#cariProdukTable').val('');

        var produkTable = $('#produkTable').DataTable({
            scrollX: true,
            scrollY: false,
            processing: true,
            serverSide: true,
            searching: false,
            lengthChange: false,
            pageLength: 7,
            bDestroy: true,
            info: false,
            responsive: true,
            order: [
                [2, 'desc']
            ],
            ajax: {
                url: "{{ route('produk.ajax') }}",
                type: "POST",
                data: function(d) {
                    d._token = $("input[name=_token]").val();
                    d.status = 'y';
                    d.cari = $('#cariProdukTable').val();
                },
            },
            columns: [{
                    data: 'produk'
                },
                {
                    data: 'kategori_id'
                },
                {
                    data: 'stok',
                },
                {
                    data: 'harga',
                    render: function(data) {
                        return formatRupiah(data);
                    }
                },
            ]
        });

        $('#cariProdukTable').keyup(function() {
            produkTable.search($('#cariProdukTable').val()).draw();
        });
    }

    function modalTransaksi(e) {
        e.preventDefault();
        $('#modalTransaksi').modal('show');
        $('#cariTransaksiTable').val('');

        var transaksiTable = $('#transaksiTable').DataTable({
            scrollX: true,
            scrollY: false,
            processing: true,
            serverSide: true,
            searching: false,
            lengthChange: false,
            pageLength: 7,
            bDestroy: true,
            info: false,
            responsive: true,
            order: [
                [4, 'desc']
            ],
            ajax: {
                url: "{{ route('kasir.transaksi') }}",
                type: "POST",
                data: function(d) {
                    d._token = $("input[name=_token]").val();
                    d.cari = $('#cariTransaksiTable').val();
                },
            },
            columns: [{
                    data: 'uuid'
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
                    data: 'created_at'
                }
            ]
        });

        $('#cariTransaksiTable').keyup(function() {
            transaksiTable.search($('#cariTransaksiTable').val()).draw();
        });
    }

    $(document).keydown(function(event) {
        if (event.which === 112) { // F1
            alert('F1 Bantuan');
        }

        if (event.which === 113) { // F2
            alert('F2 Cari Customer');
        }

        if (event.which === 114) { // F3
            modalProduk(event);
        }

        if (event.which === 115) { // F4
            modalTransaksi(event);
        }

        // jika tidak ada item disabled shortcut bayar
        if (itemsArray.length > 0) {
            if (event.which === 33) { // PageUp
                modalBayar();
            }
        }

    });
</script>
