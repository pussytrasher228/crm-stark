@extends('layouts.app')

@section('content')

    <h1>Редактирование</h1>

    <form action="{{ route('regularClients.update', ['token' => $company->token, 'id' => $regular->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="category">Клиент</label>
            <select name="client" id="client" class="form-control select2 clients" >
                @foreach ($company->activeClients() as $client)
                    <option value="{{ $client->id }}" {{ $client->id == $regular->normalClient->id ? 'selected' : '' }}>{{ $client->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="service">Услуга</label>
            <select name="service" id="service" class="form-control">
                @foreach ($company->activeServices() as $service)
                    <option value="{{ $service->name }}" {{ $service->name == $regular->service ? 'selected' : '' }}>{{ $service->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="pay_service">Получатель</label>
            <select name="pay_service" id="pay_service" class="form-control">
                @foreach ($company->payServices as $payService)
                    <option value="{{ $payService->id }}" {{ $payService->id == $regular->pay_service ? 'selected' : '' }}>{{ $payService->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="comment">Комментарий</label>
            <input type="text" class="form-control" name="comment" id="comment" value="{{ $regular->comment }}">
        </div>

        <div class="form-group">
            <label for="is_payed">Активный</label>
            <input type="checkbox" name="disabled" id="disabled" {{ $regular->disabled ? 'checked' : '' }}>
        </div>

        <div class="form-group">
            <label for="date">Дата счета</label>
            <input type="text" class="form-control date-picker" name="date" id="date" value="{{ $regular->date->format('d-m-Y') }}">
        </div>

        <div class="form-group">
            <label for="date">Сумма</label>
            <input type="number" class="form-control" name="sum" id="sum"  value="{{$regular->sum}}">
        </div>

        <div class="form-group">
            <button class="btn btn-success" type="submit">
                Сохранить
            </button>
            <a href="#" onclick="history.back();" class="btn btn-danger">Отмена</a>
        </div>
    </form>

@endsection
