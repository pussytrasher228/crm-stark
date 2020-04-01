@extends('layouts.app')

@section('content')

    <h1>Новая категория</h1>
    <hr>

    <form action="{{ route('category.store', ['token' => $company->token]) }}" method="POST"
          enctype="multipart/form-data">
        @csrf

        <div class="form-group row">
            <label for="name" class="col-sm-2 col-form-label">Название</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="name" id="name" value="{{ old('name', '') }}">
            </div>
        </div>

        <div class="form-group row">
            <label for="parent" class="col-sm-2 col-form-label">Родительская категория</label>
            <div class="col-sm-10">
                <select name="parent" id="parent" class="form-control">
                    <option value=""></option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
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

        <hr>

        <div class="card">
            <div class="card-body pl-0 pr-0 pt-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead>
                        <tr>
                            <th class="align-middle">Родительская категория</th>
                            <th class="align-middle">Название</th>
                            <th class="align-middle">Действия</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($categories as $category)
                            <tr>
                                <th class="align-middle">Нет</th>
                                <th class="align-middle">{{ $category->name }}</th>
                                <th class="align-middle d-flex d-row">
                                    <a class="btn btn-icon btn-glow-warning btn-warning"
                                       href="{{ route('category.edit', ['id' => $category->id, 'token' => $company->token]) }}">
                                        <i class="feather icon-edit"></i>
                                    </a>
                                </th>
                            </tr>
                            @foreach ($category->childs as $childCategory)
                                <tr>
                                    <td class="align-middle">{{ $childCategory->parent->name}}</td>
                                    <td class="align-middle">{{ $childCategory->name }}</td>
                                    <td class="align-middle d-flex d-row">
                                        <a class="btn btn-icon btn-glow-warning btn-warning"
                                           href="{{ route('category.edit', ['id' => $childCategory->id, 'token' => $company->token]) }}">
                                            <i class="feather icon-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </form>

@endsection
