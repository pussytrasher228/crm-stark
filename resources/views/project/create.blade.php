@extends('layouts.app')

@section('content')

    <h1>Новый проект</h1>
    <hr>

    <form action="{{ route('project.store', ['token' => $company->token]) }}" method="POST"
          enctype="multipart/form-data">
        @csrf

        <div class="form-group row">
            <label for="sum" class="col-sm-2 col-form-label">Название проекта</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="name" id="name" value="{{ old('name', '') }}" required>
            </div>
        </div>

        <div class="form-group row">
            <label for="plan_income" class="col-sm-2 col-form-label">Планируемый доход</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="plan_income" id="plan_income"
                       value="{{ old('name', '') }}" required>
            </div>
        </div>

        <div class="form-group row">
            <label for="plan_expense" class="col-sm-2 col-form-label">Планируемый расход</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="plan_expense" id="plan_expense"
                       value="{{ old('name', '') }}" required>
            </div>
        </div>

        <div class="form-group row">
            <label for="comment" class="col-sm-2 col-form-label">Описание</label>
            <div class="col-sm-10">
                <textarea style="height: 150px" type="text" class="form-control" name="description" id="description"
                          value="{{ old('description', '') }}"></textarea>
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
