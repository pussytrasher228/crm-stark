@extends('layouts.app')

@section('content')

    <h1>Редактирование {{ $service->name }}</h1>

    <form action="{{ route('service.update', ['id' => $service->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="name">Наименование</label>
            <input type="text" class="form-control" name="name" id="name" value="{{ $service->name }}" required>
        </div>
        @can('admin')
            <div class="form-group">
                <label for="disabled">Отключена</label>
                <input type="checkbox" name="disabled" id="disabled" {{ $service->disabled ? 'checked' : '' }}>
            </div>

            <div class="form-group">
                <label for="income">Учавствует в доходах</label>
                <input type="checkbox" name="income" id="income" {{ $service->income ? 'checked' : '' }}>
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

