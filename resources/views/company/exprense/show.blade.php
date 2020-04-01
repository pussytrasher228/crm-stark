@extends('layouts.app')

@section('content')

    <h1>Расходы Дани</h1>
    <hr>

    @include('layouts.filtrs')

    <ul class="nav nav-pills nav-tabs mb-3">
        @can('admin')
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#expenses">Расходы</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#total">Структура расходов</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#newtable">Новая таблица</a>
            </li>
        @endcan
    </ul>
    <div class="tab-content pl-0 pr-0 pt-0">
        <div class="tab-pane active" id="expenses">
            <div class="d-flex justify-content-end">
                <button class="btn btn-glow-success btn-success mb-4 mt-4 mr-4" onclick="location.href = '{{ route("expense.create", ["token" => $company->token]) }}'">
                    <i class="feather icon-plus"></i>
                    Добавить расход
                </button>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                    <tr>
                        <th class="align-middle">Дата</th>
                        <th class="align-middle">Сумма</th>
                        <th class="align-middle">Информация</th>
                        <th class="align-middle">Оплата</th>
                        <th class="align-middle">Действие</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php $iterator = 0; @endphp
                    @foreach($expenses as $expense)
                        <tr>
                            <td class="align-middle">
                                {{ $expense->expense_date ? $expense->expense_date->format('d-m-Y') : $expense->updated_at->format('d-m-Y') }}
                            </td>
                            <td class="align-middle">
                                -{{ number_format($expense->sum, 0, ' ', ' ') }}
                            </td>
                            <td class="align-middle white-space-normal">
                                <span class="font-weight-bold">{{ $expense->comment }}</span>
                                <br>
                                <small class="text-muted">
                                    {{ $expense->relateCategory->parent->name }} / {{ $expense->relateCategory->name }} (<a href="">$project->name</a>)
                                    {{-- TODO: Contoller -> relate $project and give it --}}
                                </small>
                            </td>
                            <td class="align-middle white-space-normal">
                                {{ $expense->user }}
                            </td>
                            <td class="align-middle d-flex d-row">
                                <button class="btn btn-icon btn-glow-warning btn-warning"
                                        onclick="location.href = '{{ route('expense.edit', ['token' => $company->token, 'id' => $expense->id]) }}'">
                                    <i class="feather icon-edit"></i>
                                </button>

                                <form
                                    action="{{ route('expense.remove', ['token' => $company->token, 'id' => $expense->id]) }}"
                                    method="POST"
                                    id="delete-{{ $expense->id }}">
                                    @csrf
                                    <button class="btn btn-icon btn-glow-danger btn-danger"
                                            type="submit"
                                            onclick='return confirm("Вы уверены, то хотите удалить оплату?")'>
                                        <i class="feather icon-trash-2"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @php $iterator++; @endphp
                        @if ($iterator >= 999999)
                            @break
                        @endif
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="tab-pane" id="total">

            <table class="table depth-table">
                <thead>
                <tr>
                    <th class="align-middle">Услуга</th>
                    <th class="align-middle">Количество сделок</th>
                    <th class="align-middle">Доход</th>
                </tr>
                </thead>
                <tbody>
                @foreach($incomesIn as $key => $categoryIncome)
                    @php
                        $sum = array_sum(array_map(function ($s) {
                            return $s['sum'];
                        }, $categoryIncome));
                    @endphp
                    <tr>
                        <td class="align-middle">{{ \App\Http\Controllers\Company\IncomeController::serviceExprense($key)->name }}</td>
                        <td class="align-middle">{{ count($categoryIncome) }}</td>
                        <td class="align-middle">+{{ number_format($sum, 0, ' ', ' ') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <table class="table table-striped table-hover depth-table">
                <thead>
                <tr>
                    <th class="align-middle">Месяц</th>
                    @foreach ($costs->getMonths() as $date)
                        <th class="align-middle">{{ $date }}</th>
                    @endforeach
                </tr>
                </thead>
                <tbody>

                @php $index = 1; @endphp
                <tr class="depth-table-plus" data-table-id="{{ $index }}">
                    <td class="align-middle">
                        <button class="btn btn-circle btn-icon btn-primary depth-table-toggle"><i class="fa fa-plus"></i>
                        </button>
                        Доход
                    </td>
                    @foreach ($costs->getTotalIncomes() as $totalIncome)
                        <td class="align-middle">+{{ number_format($totalIncome, 0, ' ', ' ') }}</td>
                    @endforeach
                </tr>
                @foreach ($costs->getTypeIncome() as $name => $incomes)
                    <tr class="depth-table-td" data-table-id="{{ $index + 1 }}" data-table-parent="{{ $index }}">
                        <td style="padding-left: 40px;">
                            @if(!empty( \App\Http\Controllers\Company\IncomeController::serviceExprense($name)->name))
                                {{ \App\Http\Controllers\Company\IncomeController::serviceExprense($name)->name }}
                            @endif
                        </td>
                        @foreach ($incomes as $income)
                            <td class="align-middle">+{{ number_format($income, 0, ' ', ' ') }}</td>
                        @endforeach
                    </tr>
                @endforeach

                @php $index += 2 @endphp
                <tr class="depth-table-minus" data-table-id="{{ $index }}">
                    <td class="align-middle">
                        <button class="btn btn-circle btn-icon btn-primary depth-table-toggle"><i class="fa fa-plus"></i>
                        </button>
                        Расход
                    </td>
                    @foreach ($costs->getTotalExpense() as $value)
                        <td class="align-middle">-{{ number_format($value, 0, ' ', ' ') }}</td>
                    @endforeach
                </tr>

                @php $subindex = $index + 1 @endphp
                @foreach($costs->getTypeExpense() as $type => $data)
                    <tr class="depth-table-td" data-table-id="{{ $subindex }}" data-table-parent="{{ $index }}">
                        <td style="padding-left: 40px;">
                            <button class="btn btn-circle btn-icon btn-primary depth-table-toggle"><i class="fa fa-plus"></i>
                            </button>
                            {{ $type }}
                        </td>
                        @php $formalIndex = 0; @endphp
                        @foreach ($costs->getExpensesByType($type) as $sum)
                            <td class="align-middle">-{{ number_format($sum, 0, ' ', ' ') }}</td>
                        @endforeach
                    </tr>

                    @foreach ($data as $key => $typeData)
                        <tr class="depth-table-td" data-table-id="{{ $subindex + 1 }}"
                            data-table-parent="{{ $subindex }}">
                            <td style="padding-left: 60px;">{{ $key }}</td>
                            @foreach ($typeData as $sum)
                                <td class="align-middle">-{{ number_format($sum, 0, ' ', ' ') }}</td>
                            @endforeach
                        </tr>
                    @endforeach

                    @php $subindex += 2; @endphp

                @endforeach

                <tr class="depth-table-prologue">
                    <td class="align-middle">Итого</td>
                    @foreach ($costs->getPrologue() as $prologue)
                        <td class="align-middle">{{ number_format($prologue, 0, ' ', ' ') }}</td>
                    @endforeach
                </tr>
                <tr class="depth-table-prologue">
                    <td class="align-middle">Итого нарастающим</td>
                    @foreach ($costs->getTotalPrologue() as $prologue)
                        <td class="align-middle">{{ number_format($prologue, 0, ' ', ' ') }}</td>
                    @endforeach
                </tr>
                </tbody>
            </table>

        </div>
        <div class="tab-pane" id="newtable">
            <div class="tab-pane active" id="expenses">
                <div class="d-flex justify-content-end">
                    <button class="btn btn-glow-success btn-success mb-4 mt-4 mr-4" onclick="location.href = '{{ route("expense.create", ["token" => $company->token]) }}'">
                        <i class="feather icon-plus"></i>
                        Добавить расход
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr class="active">
                            <th class="align-middle" style="border-right: 2px solid #cccccc"> </th>
                            <th class="align-middle"></th>
                            <th class="align-middle"></th>
                            <th class="align-middle"></th>
                            <th class="align-middle"></th>
                        </tr>
                        </thead>
                        <tbody class="table table-hover">
                        <tr class="active">
                            <th class="align-middle" style="border-right: 2px solid #cccccc; border-bottom: none">Конфигурация</th>
                            <th class="align-middle">Первая</th>
                            <th class="align-middle">Вторая</th>
                            <th class="align-middle">Третья</th>
                            <th class="align-middle">Четвертая </th>

                        </tr>
                        <tr>
                            <th class="align-middle" style="border-right: 2px solid #cccccc">&emsp;Третья</th>
                            <th class="numbers-on-table">тут расход</th>
                            <th class="numbers-on-table">еще один</th>
                        </tr>
                        <tr>
                            <th class="align-middle" style="border-right: 2px solid #cccccc">&emsp;&emsp;Третья</th>
                            <th class="numbers-on-table">тут расход</th>
                            <th class="numbers-on-table">еще один</th>
                        </tr>
                        <tr>
                            <th class="align-middle" style="border-right: 2px solid #cccccc">Третья</th>
                            <th class="numbers-on-table">Тут тоже расход</th>
                            <th class="numbers-on-table">тоже</th>
                            <th class="numbers-on-table">тоже расход</th>
                        </tr>

                        </tbody>
                    </table>
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
                                callback: function (value) {
                                    if (Number.isInteger(value)) {
                                        return value;
                                    }
                                },
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

@endsection
