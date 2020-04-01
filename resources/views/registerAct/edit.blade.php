@extends('layouts.app')

@section('content')

    <h1>Редактирование акта</h1>
    <hr>

    <form action="{{ route('registerAct.update', ['token' => $company->token, 'registerAct' => $registerAct]) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="comment">Номер договора</label>
            <input type="text" class="form-control" name="number" id="number" value="{{ $registerAct->number }}">
        </div>

        <div class="form-group">
            <label for="category">Клиент</label>
            <select name="client" id="client" class="form-control select2 clients" >
                @foreach ($company->activeClients() as $client)
                    <option value="{{ $client->id }}" {{ $client->id == $registerAct->normalClient->id ? 'selected' : '' }}>{{ $client->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="pay_service">Получатель</label>
            <select name="pay_service" id="pay_service" class="form-control">
                @foreach ($company->payServices as $payService)
                    <option value="{{ $payService->id }}" {{ $payService->id == $registerAct->pay_service ? 'selected' : '' }}>{{ $payService->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="comment">Комментарий</label>
            <input type="text" class="form-control" name="comment" id="comment" value="{{ $registerAct->comment }}">
        </div>

        <div class="form-group">
            <label for="date">Дата</label>
            <input type="text" class="form-control date-picker" name="date" id="date" value="{{ $registerAct->date->format('d-m-Y') }}">
        </div>

        <div class="form-group">
            <button class="btn btn-success" type="submit">
                Сохранить
            </button>
            <a href="#" onclick="history.back();" class="btn btn-danger">Отмена</a>
        </div>
    </form>

@endsection
