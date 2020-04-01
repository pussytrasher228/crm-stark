@extends('layouts.app')

@section('content')

    <h1>Новый расход</h1>
    <hr>

    <form action="{{ route('expense.store', ['token' => $company->token]) }}" method="POST"
          enctype="multipart/form-data">
        @csrf

        <div class="form-group row">
            <label for="category" class="col-sm-2 col-form-label">Категория</label>
            <div class="col-sm-10">
                <select name="category" id="category" class="form-control">
                    <option value="" disabled selected>Выберите категорию</option>
                    @foreach ($categories as $category)
                        <optgroup label="{{ $category->name }}">
                            @foreach ($category->childs as $childCategory)
                                @if ($childCategory->disabled)
                                    @continue
                                @endif
                                <option value="{{ $childCategory->id }}">{{$childCategory->parent->name}}
                                    / {{ $childCategory->name }}</option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label for="category" class="col-sm-2 col-form-label">Проект</label>
            <div class="col-sm-10">
                <select name="project_id" id="project_id" class="form-control select2 clients">
                    <option value="">Без проекта</option>
                    @foreach ($company->projects as $project)
                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label for="sum" class="col-sm-2 col-form-label">Сумма</label>
            <div class="col-sm-10">
                <input type="number" class="form-control" name="sum" id="sum" value="{{ old('sum', '') }}">
            </div>
        </div>

        <div class="form-group row">
            <label for="user" class="col-sm-2 col-form-label">Оплата</label>
            <div class="col-sm-10">
                <select name="user" id="user" class="form-control">
                    @foreach ($company->payServices as $payService)
                        <option value="{{ $payService->name }}">{{ $payService->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label for="comment" class="col-sm-2 col-form-label">Описание</label>
            <div class="col-sm-10">
                <textarea style="height: 150px" type="text" class="form-control" name="comment" id="comment"
                          value="{{ old('comment', '') }}"></textarea>
            </div>
        </div>

        <div class="form-group row">
            <label for="date" class="col-sm-2 col-form-label">Дата</label>
            <div class="col-sm-10">
                <input type="text" class="form-control date-picker" name="date" id="date"
                       value="{{ old('date', (new \Carbon\Carbon())->setTimezone('Asia/Novosibirsk')->format('d-m-Y')) }}">
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
