@extends('layouts.app')

@section('content')

    <h1>Редактировать название компании</h1>
    <hr>

    <form action="{{ route('company.update', $company) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group row">
            <label for="account_number" class="col-sm-2 col-form-label">Название компании</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="company_name" id="company_name"
                       value="{{ $company->name }}">
            </div>
        </div>

        <div class="form-group row">
            <label for="account_number" class="col-sm-2 col-form-label">Сайт</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="company_site" id="company_site"
                       value="{{ $company->site }}">
            </div>
        </div>

        <div class="form-group row">
            <label for="account_number" class="col-sm-2 col-form-label">Телефон</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="company_phone" id="company_phone"
                       value="{{ $company->phone }}">
            </div>
        </div>

        <div class="form-group row">
            <label for="account_number" class="col-sm-2 col-form-label">Email</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="company_email" id="company_email"
                       value="{{ $company->email }}">
            </div>
        </div>

        <div class="form-group row">
            <label for="account_number" class="col-sm-2 col-form-label">Адрес</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="company_addres" id="company_addres"
                       value="{{ $company->addres }}">
            </div>
        </div>

        <div class="form-group row">
            <label for="account_number" class="col-sm-2 col-form-label">Директор</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="direct" id="direct" value="{{ $company->direct }}">
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
