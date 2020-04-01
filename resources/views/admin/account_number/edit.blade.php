@extends('layouts.app')

@section('content')

    <h1>Редактировать номер счета/акта</h1>

    <form action="{{ route('account_number.update', $accountNumber) }}" method="POST" enctype="multipart/form-data">
    @csrf

        <div class="form-group">
            <label for="account_number">Номер счета</label>
            <input type="number" class="form-control" name="account_number" id="account_number" value="{{ $accountNumber->account_number }}">
        </div>

        <div class="form-group">
            <label for="account_number">Номер акта для ИП</label>
            <input type="number" class="form-control" name="act_number" id="act_number" value="{{ $accountNumber->act_number }}">
        </div>

        <div class="form-group">
            <label for="account_number">Номер акта для ООО</label>
            <input type="number" class="form-control" name="ip_act_number" id="ip_act_number" value="{{ $accountNumber->act_number }}">
        </div>

        <div class="form-group">
            <button class="btn btn-success" type="submit">
                Сохранить
            </button>
            <a href="#" onclick="history.back();" class="btn btn-danger">Отмена</a>
        </div>

    </form>

@endsection