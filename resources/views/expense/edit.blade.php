@extends('layouts.app')

@section('content')

    <h1>Редактирование расхода</h1>

    <form action="{{ route('expense.update', ['token' => $company->token, 'id' => $expense->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="category">Категория</label>
            <select name="category" id="category" class="form-control">
                @foreach ($categories as $category)
                    <optgroup label="{{ $category->name }}">
                        @foreach ($category->childs as $childCategory)
                            @if ($childCategory->disabled)
                                @continue
                            @endif
                            <option value="{{ $childCategory->id }}" {{ $childCategory->id == $expense->category ? 'selected' : '' }}>{{$childCategory->parent->name}} / {{ $childCategory->name }}</option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="category">Проект</label>
            <select name="project_id" id="project_id" class="form-control select2 clients" >
                <option value="">Без проекта</option>
                @foreach ($company->projects as $project)
                    <option  value="{{ $project->id }}" {{ $project->id == $expense->id_project ? 'selected' : '' }}>{{ $project->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="sum">Сумма</label>
            <input type="number" class="input-group form-control" name="sum" id="sum" value="{{ $expense->sum }}">
        </div>

        <div class="form-group">
            <label for="user">Оплата</label>
            <select name="user" id="user" class="form-control">
                @foreach ($company->payServices as $payService)
                    <option value="{{ $payService->name }}" {{ $expense->user == $payService->name ? 'selected' : '' }}>{{ $payService->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="comment">Описание</label>
            <input type="text" class="input-group form-control" name="comment" id="comment" value="{{ $expense->comment }}">
        </div>

        <div class="form-group">
            <label for="date">Дата</label>
            <input type="text" class="input-group form-control date-picker" name="date" id="date" value="{{ $expense->expense_date->format('d-m-Y') }}">
        </div>

        <div class="form-group">
            <button class="btn btn-success" type="submit">
                Сохранить
            </button>
            <a href="#" onclick="history.back();" class="btn btn-danger">Отмена</a>
        </div>

    </form>

@endsection
