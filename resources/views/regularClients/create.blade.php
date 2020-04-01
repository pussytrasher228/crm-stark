@extends('layouts.app')

@section('content')

    <h1>Новая оплата</h1>
    <hr>

    <form action="{{ route('regularClients.store', ['token' => $company->token]) }}" method="POST"
          enctype="multipart/form-data">
        @csrf

        <div class="form-group row">
            <label for="client" class="col-sm-2 col-form-label">Клиент</label>
            <div class="col-sm-10">
                <select name="client" id="client" class="form-control select2 clients">
                    @foreach ($company->activeClients() as $client)
                        <option value="{{ $client->id }}">{{ $client->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label for="service" class="col-sm-2 col-form-label">Услуга</label>
            <div class="col-sm-10">
                <div id="services">
                    <select name="service" id="service" class="form-control">
                        @foreach ($company->activeServices() as $service)
                            <option value="{{ $service->name }}">{{ $service->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="form-group row">
            <label for="pay_service" class="col-sm-2 col-form-label">Получатель</label>
            <div class="col-sm-10">
                <select name="pay_service" id="pay_service" class="form-control">
                    @foreach ($company->payServices as $payService)
                        <option value="{{ $payService->id }}">{{ $payService->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label for="comment" class="col-sm-2 col-form-label">Комментарий</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="comment" id="comment" value="{{ old('comment', '') }}">
            </div>
        </div>

        <div class="form-group row">
            <label for="disabled" class="col-sm-2 col-form-label">Активный</label>
            <div class="col-sm-10">
                <div class="checkbox checkbox-success checkbox-fill d-inline">
                    <input type="checkbox" name="disabled" checked="checked" id="disabled"
                           value="{{ old('disabled', '1') }}">
                    <label for="disabled" class="cr"></label>
                </div>
            </div>
        </div>

        <div class="form-group row">
            <label for="date" class="col-sm-2 col-form-label">Дата счета</label>
            <div class="col-sm-10">
                <input type="text" class="form-control date-picker" name="date" id="date"
                       value="{{ old('date', (new \Carbon\Carbon())->setTimezone('Asia/Novosibirsk')->format('d-m-Y')) }}">
            </div>
        </div>

        <div class="form-group row">
            <label for="date" class="col-sm-2 col-form-label">Сумма</label>
            <div class="col-sm-10">
                <input type="number" class="form-control" name="sum" id="sum" value="{{ old('sum', '') }}">
            </div>
        </div>

        <div class="form-group">
            <div class="d-flex justify-content-end">
                <button class="btn btn-glow-success btn-success" type="submit">
                    Сохранить
                </button>
                <a href="#" onclick="history.back();" class="btn btn-glow-danger btn-danger">Отмена</a>
            </div>
        </div>
    </form>

@endsection
