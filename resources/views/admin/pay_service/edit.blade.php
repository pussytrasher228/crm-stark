@extends('layouts.app')

@section('content')

    <h1>Редактировать вариант оплаты</h1>

    <form action="{{ route('pay_service.update', $payService) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="name">Название</label>
            <input type="text" class="form-control" name="name" id="name" value="{{ $payService->name }}">
        </div>

        <div class="form-group">
            <label for="category">Тип компании</label>
            <select name="type_company" id="type_company" class="form-control select2">
                <option  value="ИП">ИП</option>
                <option  value="ИП">ООО</option>
            </select>
        </div>

        <div class="form-group">
            <label for="checking_account">Расчетный счет</label>
            <input type="text" class="form-control" name="checking_account" id="checking_account" value="{{ $payService->checking_account }}">
        </div>

        <div class="form-group">
            <label for="ks">к/с</label>
            <input type="text" class="form-control" name="ks" id="ks" value="{{ $payService->ks }}">
        </div>

        <div class="form-group">
            <label for="inn">ИНН</label>
            <input type="text" class="form-control" name="inn" id="inn" value="{{ $payService->inn }}">
        </div>

        <div class="form-group">
            <label for="kpp">КПП</label>
            <input type="text" class="form-control" name="kpp" id="kpp" value="{{ $payService->kpp }}">
        </div>

        <div class="form-group">
            <label for="kpp">БИК</label>
            <input type="text" class="form-control" name="bik" id="bik" value="{{ $payService->bik }}">
        </div>

        <div class="form-group">
            <label for="bank_name">Наименование банка</label>
            <input type="text" class="form-control" name="bank_name" id="bank_name" value="{{ $payService->bank_name }}">
        </div>

        {{--<div class="form-group">--}}
            {{--<label for="bank_account">Расчетный счет банка</label>--}}
            {{--<input type="text" class="form-control" name="bank_account" id="bank_account" value="{{ $payService->bank_account }}">--}}
        {{--</div>--}}

        <div class="form-group">
            <label for="ur_address">Юридический адрес</label>
            <input type="text" class="form-control" name="ur_address" id="ur_address" value="{{ $payService->ur_address }}">
        </div>

        <div class="form-group">
            <label for="fact_address">Фактический адрес</label>
            <input type="text" class="form-control" name="fact_address" id="fact_address" value="{{ $payService->fact_address }}">
        </div>

        <div class="form-group">
            <label for="mail_address">Почтовый адрес</label>
            <input type="text" class="form-control" name="mail_address" id="mail_address" value="{{ $payService->mail_address }}">
        </div>

        <div class="form-group">
            <button class="btn btn-success" type="submit">
                Сохранить
            </button>
            <a href="#" onclick="history.back();" class="btn btn-danger">Отмена</a>
        </div>

    </form>

@endsection
