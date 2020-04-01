@extends('layouts.app')

@section('content')
    <h1>Клиенты</h1>
    <hr>

    <div class="card">
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-between">
                <form class="col-5 m-0" method="GET" action="?" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="clients">Поиск</label>
                            <select name="clients" id="clients" class="form-control select2" onchange="this.form.submit()">
                                <option value="">Все клиенты</option>
                                @foreach ($company->activeClients() as $client)
                                    <option
                                        value="{{$client->name }}" {{ (!empty(request('clients')) && $client->name == request('clients')) ? 'selected' : '' }}>
                                        {{ $client->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                </form>
                <a class="btn btn-glow-success btn-success mb-0 mt-0"
                   href="{{ route('client.create', ['token' => $company->token]) }}">
                    <i class="feather icon-plus"></i>
                    Добавить
                </a>
            </div>
        </div>
    </div>

    <ul class="nav nav-pills nav-tabs mb-3" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link text-uppercase active" id="client-tab" data-toggle="tab" href="#client" role="tab"
               aria-controls="client" aria-selected="false">Клиенты</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-uppercase" id="archive-tab" data-toggle="tab" href="#archive" role="tab"
               aria-controls="archive" aria-selected="false">Архив</a>
        </li>
    </ul>
    <div class="tab-content pl-0 pr-0 pt-0" id="myTabContent">
        <div class="tab-pane fade active show" id="client" role="tabpanel" aria-labelledby="client-tab">
            <div class="table-responsive">
                <table class="table table-hover table-striped text-left">
                    <thead>
                    <tr>
                        <th class="align-middle">Наименование</th>
                        <th class="align-middle">Телефон</th>
                        <th class="align-middle">Почта</th>
                        <th class="align-middle">Дата создания</th>
                        <th width="20%" class="align-middle">Действия</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php $iterator = 0; @endphp
                    @foreach($clients as $client)
                        <tr>
                            <td class="align-middle"><a href="{{route('client.show', ['client' => $client])}}">{{ $client->name }}</a></td>
                            <td class="align-middle"></td>
                            <td class="align-middle"></td>
                            <td class="align-middle">{{ $client->created_at }}</td>
                            <td class="align-middle">
                                <a class="btn btn-icon btn-glow-warning btn-warning"
                                   href="{{ route('client.edit', ['client' => $client]) }}">
                                    <i class="feather icon-edit"></i>
                                </a>
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
        <div class="tab-pane fade" id="archive" role="tabpanel" aria-labelledby="archive-tab">
            <div class="table-responsive">
                <table class="table table-hover table-striped text-left">
                    <thead>
                    <tr>
                        <th width="80%" class="align-middle">Наименование</th>
                        <th width="20%" class="align-middle">Действия</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php $iterator = 0; @endphp
                    @foreach($company->noActiveClients() as $client)
                        <tr>
                            <td class="align-middle">
                                <a href="{{ route('client.show', ['client' => $client])}}">{{$client->name}}</a>
                            </td>
                            <td class="align-middle">
                                <a class="btn btn-icon btn-glow-warning btn-warning"
                                   href="{{ route('client.edit', ['client' => $client]) }}">
                                    <i class="feather icon-edit"></i>
                                </a>
{{--                                <form--}}
{{--                                    action="{{ route('income.remove', ['token' => $company->token, 'id' => $income->id]) }}"--}}
{{--                                    method="POST">--}}
{{--                                    @csrf--}}
{{--                                    <button type="submit" class="btn btn-link">Удалить</button>--}}
{{--                                </form>--}}
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
