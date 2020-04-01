@extends('layouts.app')

@section('content')

    <h1>Оплаты</h1>
    <hr>

    <div class="card mb-3">
        <div class="card-body">
            @if(empty($incomePlans) || $incomePlans->count() == 0)
                План продаж еще не задан
            @endif
            @foreach($incomePlans as $incomePlan)
                <div style="text-align: center">
                    <p style="font-size: 17px">План продаж на {{$incomePlan->mounth_name}} {{$incomePlan->year}}
                        : {{number_format($incomePlan->plan, 0, ' ', ' ')}}р.
                        Выполнено {{number_format($fullCalculateMounth['price'], 0, ' ', ' ')}}р.</p>
                    <div class="progress">
                        @if(!empty($proc))
                            <div class="progress-bar" role="progressbar"
                                 style="width: {{number_format($proc, 0, ' ', ' ')}}%;" aria-valuenow="25"
                                 aria-valuemin="0" aria-valuemax="100">{{number_format($proc, 0, ' ', ' ')}}%
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach

        </div>
    </div>
    @include('layouts.filtrs')

    <div class="card mb-3">
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-end">
                <button class="btn btn-glow-success btn-success mb-0 mt-0 mr-0"
                        onclick="location.href = '{{ route('income.create', ['token' => $company->token]) }}'">
                    <i class="feather icon-plus"></i>
                    Добавить оплату
                </button>
            </div>
        </div>
    </div>

    <ul class="nav nav-pills nav-tabs mb-3" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link text-uppercase active" id="home-tab" data-toggle="tab" href="#home" role="tab"
               aria-controls="home" aria-selected="false">
                Все счета ({{ $fullCalculate['count'] }})
                <br>
                {{ number_format($fullCalculate['price'], 0, ' ', ' ') }} Руб.
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-uppercase" id="profile-tab" data-toggle="tab" href="#profile" role="tab"
               aria-controls="profile" aria-selected="false">
                Оплачено ({{ $fullCalculate['count_payed'] }})
                <br>
                {{ number_format($fullCalculate['price_payed'], 0, ' ', ' ') }} Руб.
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-uppercase" id="contact-tab" data-toggle="tab" href="#contact" role="tab"
               aria-controls="contact" aria-selected="true">
                Не оплачено ({{ $fullCalculate['count_not_payed'] }})
                <br>
                {{ number_format($fullCalculate['price_not_payed'], 0, ' ', ' ') }} Руб.
            </a>
        </li>
    </ul>
    <div class="tab-content pl-0 pr-0 pt-0" id="myTabContent">
        <div class="tab-pane fade active show" id="home" role="tabpanel" aria-labelledby="home-tab">
            <div class="table-responsive">
                <table class="table table-hover table-striped text-left">
                    <thead>
                    <tr>
                        <th scope="col" width="5%" class="align-middle white-space-normal">Дата и номер счета</th>
                        <th scope="col" width="10%" class="align-middle white-space-normal">Клиент</th>
                        <th scope="col" width="15%" class="align-middle white-space-normal">Комментарий</th>
                        <th scope="col" width="10%" class="align-middle white-space-normal">Сумма</th>
                        <th scope="col" width="10%" class="align-middle white-space-normal">Дата оплаты</th>
                        <th scope="col" width="15%" class="align-middle white-space-normal">Получатель</th>
                        <th scope="col" width="15%" class="align-middle white-space-normal">Услуга</th>
                        <th scope="col" width="20%" class="align-middle white-space-normal">Действия</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php $iterator = 0; @endphp
                    @foreach($incomes as $income)
                        <tr scope="row">
                            <td class="align-middle">
                                <a class="income_modal"
                                   route="{{ route('act.getIncome') }}"
                                   token="{{ csrf_token() }}"
                                   href=""
                                   data-toggle="modal"
                                   data-target="#act"
                                >
                                    {{ $income->account_number }}
                                </a>
                                <br>
                                <small class="text-muted">
                                    {{ $income->date->format('d-m-Y') }}
                                </small>
                            </td>

                            <td class="align-middle">
                                <a class="white-space-normal" href="{{ route('client.show', ['client' => $income->normalClient]) }}">
                                    @if(!empty($income->normalClient->name))
                                        {{ $income->normalClient->name }}
                                    @endif
                                        <br>
                                        <small class="text-muted">
                                            @isset($income->clientCheckingAccount)
                                                {{ $income->clientCheckingAccount->name }}
                                            @endisset
                                        </small>
                                </a>
                            </td>

                            <td class="align-middle white-space-normal">{{ $income->comment }}</td>

                            <td class="align-middle" style="white-space: nowrap;">
                                +{{ number_format($income->sum, 2, '.', ' ') }}
                            </td>

                            <td class="align-middle">
                                @if(!empty($income->income_date))
                                    {{$income->income_date->format('d-m-Y')}}
                                @endif
                            </td>

                            <td class="align-middle">
                                @if(!empty($income->payService))
                                    {{ $income->payService->name }}
                                @endif
                            </td>

                            <td class="align-middle">
                                @if(!empty($income->services))
                                    {{ $income->services->name }}
                                @endif
                            </td>

                            <td class="d-flex d-row align-items-center white-space-normal h-100">
                                @if ($income->is_payed)
                                    <a class="btn btn-icon btn-glow-success btn-success mr-2"
                                       href="{{ route('income.set-unpayed', $income) }}"
                                       onclick='return confirm("Оплата будет считаться не оплаченным, продолжить?")'
                                    >
                                        <i class="feather icon-check-circle"></i>
                                    </a>
                                @else
                                    <a class="btn btn-icon btn-glow-danger btn-outline-danger mr-2 set-pay-income"
                                       href="{{ route('income.set-payed', $income) }}"
                                       data-toggle="modal"
                                       data-target="#myModal"
                                       income="{{ $income->id }}"
                                    >
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
        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
            <div class="table-responsive">
                <table class="table table-hover table-striped text-left">
                    <thead>
                    <tr>
                        <th scope="col" width="5%" class="align-middle white-space-normal">Дата и номер счета</th>
                        <th scope="col" width="10%" class="align-middle white-space-normal">Клиент</th>
                        <th scope="col" width="15%" class="align-middle white-space-normal">Комментарий</th>
                        <th scope="col" width="10%" class="align-middle white-space-normal">Сумма</th>
                        <th scope="col" width="10%" class="align-middle white-space-normal">Дата оплаты</th>
                        <th scope="col" width="15%" class="align-middle white-space-normal">Получатель</th>
                        <th scope="col" width="15%" class="align-middle white-space-normal">Услуга</th>
                        <th scope="col" width="20%" class="align-middle white-space-normal">Действия</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php $iterator = 0; @endphp
                    @foreach($incomes as $income)
                        @if ($income->is_payed)
                            <tr>
                                <td class="align-middle">
                                    <a class="income_modal"
                                       route="{{ route('act.getIncome') }}"
                                       token="{{ csrf_token() }}"
                                       href=""
                                       data-toggle="modal"
                                       data-target="#act"
                                    >
                                        {{ $income->account_number }}
                                    </a>
                                    <br>
                                    <small class="text-muted">
                                        {{ $income->date->format('d-m-Y') }}
                                    </small>
                                </td>

                                <td class="align-middle">
                                    <a class="white-space-normal" href="{{ route('client.show', ['client' => $income->normalClient]) }}">
                                        @if(!empty($income->normalClient->name))
                                            {{ $income->normalClient->name }}
                                        @endif
                                            <br>
                                            <small class="text-muted">
                                                @isset($income->clientCheckingAccount)
                                                    {{ $income->clientCheckingAccount->name }}
                                                @endisset
                                            </small>
                                    </a>
                                </td>

                                <td class="align-middle white-space-normal">{{ $income->comment }}</td>

                                <td class="align-middle" style="white-space: nowrap;">
                                    +{{ number_format($income->sum, 2, '.', ' ') }}
                                </td>

                                <td class="align-middle">
                                    @if(!empty($income->income_date))
                                        {{$income->income_date->format('d-m-Y')}}
                                    @endif
                                </td>

                                <td class="align-middle">
                                    @if(!empty($income->payService))
                                        {{ $income->payService->name }}
                                    @endif
                                </td>

                                <td class="align-middle">
                                    @if(!empty($income->services))
                                        {{ $income->services->name }}
                                    @endif
                                </td>

                                <td class="d-flex d-row">
                                    @if ($income->is_payed)
                                        <a class="btn btn-icon btn-glow-success btn-success mr-2"
                                           href="{{ route('income.set-unpayed', $income) }}"
                                           onclick='return confirm("Оплата будет считаться не оплаченным, продолжить?")'
                                        >
                                            <i class="feather icon-check-circle"></i>
                                        </a>
                                    @else
                                        <a class="btn btn-icon btn-glow-danger btn-outline-danger mr-2 set-pay-income"
                                           href="{{ route('income.set-payed', $income) }}"
                                           data-toggle="modal"
                                           data-target="#myModal"
                                           income="{{ $income->id }}"
                                        >
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
                        @endif
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
            <div class="table-responsive">
                <table class="table table-hover table-striped text-left">
                    <thead>
                    <tr>
                        <th scope="col" width="5%" class="align-middle white-space-normal">Дата и номер счета</th>
                        <th scope="col" width="10%" class="align-middle white-space-normal">Клиент</th>
                        <th scope="col" width="15%" class="align-middle white-space-normal">Комментарий</th>
                        <th scope="col" width="10%" class="align-middle white-space-normal">Сумма</th>
                        <th scope="col" width="10%" class="align-middle white-space-normal">Дата оплаты</th>
                        <th scope="col" width="15%" class="align-middle white-space-normal">Получатель</th>
                        <th scope="col" width="15%" class="align-middle white-space-normal">Услуга</th>
                        <th scope="col" width="20%" class="align-middle white-space-normal">Действия</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php $iterator = 0; @endphp
                    @foreach($incomes as $income)
                        @if (!$income->is_payed)
                            <tr>
                                <td class="align-middle">
                                    <a class="income_modal"
                                       route="{{ route('act.getIncome') }}"
                                       token="{{ csrf_token() }}"
                                       href=""
                                       data-toggle="modal"
                                       data-target="#act"
                                    >
                                        {{ $income->account_number }}
                                    </a>
                                    <br>
                                    <small class="text-muted">
                                        {{ $income->date->format('d-m-Y') }}
                                    </small>
                                </td>

                                <td class="align-middle">
                                    <a class="white-space-normal" href="{{ route('client.show', ['client' => $income->normalClient]) }}">
                                        @if(!empty($income->normalClient->name))
                                            {{ $income->normalClient->name }}
                                        @endif
                                            <br>
                                            <small class="text-muted">
                                                @isset($income->clientCheckingAccount)
                                                    {{ $income->clientCheckingAccount->name }}
                                                @endisset
                                            </small>
                                    </a>
                                </td>

                                <td class="align-middle white-space-normal">{{ $income->comment }}</td>

                                <td class="align-middle" style="white-space: nowrap;">
                                    +{{ number_format($income->sum, 2, '.', ' ') }}
                                </td>

                                <td class="align-middle">
                                    @if(!empty($income->income_date))
                                        {{$income->income_date->format('d-m-Y')}}
                                    @endif
                                </td>

                                <td class="align-middle">
                                    @if(!empty($income->payService))
                                        {{ $income->payService->name }}
                                    @endif
                                </td>

                                <td class="align-middle">
                                    @if(!empty($income->services))
                                        {{ $income->services->name }}
                                    @endif
                                </td>

                                <td class="d-flex d-row">
                                    @if ($income->is_payed)
                                        <a class="btn btn-icon btn-glow-success btn-success mr-2"
                                           href="{{ route('income.set-unpayed', $income) }}"
                                           onclick='return confirm("Оплата будет считаться не оплаченным, продолжить?")'
                                        >
                                            <i class="feather icon-check-circle"></i>
                                        </a>
                                    @else
                                        <a class="btn btn-icon btn-glow-danger btn-outline-danger mr-2 set-pay-income"
                                           href="{{ route('income.set-payed', $income) }}"
                                           data-toggle="modal"
                                           data-target="#myModal"
                                           income="{{ $income->id }}"
                                        >
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
                        @endif
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
