@extends('layouts.app')

@section('content')

    <h1>Проекты:</h1>
    <hr>

    <div class="card">
        <div class="card-body pl-0 pr-0 pt-0">

            <div class="d-flex justify-content-end">
                <button class="btn btn-glow-success btn-success mb-4 mt-4 mr-4"
                        onclick="location.href = '{{ route("project.create", ["token" => $company->token]) }}'">
                    <i class="feather icon-plus"></i>
                    Добавить проект
                </button>
            </div>

            <div class="table-responsive">

                <table class="table table-hover table-striped">
                    <thead>

                    <tr>
                        <th width="10%">Название проекта</th>
                        <th width="10%">Планируемый доход</th>
                        <th width="10%">Планируемый расход</th>
                        <th width="10%">Доход</th>
                        <th width="10%">Расход</th>
                        <th width="10%">Действия</th>
                    </tr>

                    </thead>
                    <tbody>
                    @php $iterator = 0; @endphp
                    @foreach($company->projects as $project)
                        <tr>
                            <td class="align-middle">
                                <a href="{{route('project.detals', ['token' => $company->token, 'project' => $project])}}">{{$project->name}}</a>
                            </td>
                            <td class="align-middle">
                                {{$project->plan_income}}
                            </td>
                            <td class="align-middle">
                                {{$project->plan_expense}}
                            </td>
                            <td class="align-middle">
                                {{$project->fact_income}}
                            </td>
                            <td class="align-middle">
                                {{$project->fact_expense}}
                            </td>
                            <td class="align-middle d-flex d-row">
                                <a class="btn btn-icon btn-glow-warning btn-warning"
                                   href="{{ route('project.edit', ['token' => $company->token, 'project' => $project]) }}">
                                    <i class="feather icon-edit"></i>
                                </a>
                                <form
                                    action="{{ route('project.remove', ['token' => $company->token, 'project' => $project]) }}"
                                    method="POST">
                                    @csrf
                                    <button class="btn btn-icon btn-glow-danger btn-danger" type="submit"
                                            onclick="return confirm('Вы уверены, то хотите удалить проект?')">
                                        <i class="feather icon-trash-2"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @php $iterator++; @endphp
                        @if ($iterator >= 999999)
                            @break
                        @endif
                    @endforeach
                    </tbody>

                </table>
            </div>
        </div>
    </div>

@endsection
