@extends('layouts.app')

@section('content')

    <h1>Новый номер счета/акта</h1>
    <hr>

    <form action="{{ route('account_number.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group row">
            <label for="account_number" class="col-sm-2 col-form-label">Номер счета</label>
            <div class="col-sm-10">
                <input type="number" class="form-control" name="account_number" id="account_number"
                       value="{{ old('account_number', '') }}">
            </div>
        </div>

        <div class="form-group row">
            <label for="account_number" class="col-sm-2 col-form-label">Номер акта для ИП</label>
            <div class="col-sm-10">
                <input type="number" class="form-control" name="act_number" id="act_number"
                       value="{{ old('act_number', '') }}">
            </div>
        </div>

        <div class="form-group row">
            <label for="account_number" class="col-sm-2 col-form-label">Номер акта для ООО</label>
            <div class="col-sm-10">
                <input type="number" class="form-control" name="ip_act_number" id="ip_act_number"
                       value="{{ old('ip_act_number', '') }}">
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
