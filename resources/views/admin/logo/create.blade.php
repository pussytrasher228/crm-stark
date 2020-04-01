@extends('layouts.app')

@section('content')

    <h1>Загрузка логотипа</h1>
    <hr>

    <form action="{{ route('logo.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group row">
            <label for="logo" class="col-sm-2 col-form-label">Логотип</label>
            <div class="col-sm-10">
                <div class="custom-file">
                    <input type="file" class="custom-file-input" name="logo" id="logo" required>
                    <label class="custom-file-label" for="logo">Выберите логотип...</label>
                    <div class="invalid-feedback">Логотип не выбран!</div>
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
