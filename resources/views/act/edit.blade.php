@extends('layouts.app')

@section('content')

    <h1>Редактирование акта</h1>

    <form action="{{ route('act.update', ['token' => $company->token, 'id' => $act->id]) }}" method="POST" enctype="multipart/form-data">
    @csrf

        <div class="form-group">
            <label for="category">Номер счета</label>
            @foreach($act->actService as $key => $account_number)
                <input class="form-control" name="section[<?php echo 1+$key?>][name]" type="text" value="{{$account_number->income_id}}">
            @endforeach
        </div>

        <div class="form-group">
            <input type="hidden" class="form-control" name="client" id="client" value="{{  $act->client }}">
        </div>

        <div class="form-group">
            <input type="hidden" class="form-control" name="pay" id="pay" value="{{ $act->pay_service }}">
        </div>

        <table class="product-table" id="section-service">
            <tr>
                <td style="text-align: center">№</td>
                <td style="text-align: center">Наименование</td>
                <td style="text-align: center">Количество</td>
                <td style="text-align: center">Цена</td>
            </tr>
            @foreach($act->actProducts as $key => $actProducts)
            <tr id="tr-service1">
                <td class="product-number">{{$key+1}}</td>
                <td class="product-name"><input type="text" class="form-control" name="product[{{$key+1}}][name]" id="service" value="{{$actProducts->product }}" required></td>
                <td class="product-count"><input type="number" class="form-control" name="product[{{$key+1}}][count]" id="count" value="{{$actProducts->count }}" step="1" required></td>
                <td class="product-price"><input type="number" class="form-control" name="product[{{$key+1}}][price]" id="price" value="{{ $actProducts->price }}" step="0.01" required></td>
            </tr>
            @endforeach

        </table>

        <div class="form-group">
            <label for="income_date">Дата акта</label>
            <input type="text" class="form-control date-picker" name="act_date" id="act_date" value="{{ $act->data->format('d-m-Y') }}">
        </div>

        <div class="form-group">
            <label for="is_payed">Подписан</label>
            <input type="checkbox" name="is_payed" id="is_payed" value="1">
        </div>

        <div class="form-group">
            <label for="comment">Сумма</label>
            <input type="text" class="form-control" name="sum" id="sum" value="{{ $act->summa }}">
        </div>

        <div class="form-group">
            <label for="comment">Номер акта</label>
            <input type="text" class="form-control" name="number" id="service" value="{{$act->number }}">
        </div>



        <div class="form-group">
            <button class="btn btn-success" type="submit">
                Сохранить
            </button>
            <a href="#" onclick="history.back();" class="btn btn-danger">Отмена</a>
        </div>

    </form>

@endsection