@extends('layouts.app')

@section('content')

    <h1>Изменить категорию</h1>
    <hr>

    <form action="{{ route('category.update', ['id' => $category->id, 'token' => $company->token]) }}" method="POST"
          enctype="multipart/form-data">
        @csrf

        <div class="form-group row">
            <label for="name" class="col-sm-2 col-form-label">Название</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="name" id="name" value="{{ $category->name }}">
            </div>
        </div>

        @if ($category->parent)
            <div class="form-group row">
                <label for="parent" class="col-sm-2 col-form-label">Родительская категория</label>
                <div class="col-sm-10">
                    <select name="parent" id="parent" class="form-control">
                        @foreach ($categories as $parentCategory)
                            <option
                                value="{{ $parentCategory->id }}" {{ $category->parent->id == $parentCategory->id ? 'selected' : '' }}>
                                {{ $parentCategory->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label for="disabled" class="col-sm-2 col-form-label">Отключена</label>
                <div class="col-sm-10">
                    <div class="checkbox checkbox-success checkbox-fill d-inline">
                        <input type="checkbox" name="disabled"
                               id="disabled" {{ $category->disabled ? 'checked' : '' }}>
                        <label for="disabled" class="cr"></label>
                    </div>
                </div>
            </div>
        @endif

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
