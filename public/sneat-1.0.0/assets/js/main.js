/**
 * Main
 */

'use strict';

let menu, animate;

(function () {
    // Initialize menu
    //-----------------

    let layoutMenuEl = document.querySelectorAll('#layout-menu');
    layoutMenuEl.forEach(function (element) {
        menu = new Menu(element, {
            orientation: 'vertical',
            closeChildren: false
        });
        // Change parameter to true if you want scroll animation
        window.Helpers.scrollToActive((animate = false));
        window.Helpers.mainMenu = menu;
    });

    // Initialize menu togglers and bind click on each
    let menuToggler = document.querySelectorAll('.layout-menu-toggle');
    menuToggler.forEach(item => {
        item.addEventListener('click', event => {
            event.preventDefault();
            window.Helpers.toggleCollapsed();
        });
    });

    // Display menu toggle (layout-menu-toggle) on hover with delay
    let delay = function (elem, callback) {
        let timeout = null;
        elem.onmouseenter = function () {
            // Set timeout to be a timer which will invoke callback after 300ms (not for small screen)
            if (!Helpers.isSmallScreen()) {
                timeout = setTimeout(callback, 300);
            } else {
                timeout = setTimeout(callback, 0);
            }
        };

        elem.onmouseleave = function () {
            // Clear any timers set to timeout
            document.querySelector('.layout-menu-toggle').classList.remove('d-block');
            clearTimeout(timeout);
        };
    };
    if (document.getElementById('layout-menu')) {
        delay(document.getElementById('layout-menu'), function () {
            // not for small screen
            if (!Helpers.isSmallScreen()) {
                document.querySelector('.layout-menu-toggle').classList.add('d-block');
            }
        });
    }

    // Display in main menu when menu scrolls
    let menuInnerContainer = document.getElementsByClassName('menu-inner'),
        menuInnerShadow = document.getElementsByClassName('menu-inner-shadow')[0];
    if (menuInnerContainer.length > 0 && menuInnerShadow) {
        menuInnerContainer[0].addEventListener('ps-scroll-y', function () {
            if (this.querySelector('.ps__thumb-y').offsetTop) {
                menuInnerShadow.style.display = 'block';
            } else {
                menuInnerShadow.style.display = 'none';
            }
        });
    }

    // Init helpers & misc
    // --------------------

    // Init BS Tooltip
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Accordion active class
    const accordionActiveFunction = function (e) {
        if (e.type == 'show.bs.collapse' || e.type == 'show.bs.collapse') {
            e.target.closest('.accordion-item').classList.add('active');
        } else {
            e.target.closest('.accordion-item').classList.remove('active');
        }
    };

    const accordionTriggerList = [].slice.call(document.querySelectorAll('.accordion'));
    const accordionList = accordionTriggerList.map(function (accordionTriggerEl) {
        accordionTriggerEl.addEventListener('show.bs.collapse', accordionActiveFunction);
        accordionTriggerEl.addEventListener('hide.bs.collapse', accordionActiveFunction);
    });

    // Auto update layout based on screen size
    window.Helpers.setAutoUpdate(true);

    // Toggle Password Visibility
    window.Helpers.initPasswordToggle();

    // Speech To Text
    window.Helpers.initSpeechToText();

    // Manage menu expanded/collapsed with templateCustomizer & local storage
    //------------------------------------------------------------------

    // If current layout is horizontal OR current window screen is small (overlay menu) than return from here
    if (window.Helpers.isSmallScreen()) {
        return;
    }

    // If current layout is vertical and current window screen is > small

    // Auto update menu collapsed/expanded based on the themeConfig
    window.Helpers.setCollapsed(true, false);
})();


