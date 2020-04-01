@extends('layouts.app')

@section('content')

    <h1>Аналитика</h1>
    <hr>

    @include('layouts.filtrs')

    <ul class="nav nav-pills nav-tabs">
        @can('admin')
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#analytics">Аналитика</a>
            </li>
        @endcan
    </ul>

    <div class="tab-content">
        <div class="tab-pane active" id="analytics">
            <div class="row pt-5">
                <div class="card col-sm-5 mr-auto ml-auto">
                    <div class="card-body">
                        <div class="card-title">
                            <h3>Прибыль за период</h3>
                        </div>
                        <canvas id="firstChart" width="100%" height="60"></canvas>
                    </div>
                </div>

                <div class="card col-sm-6 mr-auto ml-auto">
                    <div class="card-body">
                        <div class="card-title text-center">
                            <h3>Самый доходный клиент</h3>
                        </div>
                        <canvas id="secondChart" width="100%" height="60"></canvas>
                    </div>
                </div>
            </div>

            <br>

            <div class="row">
                <div class="card col-sm-6 mr-auto ml-auto">
                    <div class="card-body">
                        <div class="card-title">
                            <h3>Средняя сумма сделки</h3>
                        </div>
                        <canvas id="fourChart" width="100%" height="60"></canvas>
                    </div>
                </div>

                <div class="card col-sm-5 mr-auto ml-auto">
                    <div class="card-body">
                        <div class="card-title text-center">
                            <h3>Количество сделок</h3>
                        </div>
                        <canvas id="thirdChart" width="100%" height="60"></canvas>
                    </div>
                </div>
            </div>

            <br>

            <div class="row">
                <div class="card col-sm-5 mr-auto ml-auto">
                    <div class="card-body">
                        <div class="card-title">
                            <h3>Выручка за период</h3>
                        </div>
                        <canvas id="sixChart" width="100%" height="60"></canvas>
                    </div>
                </div>

                <div class="card col-sm-6 mr-auto ml-auto">
                    <div class="card-body">
                        <div class="card-title">
                            <h3>Источники оплат</h3>
                        </div>
                        <canvas id="fiveChart" width="100%" height="60"></canvas>
                    </div>
                </div>
            </div>
    </div>
        <script>

            var ctx = document.getElementById("firstChart");
            var ctx2 = document.getElementById("secondChart");
            var ctx3 = document.getElementById("thirdChart");
            var ctx4 = document.getElementById("fourChart");
            var ctx5 = document.getElementById("fiveChart");
            var ctx6 = document.getElementById("sixChart");
            var data = {!! $firstChartData !!};
            var data2 = {!! $secondChartData !!};
            var data3 = {!! $thirdChartData !!};
            var data4 = {!! $fourChartData !!};
            var data5 = {!! $fiveChartData !!};
            var data6 = {!! $sixChartData !!};

            setTimeout(function () {
                var myChart = new Chart(ctx, {
                    type: 'line',
                    data: data,
                    options: {}
                });

                var myChart = new Chart(ctx2, {
                    type: 'doughnut',
                    data: data2,
                    options: {}
                });

                var myChart = new Chart(ctx3, {
                    type: 'line',
                    data: data3,
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true,
                                    callback: function (value) { if (Number.isInteger(value)) { return value; } },
                                    stepSize: 1
                                }
                            }]
                        }
                    }
                });

                var myChart = new Chart(ctx4, {
                    type: 'doughnut',
                    data: data4,
                    options: {}
                });

                var myChart = new Chart(ctx5, {
                    type: 'doughnut',
                    data: data5,
                    options: {}
                });

                var myChart = new Chart(ctx6, {
                    type: 'line',
                    data: data6,
                    options: {}
                });
            }, 1000);

        </script>
    </div>

    @endsection
