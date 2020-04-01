@extends('layouts.app')

@section('content')

    <h1>Новая услуга</h1>
    <hr>

    <form action="{{ route('service.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group row">
            <label for="name" class="col-sm-2 col-form-label">Услуга</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="name" id="name" value="{{ old('name', '') }}">
            </div>
        </div>

        <div class="form-group row">
            <label for="income" class="col-sm-2 col-form-label">Учавствует в доходах</label>
            <div class="col-sm-10">
                <div class="checkbox checkbox-success checkbox-fill d-inline">
                    <input type="checkbox" name="income" id="income"
                           value="{{ old('disabled', '1') }}" checked>
                    <label for="income" class="cr"></label>
                </div>
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
