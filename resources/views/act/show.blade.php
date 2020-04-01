@extends('layouts.app')

@section('content')
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4>Выбрать дату</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="tue">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="date">Дата оплаты</label>
                        <input type="text" class="form-control date-picker act-date-picker"
                               route="{{route('act.get_income_date')}}" token="{{csrf_token()}}" name="date" id="date"
                               value="{{ old('date', (new \Carbon\Carbon())->format('Y-m-d')) }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success income-data" type="submit">Выбрать</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                </div>
            </div>
        </div>
    </div>

    <h1>Акты</h1>
    <hr>

    @include('layouts.filtrs')

    <ul class="nav nav-pills nav-tabs">
        <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#clients">Акты</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#services">Счет → акт</a>
        </li>
    </ul>

    <div class="tab-content pl-0 pr-0 pt-0">

        <div class="tab-pane active" id="clients">
            <div class="d-flex justify-content-end">
                <button class="btn btn-glow-success btn-success mb-4 mt-4 mr-4"
                        onclick="location.href = '{{ route('act.create', ['token' => $company->token]) }}'">
                    <i class="feather icon-plus"></i>
                    Добавить акт
                </button>
            </div>

            <form method="GET" action="?" enctype="multipart/form-data">
                <div class="form-row pl-3 pr-3">
                    <div class="col-12 mb-3">
                        <label for="services">Клиенты</label>
                        <select name="clients[]" id="clients" class="form-control select2" multiple>
                            @foreach ($company->activeClients() as $client)
                                @foreach($client->checkingAccounts as $check)
                                    <option
                                        value="{{ $check->name }}" {{ (!empty(request('clients')) && in_array($check->name, request('clients'))) ? 'selected' : '' }}>
                                        {{ $check->name }}
                                    </option>
                                @endforeach
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-row pl-3 pr-3">
                    <div class="col-md-3 mb-3">
                        <div class="checkbox checkbox-success checkbox-fill d-inline">
                            <input type="checkbox" name="is_paid"
                                   id="is_paid" {{ request('is_paid') ? 'checked' : '' }}>
                            <label for="is_paid" class="cr">Подписан</label>
                        </div>
                        <br>
                        <div class="checkbox checkbox-success checkbox-fill d-inline">
                            <input type="checkbox" name="is_not_paid"
                                   id="is_not_paid" {{ request('is_not_paid') ? 'checked' : '' }}>
                            <label for="is_not_paid" class="cr">Не подписан</label>
                        </div>
                    </div>
                    <div class="col-md-9 mb-3 text-right">
                        <label for="search"></label>
                        <button id="search" class="btn btn-icon btn-primary-glow btn-primary mr-0" type="submit">
                            <i class="feather icon-search"></i>
                        </button>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                    <tr>
                        <th class="align-middle">Дата</th>
                        <th class="align-middle">Номер акта</th>
                        <th class="align-middle">Юр. лицо клиента</th>
                        <th class="align-middle">Номер счета</th>
                        <th class="align-middle">Услуга</th>
                        <th class="align-middle">Сумма</th>
                        <th width="20%" class="align-middle">Действия</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php $iterator = 0; @endphp
                    @foreach($act as $value)
                        <tr>
                            <td class="align-middle">{{ $value->data->format('d-m-Y') }}</td>
                            <td class="align-middle">
                                @if(!empty($value->number))
                                    {{$value->number}}
                                @endif
                                {{$value->ip_act_number }}
                            </td>
                            <td class="align-middle">
                                @foreach($value->checkingAccounts as $check_name)
                                    {{$check_name->name}}
                                @endforeach
                            </td>
                            <td class="align-middle">
                                @foreach($value->actService as $incom_number)
                                    <a class="income_modal" route="{{route('act.getIncome')}}" token="{{csrf_token()}}"
                                       href="" data-toggle="modal" data-target="#act">{{$incom_number->income_id}}</a>
                                    &nbsp;
                                @endforeach
                            </td>
                            <td class="align-middle">
                                @foreach($value->actProducts as $actProducts)
                                    {{$actProducts->product}}
                                @endforeach
                            </td>
                            <td class="align-middle">{{ number_format($value->summa, 0, ' ', ' ') }}</td>
                            <td class="d-flex d-row">
                                @if ($value->status)
                                    <a class="btn btn-icon btn-glow-success btn-success mr-2"
                                       href="{{ route('act.set-unpayed', $value) }}"
                                       onclick='return confirm("Акт будет считаться не подписанным, продолжить?")'>
                                        <i class="feather icon-check-circle"></i>
                                    </a>
                                @else
                                    <a class="btn btn-icon btn-glow-danger btn-outline-danger mr-2 set-pay-act"
                                       data-toggle="modal"
                                       data-target="#myModal"
                                       act="{{$value->id}}"
                                       href="#">
                                        <i class="feather icon-slash"></i>
                                    </a>
                                @endif
                                <form action="{{ route('act.print.exit', $value) }}" method="POST"
                                      enctype="multipart/form-data" target="_blank">
                                    @foreach($value->checkingAccounts as $check_name)
                                        <input type="hidden" value="{{$check_name->id}}" name="client_account">
                                    @endforeach
                                    {{--;--}}
                                    <input type="hidden" value="{{$value->pay_service}}" name="pay_service">
                                    <button class="btn btn-icon btn-glow-info btn-info">
                                        <i class="feather icon-download"></i>
                                    </button>
                                </form>
                                <a class="btn btn-icon btn-glow-warning btn-warning"
                                   href="{{ route('act.edit', ['token' => $company->token, 'id' => $value->id]) }}">
                                    <i class="feather icon-edit"></i>
                                </a>
                                <form
                                    action="{{ route('act.remove', ['token' => $company->token, 'id' => $value->id ]) }}"
                                    method="POST">
                                    @csrf
                                    @foreach($value->actService as $incom_number)
                                        <?php $key = 0; ?>
                                        <input type="hidden" value="{{$incom_number->income_id}}" name="incoms[]">
                                        <?php $key++; ?>
                                    @endforeach
                                    <button type="submit" class="btn btn-icon btn-glow-danger btn-danger"
                                            onclick='return confirm("Вы уверены, то хотите удалить акт?")'>
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
            <div class="d-flex justify-content-end">
                <button class="btn btn-glow-success btn-success mb-4 mt-4 mr-4"
                        onclick="location.href = '{{ route('service.create', ['token' => $company->token]) }}'">
                    <i class="feather icon-plus"></i>
                    Добавить счет
                </button>
            </div>

            <div class="table-responsive">

                <table class="table table-hover table-striped">
                    <thead>
                    <tr>
                        <th width="5%" class="align-middle">Номер счета</th>
                        <th class="align-middle">Клиент</th>
                        <th class="align-middle">Номер акта</th>
                        <th class="align-middle">Дата</th>
                        <th width="20%" class="align-middle">Действия</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php $iterator = 0; @endphp
                    @foreach($act as $value)
                        @foreach($value->actService as $incom_number)
                            <tr>
                                <td class="align-middle">
                                    <a class="income_modal" route="{{route('act.getIncome')}}" token="{{csrf_token()}}"
                                       href="" data-toggle="modal" data-target="#act">{{$incom_number->income_id}}</a>&nbsp;
                                </td>
                                <td class="align-middle">
                                    @foreach($value->checkingAccounts as $check_name)
                                        {{$check_name->name}}
                                    @endforeach
                                </td>
                                <td class="align-middle">
                                    @if(!empty($value->number))
                                        {{$value->number}}
                                    @endif
                                    {{$value->ip_act_number }}
                                </td>
                                <td class="align-middle">{{$value->data->format('d-m-Y')}}</td>
                                <td class="d-flex d-row">
                                    @if ($value->status)
                                        <a class="btn btn-icon btn-glow-success btn-success mr-2"
                                           href="{{ route('act.set-unpayed', $value) }}"
                                           onclick='return confirm("Акт будет считаться не подписанным, продолжить?")'>
                                            <i class="feather icon-check-circle"></i>
                                        </a>
                                    @else
                                        <a class="btn btn-icon btn-glow-danger btn-outline-danger set-pay-act"
                                           data-toggle="modal"
                                           data-target="#myModal" href="#" act="{{$value->id}}">
                                            <i class="feather icon-slash"></i>
                                        </a>
                                    @endif
                                    <form action="{{ route('act.print.exit', $value) }}" method="POST"
                                          enctype="multipart/form-data" target="_blank">
                                        @foreach($value->checkingAccounts as $check_name)
                                            <input type="hidden" value="{{$check_name->id}}" name="client_account">
                                        @endforeach
                                        <input type="hidden" value="{{$value->pay_service}}" name="pay_service">
                                        <button class="btn btn-icon btn-glow-info btn-info"
                                                type="submit">
                                            <i class="feather icon-download"></i>
                                        </button>
                                    </form>
                                    <a class="btn btn-icon btn-glow-warning btn-warning"
                                       href="{{ route('act.edit', ['token' => $company->token, 'id' => $value->id]) }}">
                                        <i class="feather icon-edit"></i>
                                    </a>
                                    <form
                                        action="{{ route('act.remove', ['token' => $company->token, 'id' => $value->id ]) }}"
                                        method="POST">
                                        @csrf
                                        @foreach($value->actService as $incom_number)
                                            <?php $key = 0; ?>
                                            <input type="hidden" value="{{$incom_number->income_id}}" name="incoms[]">
                                            <?php $key++; ?>
                                        @endforeach
                                        <button type="submit" class="btn btn-icon btn-glow-danger btn-danger"
                                                onclick='return confirm("Вы уверены, то хотите удалить акт?")'>
                                            <i class="feather icon-trash-2"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>

                        @endforeach
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
