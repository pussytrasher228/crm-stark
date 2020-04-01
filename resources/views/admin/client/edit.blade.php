@extends('layouts.app')

@section('content')

    <h1>Редактирование {{ $client->name }}</h1>

    <form action="{{ route('client.update', ['id' => $client->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="name">Наименование</label>
            <input type="text" class="form-control" name="name" id="name" value="{{ $client->name }}" required>
        </div>

        <div class="form-group">
            <label for="name">Ссылка на сайт</label>
            <input type="text" class="form-control" name="syte" id="syte" value="{{  $client->syte }}">
        </div>
        @can('admin')
        <div class="form-group">
            <label for="disabled">Отключена</label>
            <input type="checkbox" name="disabled" id="disabled" {{ $client->disabled ? 'checked' : '' }}>
        </div>
        @endcan

        <div class="form-group">
            <button class="btn btn-success" type="submit">
                Сохранить
            </button>
            <a href="#" onclick="history.back();" class="btn btn-danger">Отмена</a>
        </div>

    </form>

@endsection
