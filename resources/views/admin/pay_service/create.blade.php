@extends('layouts.app')

@section('content')
    <h1>Новый вариант оплаты</h1>
    <hr>

    <form action="{{ route('pay_service.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group row">
            <label for="name" class="col-sm-2 col-form-label">Название</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="name" id="name" value="{{ old('name', '') }}">
            </div>
        </div>

        <div class="form-group row">
            <label for="category" class="col-sm-2 col-form-label">Тип компании</label>
            <div class="col-sm-10">
                <select name="type_company" id="type_company" class="form-control select2">
                    <option value="ИП">ИП</option>
                    <option value="ООО">ООО</option>
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label for="checking_account" class="col-sm-2 col-form-label">Расчетный счет</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="checking_account" id="checking_account"
                       value="{{ old('checking_account', '') }}">
            </div>
        </div>

        <div class="form-group row">
            <label for="ks" class="col-sm-2 col-form-label">к/с</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="ks" id="ks" value="{{ old('ks', '') }}">
            </div>
        </div>

        <div class="form-group row">
            <label for="inn" class="col-sm-2 col-form-label">ИНН</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="inn" id="inn" value="{{ old('inn', '') }}">
            </div>
        </div>

        <div class="form-group row">
            <label for="kpp" class="col-sm-2 col-form-label">КПП</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="kpp" id="kpp" value="{{ old('kpp', '') }}">
            </div>
        </div>

        <div class="form-group row">
            <label for="bik" class="col-sm-2 col-form-label">БИК</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="bik" id="bik" value="{{ old('bik', '') }}">
            </div>
        </div>

        <div class="form-group row">
            <label for="bank_name" class="col-sm-2 col-form-label">Наименование банка</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="bank_name" id="bank_name"
                       value="{{ old('bank_name', '') }}">
            </div>
        </div>

        {{--<div class="form-group">--}}
        {{--<label for="bank_account">Расчетный счет банка</label>--}}
        {{--<input type="text" class="form-control" name="bank_account" id="bank_account" value="{{ old('bank_account', '') }}">--}}
        {{--</div>--}}

        <div class="form-group row">
            <label for="ur_address" class="col-sm-2 col-form-label">Юридический адрес</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="ur_address" id="ur_address"
                       value="{{ old('ur_address', '') }}">
            </div>
        </div>

        <div class="form-group row">
            <label for="fact_address" class="col-sm-2 col-form-label">Фактический адрес</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="fact_address" id="fact_address"
                       value="{{ old('fact_address', '') }}">
            </div>
        </div>

        <div class="form-group row">
            <label for="mail_address" class="col-sm-2 col-form-label">Почтовый адрес</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="mail_address" id="mail_address"
                       value="{{ old('mail_address', '') }}">
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
