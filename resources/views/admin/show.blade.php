@extends('layouts.app')

@section('content')

    <h1>Админ-панель</h1>
    <hr>

    <ul class="nav nav-tabs nav-pills mb-3">
        <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#clients">Клиенты</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#services">Услуги</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#pay_services">Варианты оплаты</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#plane">План продаж</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#number">Номер счета/акта:</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#logo">Логотип</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#extra">Категории расходов</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#company">Моя компания</a>
        </li>
    </ul>

    <div class="tab-content pl-0 pr-0 pt-0">
        <div class="tab-pane active" id="clients">
            <div class="d-flex justify-content-end">
                <button class="btn btn-glow-success btn-success mb-4 mt-4 mr-4"
                        onclick="location.href = '{{ route('client.create', ['token' => $company->token]) }}'">
                    <i class="feather icon-plus"></i>
                    Добавить клиента
                </button>
            </div>

            <table class="table table-hover table-striped">
                <thead>
                <tr>
                    <th width="80%" class="align-middle">Наименование</th>
                    <th width="20%" class="align-middle">Действие</th>
                </tr>
                </thead>
                <tbody>
                @php $iterator = 0; @endphp
                @foreach($clients as $client)
                    <tr>
                        @if($client->disabled == 1)
                            <td class="align-middle">{{ $client->name }} <span style="color: red">(клиент неактивен)</span></td>
                        @elseif($client->disabled == null)
                            <td class="align-middle">{{ $client->name }}</td>
                        @endif
                        <td class="align-middle">
                            <button class="btn btn-icon btn-glow-warning btn-warning"
                                    onclick="location.href = '{{ route('client.edit', ['client' => $client]) }}'">
                                <i class="feather icon-edit"></i>
                            </button>
                            <button class="btn btn-icon btn-glow-danger btn-danger"
                                    onclick="location.href = '{{ route('client.remove', ['client' => $client]) }}'">
                                <i class="feather icon-trash-2"></i>
                            </button>
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

        <div class="tab-pane fade" id="services">
            <div class="d-flex justify-content-end">
                <button class="btn btn-glow-success btn-success mb-4 mt-4 mr-4"
                        onclick="location.href = '{{ route('service.create', ['token' => $company->token]) }}'">
                    <i class="feather icon-plus"></i>
                    Добавить услугу
                </button>
            </div>


            <table class="table table-hover table-striped">
                <thead>
                <tr>
                    <th width="50%" class="align-middle">Наименование</th>
                    <th width="30%" class="align-middle">Участие в доходах</th>
                    <th width="20%" class="align-middle">Действие</th>
                </tr>
                </thead>
                <tbody>
                @php $iterator = 0; @endphp
                @foreach($services as $service)
                    <tr>
                        @if($service->disabled == 1)
                            <td class="align-middle">{{ $service->name }} <span style="color: red">(услуга отключена)</span></td>
                        @elseif($service->disabled == null)
                            <td class="align-middle">{{ $service->name }}</td>
                        @endif
                        @if($service->income == true)
                            <td style="color: green"><i class="fas fa-check"></i></td>
                        @elseif($service->income == false)
                            <td style="color: red"><i class="fas fa-times"></i></td>
                        @endif
                        <td class="align-middle">
                            <button class="btn btn-icon btn-glow-warning btn-warning"
                                    onclick="location.href = '{{ route('service.edit', ['service' => $service]) }}'">
                                <i class="feather icon-edit"></i>
                            </button>
                            <button class="btn btn-icon btn-glow-danger btn-danger"
                                    onclick="location.href = '{{ route('service.remove', ['service' => $service]) }}'">
                                <i class="feather icon-trash-2"></i>
                            </button>
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

        <div class="tab-pane fade" id="pay_services">
            <div class="d-flex justify-content-end">
                <button class="btn btn-glow-success btn-success mb-4 mt-4 mr-4"
                        onclick="location.href = '{{ route('pay_service.create', ['token' => $company->token]) }}'">
                    <i class="feather icon-plus"></i>
                    Добавить вариант оплаты
                </button>
            </div>

            <table class="table table-hover table-striped">
                <thead>
                <tr>
                    <th width="80%" class="align-middle">Название</th>
                    <th width="20%" class="align-middle">Действие</th>
                </tr>
                </thead>
                <tbody>
                @php $iterator = 0; @endphp
                @foreach($payServices as $payService)
                    <tr>
                        <td class="align-middle">{{ $payService->name }}</td>
                        <td class="align-middle">
                            <button class="btn btn-icon btn-glow-warning btn-warning"
                                    onclick="location.href = '{{ route('pay_service.edit', ['payService' => $payService->id]) }}'">
                                <i class="feather icon-edit"></i>
                            </button>
                            <button class="btn btn-icon btn-glow-danger btn-danger"
                                    onclick="location.href = '{{ route('pay_service.remove', ['payService' => $payService->id]) }}'">
                                <i class="feather icon-trash-2"></i>
                            </button>
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

        <div class="tab-pane fade" id="plane">
            @if(empty($incomePlans) || $incomePlans->count() == 0)
                <div class="d-flex justify-content-end">
                    <button class="btn btn-glow-success btn-success mb-4 mt-4 mr-4"
                            onclick="location.href = '{{ route('incomePlans.create') }}'">
                        <i class="feather icon-plus"></i>
                        Добавить план продаж
                    </button>
                </div>
            @else
                <div class="d-flex justify-content-end">
                    <button class="btn btn-glow-warning btn-warning mb-4 mt-4 mr-4"
                            onclick="location.href = '{{ route('IncomePlans.edit', ['years' => date('Y')]) }}'">
                        <i class="feather icon-edit"></i>
                        Редактировать план продаж
                    </button>
                </div>
            @endif

            <table class="table table-hover table-striped">
                <thead>
                <tr>
                    <th class="align-middle">Месяц</th>
                    <th class="align-middle">План продаж</th>
                </tr>
                @foreach($incomePlans as $incomePlan)
                    <tr>
                        <td class="align-middle">
                            {{$incomePlan->mounth_name}}
                        </td>
                        <td class="align-middle">
                            {{$incomePlan->plan}}
                        </td>
                    </tr>
                @endforeach
                </thead>
                <tbody>

            </table>

        </div>

        <div class="tab-pane fade" id="number">
            <div class="d-flex justify-content-end">
                <button class="btn btn-glow-success btn-success mb-4 mt-4 mr-4"
                        onclick="location.href = '{{ route('account_number.create', ['token' => $company->token]) }}'">
                    <i class="feather icon-plus"></i>
                    Добавить номер счета/акта
                </button>
            </div>

            <table class="table table-hover table-striped">
                <thead>
                <tr>
                    <th class="align-middle">Номер счета</th>
                    <th class="align-middle">Номер акта для ИП</th>
                    <th class="align-middle">Номер акта для ОО</th>
                    <th class="align-middle">Действие</th>
                </tr>
                </thead>
                <tbody>
                @php $iterator = 0; @endphp
                @foreach($accountNumbers as $accountNumber)
                    <tr>
                        <td class="align-middle">{{ $accountNumber->account_number }}</td>
                        <td class="align-middle">{{ $accountNumber->ip_act_number }}</td>
                        <td class="align-middle">{{ $accountNumber->act_number }}</td>
                        <td class="align-middle">
                            <button class="btn btn-icon btn-glow-warning btn-warning"
                                    onclick="location.href = '{{ route('account_number.edit', ['accountNumber' => $accountNumber->id]) }}'">
                                <i class="feather icon-edit"></i>
                            </button>
                            <button class="btn btn-icon btn-glow-danger btn-danger"
                                    onclick="location.href = '{{ route('account_number.remove', ['accountNumber' => $accountNumber->id]) }}'">
                                <i class="feather icon-trash-2"></i>
                            </button>
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

        <div class="tab-pane fade" id="logo">
            <div class="d-flex justify-content-end">
                <button class="btn btn-glow-success btn-success mb-4 mt-4 mr-4"
                        onclick="location.href = '{{ route('logo.create', ['token' => $company->token]) }}'">
                    <i class="feather icon-plus"></i>
                    Добавить логотип
                </button>
            </div>

            <table class="table table-hover table-striped">
                <thead>
                <tr>
                    <th width="80%" class="align-middle">Логотип</th>
                    <th width="20%" class="align-middle">Действие</th>
                </tr>
                </thead>
                <tbody>
                @php $iterator = 0; @endphp
                @foreach($logo as $log)
                    <tr>
                        <td class="align-middle"><img src="{{$log->getLogo()}}" alt="" style="width: 100px"></td>
                        <td class="align-middle">
                            <button class="btn btn-icon btn-glow-danger btn-danger"
                                    onclick="location.href = '{{ route('logo.remove', ['logo' => $log->id]) }}'">
                                <i class="feather icon-trash-2"></i>
                            </button>
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

        <div class="tab-pane fade" id="extra">
            <div class="d-flex justify-content-end">
                <button class="btn btn-glow-success btn-success mb-4 mt-4 mr-4"
                        onclick="location.href = '{{ route('category.create', ['token' => $company->token]) }}'">
                    <i class="feather icon-plus"></i>
                    Добавить категорию
                </button>
            </div>

            <table class="table table-hover table-striped">
                <thead>
                <tr>
                    <th width="40%" class="align-middle">Родительская категория</th>
                    <th width="40%" class="align-middle">Название</th>
                    <th width="20%" class="align-middle">Действия</th>
                </tr>
                </thead>
                <tbody>
                @foreach($categories as $category)
                    <tr>
                        <th class="align-middle">Нет</th>
                        <th class="align-middle">{{ $category->name }}</th>
                        <th class="align-middle">
                            <button class="btn btn-icon btn-glow-warning btn-warning"
                                    onclick="location.href = '{{ route('category.edit', ['id' => $category->id, 'token' => $company->token]) }}'">
                                <i class="feather icon-edit"></i>
                            </button>
                        </th>
                    </tr>
                    @foreach ($category->childs as $childCategory)
                        <tr>
                            <td class="align-middle">{{ $childCategory->parent->name}}</td>
                            <td class="align-middle">{{ $childCategory->name }}</td>
                            <td class="align-middle">
                                <button class="btn btn-icon btn-glow-warning btn-warning"
                                        onclick="location.href = '{{ route('category.edit', ['id' => $childCategory->id, 'token' => $company->token]) }}'">
                                    <i class="feather icon-edit"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                @endforeach
                </tbody>
            </table>

        </div>

        <div class="tab-pane fade" id="company">
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                    <tr>
                        <th class="align-middle">Название</th>
                        <th class="align-middle">Телефон</th>
                        <th class="align-middle">Сайт</th>
                        <th class="align-middle">Почта</th>
                        <th class="align-middle">Адрес</th>
                        <th class="align-middle">Директор</th>
                        <th class="align-middle">Действия</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <th class="align-middle">{{ $company->name }}</th>
                        <th class="align-middle">{{ $company->phone }}</th>
                        <th class="align-middle"><a href="http://{{ $company->site }}">{{ $company->site }}</a></th>
                        <th class="align-middle">{{ $company->email }}</th>
                        <th class="align-middle">{{ $company->addres }}</th>
                        <th class="align-middle">{{ $company->direct }}</th>
                        <th class="align-middle">
                            <button class="btn btn-icon btn-glow-warning btn-warning"
                                    onclick="location.href = '{{ route('company.edit', ['id' => $company->id]) }}'">
                                <i class="feather icon-edit"></i>
                            </button>
                        </th>
                    </tr>
                    </tbody>
                </table>
            </div>

        </div>

    </div>


@endsection
