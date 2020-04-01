@extends('layouts.app')

@section('content')

    {{-- О клиенте --}}
    <div class="d-flex justify-content-between">
        <h1>{{ $client->name }}</h1>
        <a class="btn btn-icon btn-glow-warning btn-warning"
           href="{{ route('client.edit', ['client' => $client]) }}">
            <i class="feather icon-edit"></i>
        </a>
    </div>
    <hr>

    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4>Выбрать дату оплаты</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="tue">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="date">Дата оплаты</label>
                        <input type="text" class="form-control date-picker income-date-picker"
                               route="{{route('income.get_income_date')}}" token="{{csrf_token()}}" name="date"
                               id="date" value="{{ old('date', (new \Carbon\Carbon())->format('Y-m-d')) }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success income-data" type="submit">Оплачено</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                </div>
            </div>
        </div>
    </div>

    <ul class="nav nav-pills nav-tabs mb-3" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link text-uppercase active" id="incomes-tab" data-toggle="tab" href="#incomes" role="tab"
               aria-controls="incomes" aria-selected="false">Оплаты</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-uppercase" id="employees-tab" data-toggle="tab" href="#employees" role="tab"
               aria-controls="employees" aria-selected="false">Контакты</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-uppercase" id="entities-tab" data-toggle="tab" href="#entities" role="tab"
               aria-controls="entities" aria-selected="false">Юридические лица</a>
        </li>
    </ul>
    <div class="tab-content pl-0 pr-0 pt-0" id="myTabContent">
        <div class="tab-pane fade active show" id="incomes" role="tabpanel" aria-labelledby="incomes-tab">
            <div class="d-flex justify-content-end">
                <a class="btn btn-glow-success btn-success mb-3 mt-3 mr-3"
                   href="{{ route('income.create', ['token' => $company->token]) }}">
                    <i class="feather icon-plus"></i>
                    Добавить оплату
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                    <tr>
                        <th scope="col" width="5%" class="align-middle white-space-normal">Дата и номер счета</th>
                        <th scope="col" width="20%" class="align-middle white-space-normal">Комментарий</th>
                        <th scope="col" width="10%" class="align-middle white-space-normal">Сумма</th>
                        <th scope="col" width="10%" class="align-middle white-space-normal">Дата оплаты</th>
                        <th scope="col" width="20%" class="align-middle white-space-normal">Получатель</th>
                        <th scope="col" width="15%" class="align-middle white-space-normal">Услуга</th>
                        <th scope="col" width="20%" class="align-middle white-space-normal">Действия</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php $iterator = 0; @endphp
                    @foreach($incomes as $income)
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

                            <td class="align-middle white-space-normal">{{ $income->comment }}</td>

                            <td class="align-middle" style="white-space: nowrap;">
                                +{{ number_format($income->sum, 2, '.', ' ') }}
                            </td>

                            <td class="align-middle">
                                @if(!empty($income->income_date))
                                    {{$income->income_date->format('d-m-Y')}}
                                @endif
                            </td>

                            <td class="align-middle white-space-normal">
                                @if(!empty($income->payService))
                                    {{ $income->payService->name }}
                                @endif
                            </td>

                            <td class="align-middle white-space-normal">
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
        <div class="tab-pane fade" id="employees" role="tabpanel" aria-labelledby="employees-tab">
            <div class="d-flex justify-content-end">
                <a class="btn btn-glow-success btn-success mb-3 mt-3 mr-3"
                   href="{{ route('employees.create', $client) }}">
                    <i class="feather icon-plus"></i>
                    Добавить контакт
                </a>
            </div>

            @if ($client->employee->count() == 0)
                <div class="d-flex justify-content-center">
                    <p>Контактов пока нет</p>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                    <tr>
                        <th>Имя</th>
                        <th>Почта</th>
                        <th>Телефон</th>
                        <th>Должность</th>
                        <th>Комментарий</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($client->employee as $eKey => $employee)
                        <tr>
                            <td class="white-space-normal">
                                {{ $employee->name }}
                            </td>
                            <td class="white-space-normal">
                                {{ $employee->email }}
                            </td>
                            <td class="white-space-normal">
                                {{ $employee->phone }}
                            </td>
                            <td class="white-space-normal">
                                {{ $employee->position }}
                            </td>
                            <td class="white-space-normal">
                                {{ $employee->comment }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="tab-pane fade" id="entities" role="tabpanel" aria-labelledby="entities-tab">

            <div class="d-flex justify-content-end">
                <a class="btn btn-glow-success btn-success mb-3 mt-3 mr-3"
                   href="{{ route('client_account.create', $client) }}">
                    <i class="feather icon-plus"></i>
                    Добавить юр. лицо
                </a>
            </div>

            @if ($client->checkingAccounts->count() == 0)
                <div class="d-flex justify-content-center">
                    <p>Юридических лиц пока нет</p>
                </div>
            @endif

            <table class="table table-hover table-striped" id="accordionContainer">
                <thead>
                <tr>
                    <th colspan="2">Имя</th>
                    {{--                    <th>Расчетный счет</th>--}}
                    {{--                    <th>К\С</th>--}}
                    {{--                    <th>ИНН</th>--}}
                    {{--                    <th>КПП</th>--}}
                    {{--                    <th>БИК</th>--}}
                    {{--                    <th>Банк</th>--}}
                    {{--                    <th>Юр. адрес</th>--}}
                    {{--                    <th>Факт. адрес</th>--}}
                    {{--                    <th>Почта</th>--}}
                    {{--                    <th>Действия</th>--}}
                </tr>
                </thead>
                <tbody>
                {{--                @foreach ($client->checkingAccounts as $key => $checkingAccount)--}}
                {{--                    <tr>--}}
                {{--                        <td>{{ $checkingAccount->name }}</td>--}}
                {{--                        <td>{{ $checkingAccount->checking_account }}</td>--}}
                {{--                        <td>{{ $checkingAccount->ks }}</td>--}}
                {{--                        <td>{{ $checkingAccount->inn }}</td>--}}
                {{--                        <td>{{ $checkingAccount->kpp }}</td>--}}
                {{--                        <td>{{ $checkingAccount->bik }}</td>--}}
                {{--                        <td>{{ $checkingAccount->bank_name }}</td>--}}
                {{--                        <td>Юр. адрес</td>--}}
                {{--                        <td>Факт. адрес</td>--}}
                {{--                        <td>Почта</td>--}}
                {{--                        <td>Действия</td>--}}
                {{--                    </tr>--}}
                {{--                @endforeach--}}
                @foreach ($client->checkingAccounts as $key => $checkingAccount)
                    <tr style="cursor: pointer;" data-toggle="collapse" data-target="#acc-{{ $key+25 }}">
                        <td class="align-middle">
                            <div class="d-flex justify-content-between align-items-center">
                                {{ $checkingAccount->name }}
                                <div>
                                    <button class="btn btn-icon btn-glow-warning btn-warning"
                                            onclick="location.href = '{{ route('client_account.edit', $checkingAccount) }}'">
                                        <i class="feather icon-edit"></i>
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="hidden"></tr>
                    <tr class="collapse" style="background: #bfccce52;" data-parent="#accordionContainer"
                        id="acc-{{ $key+25 }}">
                        <td class="align-middle">
                            Наименование: {{ $checkingAccount->name }}
                            <hr>
                            @if ($checkingAccount->checking_account)
                                Расчетный счет: {{ $checkingAccount->checking_account }}
                                <hr>
                            @endif

                            @if ($checkingAccount->ks)
                                К\С: {{ $checkingAccount->ks }}
                                <hr>
                            @endif

                            @if ($checkingAccount->inn)
                                ИНН: {{ $checkingAccount->inn }}
                                <hr>
                            @endif

                            @if ($checkingAccount->kpp)
                                КПП: {{ $checkingAccount->kpp }}
                                <hr>
                            @endif

                            @if ($checkingAccount->bik)
                                БИК: {{ $checkingAccount->bik }}
                                <hr>
                            @endif

                            @if ($checkingAccount->bank_name)
                                Наименование банка: {{ $checkingAccount->bank_name }}
                                <hr>
                            @endif

                            @if ($checkingAccount->ur_address)
                                Юридический адрес: {{ $checkingAccount->ur_address }}
                                <hr>
                            @endif

                            @if ($checkingAccount->fact_address)
                                Фактический адрес: {{ $checkingAccount->fact_address }}
                                <hr>
                            @endif

                            @if ($checkingAccount->mail_address)
                                Почтовый адрес: {{ $checkingAccount->mail_address }}
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
