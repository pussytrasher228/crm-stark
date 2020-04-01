@extends('layouts.app')

@section('content')

    <h1>План продаж</h1>
    <hr>

    <form action="{{ route('incomePlans.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead>
                        <tr>
                            <th class="align-middle">Месяц</th>
                            <th class="align-middle">План продаж</th>
                        </tr>
                        @foreach($mounth as $key => $mount)
                            <tr>
                                <td class="align-middle">
                                    {{$mount}}
                                </td>
                                <td class="align-middle">
                                    <input type="text" class="form-control" name="mounth[{{$key}}][plan]" id="mounth"
                                           value="{{ old('mounth', '') }}">
                                    <input type="hidden" name="mounth[{{$key}}][mounth_name]" id="mounth_name"
                                           value="{{$mount}}">
                                    <input type="hidden" name="mounth[{{$key}}][yars]" id="yars"
                                           value="{{date( 'Y' )}}">
                                </td>
                            </tr>
                        @endforeach
                        </thead>
                        <tbody>
                    </table>
                </div>
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
