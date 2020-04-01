@extends('layouts.app')

@section('content')
    <h1>Создание нового клиента</h1>
    <hr>


    <form action="{{ route('client.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group row">
            <label for="name" class="col-sm-2 col-form-label">Наименование</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="name" id="name" value="{{ old('name', '') }}" required>
            </div>
        </div>

        <div class="form-group row">
            <label for="syte" class="col-sm-2 col-form-label">Ссылка на сайт</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="syte" id="syte" value="{{ old('syte', '') }}">
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
