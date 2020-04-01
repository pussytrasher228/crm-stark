@extends('test.layouts')

@section('content')
    <div class="head">
    <div class="filtrs">
       <div class="container">
           <h1 style="text-align: center">Фильтры</h1>
           <div class="row">
               <div class="col-md-3">
                   <input type="text" class="form-control date-picker" name="plan_date" id="plan_date" value="{{ old('plan_date') }}">
               </div>
               <div class="col-md-3">
                   <input type="text" class="form-control date-picker" name="plan_date" id="plan_date" value="{{ old('plan_date') }}">
               </div>
               <div class="col-md-3">
                   <input type="text" class="form-control date-picker" name="plan_date" id="plan_date" value="{{ old('plan_date') }}">
               </div>
               <div class="col-md-3">
                   <input type="text" class="form-control date-picker" name="plan_date" id="plan_date" value="{{ old('plan_date') }}">
               </div>
           </div>
           <div class="row" style="padding-top: 20px">
               <div class="col-md-3">
                   <input type="text" class="form-control date-picker" name="plan_date" id="plan_date" value="{{ old('plan_date') }}">
               </div>
               <div class="col-md-3">
                   <input type="text" class="form-control date-picker" name="plan_date" id="plan_date" value="{{ old('plan_date') }}">
               </div>
               <div class="col-md-3">
                   <input type="text" class="form-control date-picker" name="plan_date" id="plan_date" value="{{ old('plan_date') }}">
               </div>
               <div class="col-md-3">
                   <input type="text" class="form-control date-picker" name="plan_date" id="plan_date" value="{{ old('plan_date') }}">
               </div>
           </div>
           <hr>
       </div>
    </div>
    <section class="content">
        <table class="table">
            <thead class="thead-light">
            <tr>
                <th scope="col">Добавлен</th>
                <th scope="col">Тип сделки</th>
                <th scope="col">Комнат</th>
                <th scope="col">Нас.пункт</th>
                <th scope="col">Улица</th>
                <th scope="col">Цена</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td class="align-middle">22.12.12</td>
                <td class="align-middle">Продажа</td>
                <td class="align-middle">2</td>
                <td class="align-middle">Новосибирск</td>
                <td class="align-middle">Ленина</td>
                <td class="align-middle">4000000</td>
            </tr>

            </tbody>
        </table>

    </section>
    </div>
@endsection