// ========================= CUSTOM =================================
$(document).ready(function () {
    var token = $('input[name="_token"]').val();

    $('.select2').select2();

    // datatables checkbox
    $("#checkAll").click(function () {
        $('.dt-checkboxes').not(this).prop('checked', this.checked);
    });

    // ================================= ROLE SELECT
    $('.role-select').each(function () {
        let url = $(this).data('url');

        $('.role-select').select2({
            placeholder: " - Role -",
            allowClear: true,
            ajax: {
                url: url,
                type: 'POST',
                dataType: 'json',
                data: function (params) {
                    return {
                        term: params.term,
                        _token: token,
                    }
                },
                processResults: function (data) {
                    const status = data.status;

                    if (status == true) {
                        const record = data.data;

                        return {
                            results: $.map(record, function (item) {
                                return {
                                    id: item.id,
                                    text: item.label,
                                }
                            }),
                        };
                    }
                }
            }
        });
    });

    // ============================= SELECT REGION
    // --- biasa
    $('.provinsi-select').select2({
        placeholder: " - Provinsi -",
        allowClear: true,
        ajax: {
            type: 'POST',
            dataType: 'json',
            data: function (params) {
                return {
                    term: params.term,
                    _token: token,
                }
            },
            processResults: function (data) {
                const status = data.status;

                if (status == true) {
                    const record = data.data;

                    return {
                        results: $.map(record, function (item) {
                            return {
                                id: item.id,
                                text: item.label,
                            }
                        }),
                    };
                }
            }
        }
    });

    // --- biasa
    $('.kota-select').select2({
        placeholder: " - Kota -",
        allowClear: true,
        ajax: {
            type: 'POST',
            dataType: 'json',
            data: function (params) {
                return {
                    term: params.term,
                    provinsi: $('.provinsi-select').val(),
                    _token: token,
                }
            },
            processResults: function (data) {
                const status = data.status;

                if (status == true) {
                    const record = data.data;

                    return {
                        results: $.map(record, function (item) {
                            return {
                                id: item.id,
                                text: item.label,
                            }
                        }),
                    };
                }
            }
        }
    });

    // --- biasa
    $('.kecamatan-select').select2({
        placeholder: " - Kecamatan -",
        allowClear: true,
        ajax: {
            type: 'POST',
            dataType: 'json',
            data: function (params) {
                return {
                    term: params.term,
                    kota: $('.kota-select').val(),
                    _token: token,
                }
            },
            processResults: function (data) {
                const status = data.status;

                if (status == true) {
                    const record = data.data;

                    return {
                        results: $.map(record, function (item) {
                            return {
                                id: item.id,
                                text: item.label,
                            }
                        }),
                    };
                }
            }
        }
    });

    // --- biasa
    $('.kategori-select').select2({
        placeholder: " - Kategori -",
        allowClear: true,
        ajax: {
            type: 'POST',
            dataType: 'json',
            data: function (params) {
                return {
                    term: params.term,
                    _token: token,
                }
            },
            processResults: function (data) {
                const status = data.status;

                if (status == true) {
                    const record = data.data;

                    return {
                        results: $.map(record, function (item) {
                            return {
                                id: item.id,
                                text: item.label,
                            }
                        }),
                    };
                }
            }
        }
    });

    // --- biasa
    $('.unit-select').select2({
        placeholder: " - Unit -",
        allowClear: true,
        ajax: {
            type: 'POST',
            dataType: 'json',
            data: function (params) {
                return {
                    term: params.term,
                    _token: token,
                }
            },
            processResults: function (data) {
                const status = data.status;

                if (status == true) {
                    const record = data.data;

                    return {
                        results: $.map(record, function (item) {
                            return {
                                id: item.id,
                                text: item.label,
                            }
                        }),
                    };
                }
            }
        }
    });

    // --- biasa
    $('.produk-select').select2({
        placeholder: " - Produk -",
        allowClear: true,
        ajax: {
            type: 'POST',
            dataType: 'json',
            data: function (params) {
                return {
                    term: params.term,
                    _token: token,
                }
            },
            processResults: function (data) {
                const status = data.status;

                if (status == true) {
                    const record = data.data;

                    return {
                        results: $.map(record, function (item) {
                            return {
                                id: item.id,
                                text: item.label,
                            }
                        }),
                    };
                }
            }
        }
    });

    // --- biasa
    $('.suplier-select').select2({
        placeholder: " - Suplier -",
        allowClear: true,
        ajax: {
            type: 'POST',
            dataType: 'json',
            data: function (params) {
                return {
                    term: params.term,
                    _token: token,
                }
            },
            processResults: function (data) {
                const status = data.status;

                if (status == true) {
                    const record = data.data;

                    return {
                        results: $.map(record, function (item) {
                            return {
                                id: item.id,
                                text: item.label,
                            }
                        }),
                    };
                }
            }
        }
    });

    // export
    $('.btn-export').click(function (e) {
        e.preventDefault();
        let url = $(this).attr('href');
        let ext = $(this).data('ext');
        let filename = $(this).data('filename');

        const getText = $(this).html();

        $.ajax({
            type: 'POST',
            xhrFields: {
                responseType: 'blob',
            },
            cache: false,
            url: url,
            data: {
                _token: $('input[name="_token"]').val(),
                ext: ext,
                export: 'export',
                cari: $('#cari').val(),
                tanggal: $('#tanggal').val(),
                kategori: $('#kategori').val(),
                tipe: $('#tipe').val(),
                status: $('.btn-check:checked').val(),
            },
            beforeSend: function () {
                $('.btn-export').attr('disabled', 'disabled').html('Loading...');
            }
        })
            .done(function (result, status, xhr) {
                // The actual download
                var blob = new Blob([result], {
                    type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                });
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = filename;

                document.body.appendChild(link);

                link.click();
                document.body.removeChild(link);

                $('.btn-export').removeAttr('disabled').html(getText);
            })
            .fail(function (err) {
                $('.btn-export').removeAttr('disabled').html(getText);
                console.log(err);
            });
        return false;
    });

    $('#btn-logout').click(function (e) {
        e.preventDefault();
        let url = $(this).attr('href');

        Swal.fire({
            title: 'Anda yakin ingin Keluar?',
            // text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#1A237E',
            cancelButtonColor: '#B71C1C',
            confirmButtonText: 'Ya, Keluar!',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    });

    $('.table').on('click', '.btn-hapus', function (e) {
        e.preventDefault();
        let url = $(this).attr('href');
        let title = $(this).data('title');
        let status = $(this).data('status');

        let textMessage = "Data yang akan dihapus : " + title;
        let btnText = 'Ya, Hapus!';

        if (status == false || status === 'n') {
            textMessage = "Data yang diaktifkan kembali : " + title;
            btnText = 'Aktifkan Kembali!';
        }

        Swal.fire({
            title: 'Anda yakin?',
            text: textMessage,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#1A237E',
            cancelButtonColor: '#B71C1C',
            confirmButtonText: btnText,
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'DELETE',
                    url: url,
                    data: {
                        _token: $('input[name="_token"]').val(),
                    },
                })
                    .done(function () {
                        datatables.ajax.reload(null, false);
                    })
                    .fail(function (err) {
                        const data = err.responseJSON;

                        Swal.fire({
                            title: 'Terjadi Kesalahan',
                            html: data.pesan,
                            icon: 'error',
                            showCancelButton: false,
                            showConfirmButton: false,
                            timer: 3000,
                        })
                    });
            }
        });
    });


    $('.table').on('click', '.btn-detail', function (e) {
        e.preventDefault();

        const url = $(this).attr('href');
        const title = $(this).data('title');

        const getData = async () => {
            try {
                const req = await fetch(url);
                const res = await req.json();
                const data = res.data;

                $('#modalDetailTable').modal('show');
                $('#modalDetailTableLabel').text(title);

                let dataBody = '';
                for (const [key, value] of Object.entries(data)) {
                    dataBody += `<div class="row mb-2">
                                    <label class="col-sm-3 col-form-label" for="nama">${key}</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" disabled value="${value}">
                                    </div>
                                </div>`;
                }



                $('#modalDetailTableBody').html(dataBody);


            } catch (error) {
                console.log(error);
            }

        }

        getData();

    });

});


function potongTeks(teks, jumlahKata) {
    // Pisahkan teks menjadi array kata-kata
    if (teks !== null) {

        let kata = teks.split(' ');

        // Periksa apakah jumlah kata dalam teks lebih besar dari jumlah yang diinginkan
        if (kata.length > jumlahKata) {
            // Potong array kata-kata hingga jumlah kata yang diinginkan
            kata = kata.slice(0, jumlahKata);

            // Gabungkan kembali array kata-kata menjadi teks
            teks = kata.join(' ') + '...'; // Tambahkan elipsis sebagai penanda bahwa teks dipotong
        }

        return teks;
    } else {
        return '';
    }
}
