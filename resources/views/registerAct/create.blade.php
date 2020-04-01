@extends('layouts.app')

@section('content')

    <h1>Новый договор</h1>
    <hr>

    <form action="{{ route('registerAct.store', ['token' => $company->token]) }}" method="POST"
          enctype="multipart/form-data">
        @csrf
        <div class="form-group row">
            <label for="comment" class="col-sm-2 col-form-label">Номер договора</label>
            <div class="col-sm-10">
                <input type="number" class="form-control" name="number" id="number" value="{{ old('comment', '') }}">
            </div>
        </div>

        <div class="form-group row">
            <label for="category" class="col-sm-2 col-form-label">Клиент</label>
            <div class="col-sm-10">
                <select name="client" id="client" class="form-control select2 clients">
                    @foreach ($company->activeClients() as $client)
                        <option value="{{ $client->id }}">{{ $client->name }}</option>
                    @endforeach
                </select>
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
            <label for="date" class="col-sm-2 col-form-label">Дата</label>
            <div class="col-sm-10">
                <input type="text" class="form-control date-picker" name="date" id="date"
                       value="{{ old('date', (new \Carbon\Carbon())->setTimezone('Asia/Novosibirsk')->format('d-m-Y')) }}">
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

