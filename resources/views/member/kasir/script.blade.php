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

    const getProdukInputToCart = async (item, qty) => {
        const baseUrl = '{{ url('/') }}';

        try {
            const req = await fetch(`${baseUrl}/auth/produk/cariproduk`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json' // Anda dapat mengubah tipe konten sesuai kebutuhan
                },
                body: JSON.stringify({
                    _token: $('input[name="_token"]').val(),
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
                const kategori = data.kategori;
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
                        'kategori': kategori,
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

    const getMemberDetail = async (memberCode) => {
        const baseUrl = '{{ url('/') }}';

        try {
            const req = await fetch(`${baseUrl}/auth/member/customer`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json' // Anda dapat mengubah tipe konten sesuai kebutuhan
                },
                body: JSON.stringify({
                    _token: $('input[name="_token"]').val(),
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

    $(document).ready(function() {
        // =---- Init
        $('.member[value="0"]').prop('checked', true);
        const memberCheck = $('.member:checked').val();
        $('#member_cari').attr('disabled', 'disabled').val('');
        $('#member_detail').hide();
        $('#member_item_qty').val(1);
        $('#member_item_cari').val('');
        $('#member_detail').hide();
        $('#member_uuid').val('');
        $('#member_nama').text('');
        $('#member_phone').text('');
        $('#member_alamat').text('');

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
                $('#member_uuid').val('');
                $('#member_nama').text('');
                $('#member_phone').text('');
                $('#member_alamat').text('');
            }
        });


        // ------------ CARI MEMBER JIKA ADA
        $('#member_cari_form').submit(function(e) {
            e.preventDefault();
            const memberCode = $('#member_cari').val();

            if (memberCode !== '' && memberCode !== null) {
                getMemberDetail(memberCode);
            }

        });

        // ----------- INPUT PRODUK / ITEM
        $('#member_item_cari_form input').keypress(function(e) {
            if (e.which === 13) {
                e.preventDefault();

                const item = $('#member_item_cari').val();
                const qty = $('#member_item_qty').val();

                if (item !== '' && item !== null) {
                    getProdukInputToCart(item, qty);
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
                        member: $('#member_uuid').val(),
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

            // reset member detail
            $('#member_detail').hide();
            $('#member_uuid').val('');
            $('#member_nama').text('');
            $('#member_phone').text('');
            $('#member_alamat').text('');

            // reset umum/member button
            $('#member_cari').val('').attr('disabled', 'disabled');
            $('#member_umum').prop('checked', true);
            $('#member_member').prop('checked', false);
            $('.btn-check2:checked').css('background-color', '#0e0e55');
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

    function getCurrentDate() {
        const currentDate = new Date();
        const currentYear = currentDate.getFullYear();
        const currentMonth = ("0" + (currentDate.getMonth() + 1)).slice(-2);
        const currentDay = ("0" + currentDate.getDate()).slice(-2);

        return `${currentYear}-${currentMonth}-${currentDay}`;
    }
    // ============================ BANTUAN ===============================
    function modalHelp() {
        $('#modalHelp').modal('show');
    }

    // ======================= MODAL MEMBER ========================
    function modalMember(e) {
        e.preventDefault();
        $('#modalMember').modal('show');
        $('#cariMemberTable').val('');

        var memberTable = $('#memberTable').DataTable({
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
            // order: [
            //     [2, 'desc']
            // ],
            ajax: {
                url: "{{ route('member.ajax') }}",
                type: "POST",
                data: function(d) {
                    d._token = $("input[name=_token]").val();
                    d.status = 'y';
                    d.cari = $('#cariMemberTable').val();
                },
            },
            columns: [{
                    data: 'member'
                },
                {
                    data: 'phone'
                },
                {
                    data: 'email',
                },
                {
                    data: 'jenis_kelamin',
                },
                {
                    data: 'phone',
                    render: function(data) {
                        return `<button type="button" class="btn btn-xs btn-outline-primary" onclick="tambahMembertoKasir('${data}')">tambah</button>`;
                    }
                }
            ]
        });

        $('#cariMemberTable').keyup(function() {
            memberTable.search($('#cariMemberTable').val()).draw();
        });
    }

    function tambahMembertoKasir(memberCode) {
        $('#member_cari').val(memberCode).removeAttr('disabled');
        $('#member_umum').removeAttr('checked');
        $('#member_member').prop('checked', true);

        $('.btn-check2:checked').css('background-color', '#0e0e55');

        $('#modalMember').modal('hide');
        getMemberDetail(memberCode);
    }

    // ============================ BAYAR ===============================
    function modalBayar() {
        $('#modalBayar').modal('show');
    }

    // ======================= MODAL PRODUK ========================
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
                {
                    data: 'barcode',
                    render: function(data) {
                        return `<button type="button" class="btn btn-xs btn-outline-primary" onclick="tambahItemFromProdukModal(${data})">tambah</button>`;
                    }
                }
            ]
        });

        $('#cariProdukTable').keyup(function() {
            produkTable.search($('#cariProdukTable').val()).draw();
        });
    }

    function tambahItemFromProdukModal(barcode) {
        $('#member_item_cari').val(barcode);
        const item = $('#member_item_cari').val();
        const qty = $('#member_item_qty').val();

        getProdukInputToCart(item, qty);
    }


    // ====== TRANSAKSI =======================
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
            pageLength: 10,
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
                    d.tanggal = getCurrentDate();
                },
            },
            columns: [{
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
            modalHelp();
        }

        if (event.which === 113) { // F2
            modalMember(event);
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

{{-- ---------------- full screen ---------------- --}}
<script>
    function toggleFullScreen() {
        if (!document.fullscreenElement && // alternative standard method
            !document.mozFullScreenElement && !document.webkitFullscreenElement && !document.msFullscreenElement
        ) { // current working methods
            if (document.documentElement.requestFullscreen) {
                document.documentElement.requestFullscreen();
            } else if (document.documentElement.msRequestFullscreen) {
                document.documentElement.msRequestFullscreen();
            } else if (document.documentElement.mozRequestFullScreen) {
                document.documentElement.mozRequestFullScreen();
            } else if (document.documentElement.webkitRequestFullscreen) {
                document.documentElement.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
            }
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.msExitFullscreen) {
                document.msExitFullscreen();
            } else if (document.mozCancelFullScreen) {
                document.mozCancelFullScreen();
            } else if (document.webkitExitFullscreen) {
                document.webkitExitFullscreen();
            }
        }
    }
</script>
