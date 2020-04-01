@extends('layouts.app')

@section('content')

    <h1>Регулярные оплаты</h1>

    <hr>

    <ul class="nav nav-pills nav-tabs">
        <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#clients">Активные клиенты</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#services">Неактивные клиенты</a>
        </li>
    </ul>

    <div class="tab-content pl-0 pr-0 pt-0">
        <div class="tab-pane active" id="clients">
            <div class="d-flex justify-content-between align-items-center pl-4">
                Всего оплат: {{ $fullCalculate['count'] }}. На
                сумму {{ number_format($fullCalculate['price'], 0, ' ', ' ') }}р.
                <button class="btn btn-glow-success btn-success mb-4 mt-4 mr-4"
                        onclick="location.href = '{{ route('regularClients.create', ['token' => $company->token]) }}'">
                    <i class="feather icon-plus"></i>
                    Добавить оплату
                </button>
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                    <tr>
                        <th class="align-middle">Число</th>
                        <th class="align-middle">Клиент</th>
                        <th class="align-middle">Услуги</th>
                        <th class="align-middle">Получатель</th>
                        <th class="align-middle">Комментарий</th>
                        <th class="align-middle">Сумма</th>
                        <th class="align-middle">Действия</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php $iterator = 0; @endphp
                    @foreach($regularClients as $value)
                        <tr>
                            <td class="align-middle">{{$value->date->format('d')}}</td>
                            <td class="align-middle"><a
                                    href="{{route('client.show', ['client' => $value->normalClient])}}">@if(!empty($value->normalClient->name)){{ $value->normalClient->name }}</a>@endif
                            </td>
                            <td class="align-middle">{{$value->service}}</td>
                            <td class="align-middle">{{$value->payService->name}}</td>
                            <td class="align-middle">{{$value->comment}}</td>
                            <td class="align-middle">{{$value->sum}}</td>
                            <td class="d-flex d-row">
                                <a class="btn btn-icon btn-glow-warning btn-warning"
                                   href="{{ route('regularClients.edit', ['token' => $company->token, 'id' => $value->id]) }}">
                                    <i class="feather icon-edit"></i>
                                </a>
                                <form
                                    action="{{ route('regularClients.remove', ['token' => $company->token, 'id' => $value->id]) }}"
                                    method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-icon btn-glow-danger btn-danger"
                                            onclick='return confirm("Вы уверены, то хотите удалить?")'>
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
        <div class="tab-pane fade" id="services">
            <div class="d-flex justify-content-between align-items-center pl-4 pt-3 pb-3">
                Всего оплат: {{ $fullCalculateN['count'] }}. На
                сумму {{ number_format($fullCalculateN['price'], 0, ' ', ' ') }}р.
            </div>
            <table class="table table-hover table-striped">
                <thead>
                <tr>
                    <th class="align-middle">Число</th>
                    <th class="align-middle">Клиент</th>
                    <th class="align-middle">Услуги</th>
                    <th class="align-middle">Получатель</th>
                    <th class="align-middle">Комментарий</th>
                    <th class="align-middle">Сумма</th>
                    <th class="align-middle">Действия</th>
                </tr>
                </thead>
                <tbody>
                @php $iterator = 0; @endphp
                @foreach($regularClientsNo as $value)
                    <tr>
                        <td class="align-middle">{{$value->date->format('d')}}</td>
                        <td class="align-middle"><a
                                href="{{route('client.show', ['client' => $value->normalClient])}}">@if(!empty($value->normalClient->name)){{ $value->normalClient->name }}</a>@endif
                        </td>
                        <td class="align-middle">{{$value->service}}</td>
                        <td class="align-middle">{{$value->payService->name}}</td>
                        <td class="align-middle">{{$value->comment}}</td>
                        <td class="align-middle">{{$value->sum}}</td>
                        <td class="d-flex d-row">
                            <a class="btn btn-icon btn-glow-warning btn-warning"
                               href="{{ route('regularClients.edit', ['token' => $company->token, 'id' => $value->id]) }}">
                                <i class="feather icon-edit"></i>
                            </a>
                            <form
                                action="{{ route('regularClients.remove', ['token' => $company->token, 'id' => $value->id]) }}"
                                method="POST">
                                @csrf
                                <button type="submit" class="btn btn-icon btn-glow-danger btn-danger"
                                        onclick='return confirm("Вы уверены, то хотите удалить?")'>
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


@endsection
