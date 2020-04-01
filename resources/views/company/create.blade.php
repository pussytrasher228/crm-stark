@extends('layouts.app')

@section('content')

    <form action="{{ route('companies.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="name">Название компании</label>
            <input type="text" class="form-control" name="name" id="name">

            <div class="form-group">
                <button class="btn btn-success" type="submit">
                    Сохранить
                </button>
                <a href="#" onclick="history.back();" class="btn btn-danger">Отмена</a>
            </div>
        </div>

    </form>

@endsection
