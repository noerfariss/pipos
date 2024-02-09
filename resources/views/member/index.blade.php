@extends('member.layouts.layout')

@section('konten')
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
            <h5 class="card-title text-primary mb-3">Halooo <b>{{ Auth::user()->nama }}</b> 🎉</h5>
            <div class="row">
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="tanggal">
                </div>
                <div class="col-sm-2">
                    <button class="btn btn-sm btn-warning text-dark" type="button" onclick="getGrafik()">GO</button>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-2 mb-4">
                <div class="card mb-3">
                    <div class="px-3 py-1">
                        <h6 class="col-form-label">transactions</h6>
                        <h2 class="fw-bold" id="ts-total">0</h2>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="px-3 py-1">
                        <h6 class="col-form-label">items</h6>
                        <h2 class="fw-bold" id="ts-items">0</h2>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="px-3 py-1">
                        <h6 class="col-form-label">Categories</h6>
                        <h2 class="fw-bold" id="ts-categories">0</h2>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="px-3 py-1">
                        <h6 class="col-form-label">members</h6>
                        <h2 class="fw-bold" id="ts-members">0</h2>
                    </div>
                </div>
            </div>
            <div class="col-sm-7 mb-4">
                <div class="card">
                    <h5 class="card-header col-form-label">Daily Transaction</h5>
                    <section id="boxDailyTransaction"></section>
                </div>
            </div>
            <div class="col-sm-3 mb-4">
                <div class="card">
                    <h5 class="card-header col-form-label">Member Transaction</h5>
                    <section id="boxMemberTransaction"></section>
                </div>
            </div>
        </div>

        <div class="row">



        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.7.0"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-annotation/0.5.7/chartjs-plugin-annotation.js">
    </script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
        $('#tanggal').flatpickr({
            mode: 'range',
            defaultDate: ["{{ date('Y-m-01') }}", "{{ date('Y-m-d') }}"],
            maxDate: '{{ date('Y-m-d') }}',
        });
    </script>

    <script>
        let token = $('input[name="_token"]').val();

        getGrafik();

        function getGrafik() {
            // --- init
            let tanggal = $('#tanggal').val();

            getSummaryTransaction(tanggal);
            getDailyTransaction(tanggal);
            getMemberTransaction(tanggal);
        }

        function getSummaryTransaction(tanggal) {
            $.ajax({
                    type: 'POST',
                    url: '{{ route('chart.summary.transaction') }}',
                    data: {
                        _token: token,
                        tanggal: tanggal
                    }
                })
                .done(function(msg) {
                    const data = msg.data;

                    $('#ts-total').text(data.total);
                    $('#ts-items').text(data.items);
                    $('#ts-categories').text(data.categories);
                    $('#ts-members').text(data.members);
                })
                .fail(function(err) {
                    console.log(err);
                });
        }

        function getDailyTransaction(tanggal) {
            $.ajax({
                    type: 'POST',
                    url: '{{ route('chart.daily.transaction') }}',
                    data: {
                        _token: token,
                        tanggal: tanggal
                    }
                })
                .done(function(msg) {
                    const boxDailyTransaction = '#boxDailyTransaction';
                    const chartDailyTransaction = '#chartDailyTransaction';

                    $(boxDailyTransaction).html(
                        `<canvas id="${chartDailyTransaction}" width="100%"></canvas>`);

                    const data = msg.data;
                    let tanggal = [];
                    let total = [];

                    for (const row of data) {
                        tanggal.push(row.tanggal);
                        total.push(row.total);
                    }

                    let dataValue = [tanggal, total];
                    chartLINE('Daily Transaction', chartDailyTransaction, 46, dataValue, false);
                })
                .fail(function(err) {
                    console.log(err);
                });
        }

        function getMemberTransaction(tanggal) {
            $.ajax({
                    type: 'POST',
                    url: '{{ route('chart.member.transaction') }}',
                    data: {
                        _token: token,
                        tanggal: tanggal
                    }
                })
                .done(function(msg) {
                    const boxMemberTransaction = '#boxMemberTransaction';
                    const chartMemberTransaction = '#chartMemberTransaction';

                    $(boxMemberTransaction).html(
                        `<canvas id="${chartMemberTransaction}" width="100%"></canvas>`);

                    const data = msg.data;

                    let dataValue = [{
                        label: 'Total',
                        data: [data.total],
                        backgroundColor: '#0e0e55',
                        stack: 'stack 1'
                    }, {

                        label: 'Member',
                        data: [data.member],
                        backgroundColor: '#F1BD00',
                        stack: 'stack 2'
                    }, {
                        label: 'Umum',
                        data: [data.umum],
                        backgroundColor: '#B0BEC5',
                        stack: 'stack 3'
                    }, ];

                    chartBar(['Member Transaction'], chartMemberTransaction, 111, dataValue, true);
                })
                .fail(function(err) {
                    console.log(err);
                });
        }

        function chartLINE(label, boxDiv, chartHeight, dataValue, setLegend) {
            var ctx = document.getElementById(boxDiv);
            ctx.height = chartHeight;

            var myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: dataValue[0],
                    datasets: [{
                        label: label,
                        data: dataValue[1],
                        borderColor: '#0e0e55 ',
                        pointBorderColor: 'red',
                        tension: 0,
                    }]
                },
                options: {
                    plugins: {
                        datalabels: {
                            display: true,
                            color: '#000000',
                            anchor: 'end',
                            align: 'end',
                            offset: 3,
                        },
                    },
                    legend: {
                        display: setLegend
                    },
                    scales: {
                        xAxes: [{
                            display: true,
                            stacked: true,
                            ticks: {
                                autoSkip: false,
                            },
                        }],
                        yAxes: [{
                            display: true,
                            stacked: true,
                            ticks: {
                                beginAtZero: true,
                                fontSize: 12,
                            },
                            afterDataLimits(scale) {
                                scale.max += 2;
                            }
                        }],
                    },
                }
            });
        }

        function chartBar(label, boxDiv, chartHeight, dataValue, setLegend) {
            var ctx = document.getElementById(boxDiv);
            ctx.height = chartHeight;

            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: label,
                    datasets: dataValue,
                },
                options: {
                    plugins: {
                        datalabels: {
                            display: true,
                            color: '#000000',
                            anchor: 'end',
                            align: 'top',
                            // formatter: function(value, context) {
                            //     return value + '%';
                            // }
                        },
                    },
                    legend: {
                        display: setLegend
                    },
                    scales: {
                        xAxes: [{
                            display: true,
                            stacked: true,
                            ticks: {
                                autoSkip: false,
                            },
                        }],
                        yAxes: [{
                            display: true,
                            stacked: true,
                            ticks: {
                                beginAtZero: true,
                                fontSize: 11,
                            },
                            afterDataLimits(scale) {
                                scale.max += 1;
                            }
                        }],
                    },
                }
            });
        }
    </script>
@endsection
