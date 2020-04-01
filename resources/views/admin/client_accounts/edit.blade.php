@extends('layouts.app')

@section('content')

    <h1>Редактирование {{ $clientAccount->name }}</h1>

    <form action="{{ route('client_account.update', $clientAccount) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="name">Название юридического лица</label>
            <input type="text" class="form-control" name="name" id="name" value="{{ $clientAccount->name }}" required>
        </div>

        <div class="form-group">
            <label for="checking_account">Расчетный счет</label>
            <input type="text" class="form-control" name="checking_account" id="checking_account" value="{{ $clientAccount->checking_account }}">
        </div>

        <div class="form-group">
            <label for="ks">к/с</label>
            <input type="text" class="form-control" name="ks" id="ks" value="{{ $clientAccount->ks }}">
        </div>

        <div class="form-group">
            <label for="inn">ИНН</label>
            <input type="text" class="form-control" name="inn" id="inn" value="{{ $clientAccount->inn }}">
        </div>

        <div class="form-group">
            <label for="kpp">КПП</label>
            <input type="text" class="form-control" name="kpp" id="kpp" value="{{ $clientAccount->kpp }}">
        </div>

        <div class="form-group">
            <label for="kpp">БИК</label>
            <input type="text" class="form-control" name="bik" id="bik" value="{{ $clientAccount->bik }}">
        </div>

        <div class="form-group">
            <label for="bank_name">Наименование банка</label>
            <input type="text" class="form-control" name="bank_name" id="bank_name" value="{{ $clientAccount->bank_name }}">
        </div>

        <div class="form-group">
            <label for="ur_address">Юридический адрес</label>
            <input type="text" class="form-control" name="ur_address" id="ur_address" value="{{ $clientAccount->ur_address }}">
        </div>

        <div class="form-group">
            <label for="fact_address">Фактический адрес</label>
            <input type="text" class="form-control" name="fact_address" id="fact_address" value="{{ $clientAccount->fact_address }}">
        </div>

        <div class="form-group">
            <label for="mail_address">Почтовый адрес</label>
            <input type="text" class="form-control" name="mail_address" id="mail_address" value="{{ $clientAccount->mail_address }}">
        </div>

        <div class="form-group">
            <button class="btn btn-success" type="submit">
                Сохранить
            </button>
            <a href="#" onclick="history.back();" class="btn btn-danger">Отмена</a>
        </div>

    </form>

@endsection
