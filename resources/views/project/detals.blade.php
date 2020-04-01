@extends('layouts.app')

@section('content')

    <h1>О проекте <small class="text-muted">({{ $company->name }})</small></h1>
    <hr>

    <ul class="nav nav-pills nav-tabs mb-3" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link text-uppercase active" id="incomes-tab" data-toggle="tab" href="#incomes" role="tab"
               aria-controls="incomes" aria-selected="false">Доходы</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-uppercase" id="employees-tab" data-toggle="tab" href="#costs" role="tab"
               aria-controls="employees" aria-selected="false">Расходы</a>
        </li>
    </ul>
    <div class="tab-content pl-0 pr-0 pt-0" id="myTabContent">
        <div class="tab-pane fade active show" id="incomes" role="tabpanel" aria-labelledby="incomes-tab">
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                    <tr>
                        <th width="5%" class="align-middle white-space-normal">Дата и номер счета</th>
                        <th width="10%" class="align-middle white-space-normal">Клиент</th>
                        <th width="10%" class="align-middle white-space-normal">Комментарий</th>
                        <th width="10%" class="align-middle white-space-normal">Сумма</th>
                        <th width="10%" class="align-middle white-space-normal">Дата оплаты</th>
                        <th width="10%" class="align-middle white-space-normal">Получатель</th>
                        <th width="10%" class="align-middle white-space-normal">Услуга</th>
                        <th width="10%" class="align-middle white-space-normal">Действия</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php $iterator = 0; @endphp
                    @foreach($project->incomes as $income)

                        <tr>
                            <td class="align-middle">
                                <a class="income_modal"
                                   route="{{ route('act.getIncome') }}"
                                   token="{{ csrf_token() }}"
                                   href=""
                                   data-toggle="modal"
                                   data-target="#act">
                                    {{ $income->account_number }}
                                </a>
                                <br>
                                <small class="text-muted">
                                    {{ $income->date->format('d-m-Y') }}
                                </small>
                            </td>
                            <td class="align-middle">
                                <a href="{{route('client.show', ['client' => $income->normalClient])}}">@if(!empty($income->normalClient->name)){{ $income->normalClient->name }}</a>@endif
                            </td>
                            <td class="align-middle">
                                {{ $income->comment }}
                            </td>
                            <td class="align-middle" style="white-space: nowrap;">
                                +{{ number_format($income->sum, 2, '.', ' ') }} ₽
                            </td>
                            <td class="align-middle">
                                @if(!empty($income->income_date))
                                    {{ $income->income_date->format('d-m-Y') }}
                                @endif</td>
                            <td class="align-middle">
                                @if(!empty($income->payService))
                                    {{ $income->payService->name }}
                                @endif
                            </td>
                            <td class="align-middle">
                                @if(!empty($income->services))
                                    {{ $income->services->name}}
                                @endif
                            </td>
                            <td class="d-flex d-row align-items-center white-space-normal h-100">
                                @if ($income->is_payed)
                                    <a class="btn btn-icon btn-glow-success btn-success mr-2"
                                       href="{{ route('income.set-unpayed', $income) }}"
                                       onclick='return confirm("Оплата будет считаться не оплаченным, продолжить?")'>
                                        <i class="feather icon-check-circle"></i>
                                    </a>
                                @else
                                    <a class="btn btn-icon btn-glow-danger btn-outline-danger mr-2 set-pay-income"
                                       href="{{ route('income.set-payed', $income) }}"
                                       data-toggle="modal"
                                       data-target="#myModal"
                                       income="{{$income->id}}">
                                        <i class="feather icon-slash"></i>
                                    </a>
                                @endif
                                    <form action="{{ route('income.print.exit', $income) }}"
                                          method="POST"
                                          enctype="multipart/form-data"
                                          target="_blank">

                                        <input type="hidden" value="{{ $income->client_cheking_accounts }}"
                                               name="client_account"/>

                                        <input type="hidden" value="{{ $income->pay_service }}"
                                               name="pay_service"/>

                                        <button class="btn btn-icon btn-glow-info btn-info"
                                                type="submit">
                                            <i class="feather icon-download"></i>
                                        </button>
                                    </form>
                                    <button class="btn btn-icon btn-glow-warning btn-warning"
                                            onclick="location.href = '{{ route('income.edit', ['token' => $company->token, 'id' => $income->id]) }}'">
                                        <i class="feather icon-edit"></i>
                                    </button>
                                    <form
                                        action="{{ route('income.remove', ['token' => $company->token, 'id' => $income->id]) }}"
                                        method="POST"
                                        id="delete-{{ $income->id }}">
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

        <div class="tab-pane fade" id="costs" role="tabpanel" aria-labelledby="costs-tab">
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                    <tr>
                        <th scope="col" width="5%" class="align-middle white-space-normal">Дата</th>
                        <th scope="col" width="10%" class="align-middle white-space-normal">Категория</th>
                        <th scope="col" width="10%" class="align-middle white-space-normal">Подкатегория</th>
                        <th scope="col" width="10%" class="align-middle white-space-normal">Сумма</th>
                        <th scope="col" width="20%" class="align-middle white-space-normal">Описание</th>
                        <th scope="col" width="20%" class="align-middle white-space-normal">Получатель</th>
                        <th scope="col" width="15%" class="align-middle white-space-normal">Действия</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php $iterator = 0; @endphp
                    @foreach($project->expenses as $expense)
                        <tr>
                            <td class="align-middle">
                                {{ $expense->expense_date ? $expense->expense_date->format('d-m-Y') : $expense->updated_at->format('d-m-Y') }}
                            </td>
                            <td class="align-middle">
                                {{ $expense->relateCategory->parent->name }}
                            </td>
                            <td class="align-middle">
                                {{ $expense->relateCategory->name }}
                            </td>
                            <td class="align-middle">
                                -{{ number_format($expense->sum, 0, ' ', ' ') }} ₽
                            </td>
                            <td class="align-middle">
                                {{ $expense->comment }}
                            </td>
                            <td class="align-middle">
                                {{ $expense->user }}
                            </td>
                            <td class="d-flex d-row align-items-center white-space-normal h-100">
                                <button class="btn btn-icon btn-glow-warning btn-warning"
                                        onclick="location.href = '{{ route('expense.edit', ['token' => $company->token, 'id' => $expense->id]) }}'">
                                    <i class="feather icon-edit"></i>
                                </button>
                                <form
                                    action="{{ route('expense.remove', ['token' => $company->token, 'id' => $expense->id]) }}"
                                    method="POST">
                                    @csrf
                                    <button class="btn btn-icon btn-glow-danger btn-danger"
                                            type="submit"
                                            onclick='return confirm("Вы уверены, то хотите удалить расход?")'>
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
    </div>
@endsection
