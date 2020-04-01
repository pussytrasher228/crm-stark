@extends('layouts.app')

@section('content')

    <h1>Редактировать {{$project->name}}</h1>

    <form action="{{ route('project.update', ['token' => $company->token, 'project' => $project]) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="sum">Название проекта</label>
            <input type="text" class="form-control" name="name" id="name" value="{{$project->name}}" required>
        </div>

        <div class="form-group">
            <label for="sum">Планируемый доход</label>
            <input type="text" class="form-control" name="plan_income" id="plan_income" value="{{$project->plan_income}}" required>
        </div>

        <div class="form-group">
            <label for="sum">Планируемый расход</label>
            <input type="text" class="form-control" name="plan_expense" id="plan_expense" value="{{ $project->plan_expense }}" required>
        </div>

        <div class="form-group">
            <label for="comment">Описание</label>
            <textarea style="height: 150px" type="text" class="form-control" name="description" id="description" value="{{ $project->description }}"></textarea>
        </div>

        <div class="form-group">
            <button class="btn btn-success" type="submit">
                Сохранить
            </button>
            <a href="#" onclick="history.back();" class="btn btn-danger">Отмена</a>
        </div>

    </form>

@endsection
