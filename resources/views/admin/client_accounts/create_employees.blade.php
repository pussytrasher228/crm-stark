@extends('layouts.app')

@section('content')

    <h1>Добавить сотрудника</h1>

    <form action="{{ route('employees.store', $client) }}" method="POST" enctype="multipart/form-data">
    @csrf

        <div class="form-group">
            <label for="name">ФИО</label>
            <input type="text" class="form-control" name="name" id="name" value="{{ old('name', '') }}" required>
        </div>

        <div class="form-group">
            <label for="name">Email</label>
            <input type="text" class="form-control" name="email" id="email" value="{{ old('email', '') }}">
        </div>

        <div class="form-group">
            <label for="name">Номер телефона</label>
            <input type="text" class="form-control" name="phone" id="phone" value="{{ old('phone', '') }}">
        </div>

        <div class="form-group">
            <label for="name">Должность</label>
            <input type="text" class="form-control" name="position" id="position" value="{{ old('position', '') }}">
        </div>

        <div class="form-group">
            <label for="name">Комментарий</label>
            <input type="text" class="form-control" name="comment" id="comment" value="{{ old('comment', '') }}">
        </div>

        <div class="form-group">
            <button class="btn btn-success" type="submit">
                Сохранить
            </button>
            <a href="#" onclick="history.back();" class="btn btn-danger">Отмена</a>
        </div>
    </form>


@endsection