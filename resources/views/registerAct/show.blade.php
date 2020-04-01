@extends('layouts.app')

@section('content')

    <h1>Реестр договоров</h1>
    <hr>

    <form method="GET" action="?" enctype="multipart/form-data">
        <div class="row">
            <div class="form-group col-sm-3">
                <label for="services">Получатель</label>
                <select name="pay_service" id="pay_service" class="form-control select2" onchange="this.form.submit()">
                    <option value="">Все получатели</option>
                    @foreach ($company->payServices as $payServices)
                        <option value="{{$payServices->id}}" {{ $payServices->id == $pay_servicess ? 'selected' : '' }}>{{$payServices->name}}</option>
                    @endforeach
                </select>
            </div>

        </div>
    </form>

    <div class="card">
        <div class="card-body m-0 p-0">
            <div class="d-flex justify-content-end">
                <button class="btn btn-glow-success btn-success mb-4 mt-4 mr-4"
                        onclick="location.href = '{{ route('registerAct.create', ['token' => $company->token]) }}'">
                    <i class="feather icon-plus"></i>
                    Добавить проект
                </button>
            </div>

            <table class="table table-hover table-striped">
                <thead>
                <tr>
                    <th class="align-middle">Номер</th>
                    <th class="align-middle">Дата</th>
                    <th class="align-middle">Клиент</th>
                    <th class="align-middle">Получатель</th>
                    <th class="align-middle">Комментарий</th>
                    <th class="align-middle">Действия</th>
                </tr>
                </thead>
                <tbody>
                @foreach($register as $registerAct)
                    <tr>
                        <td class="align-middle">{{$registerAct->number}}</td>
                        <td class="align-middle">{{$registerAct->date->format('d-m-Y')}}</td>
                        <td class="align-middle">{{$registerAct->normalClient->name}}</td>
                        <td class="align-middle">{{$registerAct->payService->name}}</td>
                        <td class="align-middle">{{$registerAct->comments}}</td>
                        <td class="d-flex d-row">
                            <a class="btn btn-icon btn-glow-warning btn-warning" href="{{route('registerAct.edit', ['registerAct' => $registerAct])}}">
                                <i class="feather icon-edit"></i>
                            </a>
                            <form action="{{ route('registerAct.remove', ['token' => $company->token, 'registerAct' => $registerAct]) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-icon btn-glow-danger btn-danger" onclick='return confirm("Вы уверены, то хотите удалить?")'>
                                    <i class="feather icon-trash-2"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
