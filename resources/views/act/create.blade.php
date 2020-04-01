@extends('layouts.app')

@section('content')

    <h1>Новый акт</h1>
    <hr>

    <form action="{{ route('act.store', ['token' => $company->token]) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="card">
            <div class="card-header">
                Номер счета
            </div>
            <div class="card-body pl-0 pr-0 pt-0 pb-0" id="data-list">
                <div class="p-3" id="div">
                    <input autocomplete="off" class="list-section1 form-control mb-2" name="section[1][name]" sum=""
                           placeholder="Поиск нужного счета" list="section">
                </div>
                <datalist id="section">
                    @foreach($company->noActiveAct() as $incom)
                        <option class="section-item" data-product="{{$incom->products}}" data-income_id="{{$incom->id}}"
                                data-client_cheking_accounts="{{$incom->client_cheking_accounts}}"
                                data-pay_service="{{$incom->pay_service}}" data-sum="{{$incom->sum}}"
                                value="{{$incom->account_number}}"
                                data-service="{{$incom->service}}">{{$incom->service}}</option>
                    @endforeach
                </datalist>
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-glow-success btn-success m-3" id="buttonAddAct">
                        <i class="feather icon-plus"></i>
                        Добавить
                    </button>
                </div>
            </div>
        </div>

        <script>
            var sum = 0;

            var count = 1;

            $(".list-section" + count).on('input', function (e) {
                sum = 0;
                var val = $(this).val();
                var datalist = $(this).parent().next();
                var selected = datalist.find('[value="' + val + '"]');

                if (selected.length > 0) {
                    $("input[name='section[1][name]']").each(function () {
                        console.log($(this).val());
                        if ($(this).val().length > 0) {
                            optionSum = $('option[value=' + $(this).val() + ']').data('sum');
                            console.log(optionSum);
                            sum += optionSum;
                        }
                    });

                    var service = selected.data('service');
                    var client = selected.data('client_cheking_accounts');
                    var pay = selected.data('pay_service');
                    var income_id = selected.data('income_id');
                    var productObj = selected.data('product');
                    $.each(productObj, function (key, value) {
                        document.getElementById('service').value = value.product;
                        document.getElementById('count').value = value.count;
                        document.getElementById('price').value = value.price;
                    });
                }
                console.log(sum);
                document.getElementById('sum').value = sum;
                document.getElementById('client').value = client;
                document.getElementById('pay').value = pay;
                document.getElementById('income_id').value = income_id;
            });

            count = 2;

            buttonAddAct.addEventListener("click", function () {
                var input = `
<input type="text" class="form-control list-section` + count + ` mb-2" name="section[` + count + `][name]" placeholder="Поиск нужного счета" autocomplete="off" list="section` + count + `">
                `;

                $('#div').append(input);

                var datalist = document.createElement('DATALIST');
                datalist.id = ("id", "section" + count);
                document.querySelector('#data-list').appendChild(datalist);

                    @foreach($company->noActiveAct() as $incom)
                var option = document.createElement('OPTION');
                option.value = ('{{$incom->account_number}}');
                option.text = ('{{$incom->service}}');
                option.className = "section-item";
                option.setAttribute('data-sum', '{{$incom->sum}}');
                option.setAttribute('data-service', '{{$incom->service}}');
                option.setAttribute('data-client_cheking_accounts', '{{$incom->client_cheking_accounts}}');
                option.setAttribute('data-pay_service', '{{$incom->pay_service}}');
                option.setAttribute('data-income_id', '{{$incom->id}}');
                document.querySelector('#section' + count).appendChild(option);
                @endforeach

                $(".list-section" + count).on('input', function (e) {
                    var val = $(this).val();
                    var datalist = $(this).parent().next();
                    var selected = datalist.find('[value="' + val + '"]');
                    var key = parseInt(count) - parseInt(1);
                    if (selected.length > 0) {
                        $("input[name='section[" + key + "][name]']").each(function () {
                            console.log($(this).val());
                            if ($(this).val().length > 0) {
                                optionSum = $('option[value=' + $(this).val() + ']').data('sum');
                                console.log(optionSum);
                                sum += optionSum;
                            }
                        });

                        var service = selected.data('service');
                        var client = selected.data('client_cheking_accounts');
                        var pay = selected.data('pay_service');
                        var income_id = selected.data('income_id');

                    }
                    console.log(sum);
                    document.getElementById('service').value = service;
                    document.getElementById('sum').value = sum;
                    document.getElementById('client').value = client;
                    document.getElementById('pay').value = pay;
                    document.getElementById('income_id').value = income_id;
                });
                count++;
            });

        </script>

        <div class="form-group">
            <input type="hidden" class="form-control" name="income_id" id="income_id" value="{{ old('id', '') }}">
        </div>
        <div class="form-group">
            <input type="hidden" class="form-control" name="client" id="client" value="{{ old('service', '') }}">
        </div>

        <div class="form-group">
            <input type="hidden" class="form-control" name="pay" id="pay" value="{{ old('service', '') }}">
        </div>


        <div class="card mt-5 mb-5">
            <div class="card-body pl-0 pr-0 pt-0 pb-0">
                <div class="form-group">
                    <div class="table-responsive">
                        <table class="table product-table">
                            <thead>
                            <tr>
                                <th class="text-left">Наименование</th>
                                <th class="text-left">Количество</th>
                                <th class="text-left">Цена</th>
                                <th class="text-center"></th>
                            </tr>
                            </thead>
                            <tbody id="section-service">
                            <tr>
                                <td class="align-middle product-name">
                                    <input type="text" class="form-control" name="product[1][name]" id="product"
                                           value="{{ old('product[]') }}" required>
                                </td>
                                <td class="align-middle product-count">
                                    <input type="number" class="form-control" name="product[1][count]"
                                           id="count" value="1" step="1" required>
                                </td>
                                <td class="align-middle product-price">
                                    <input type="number" class="form-control" name="product[1][price]"
                                           id="price" value="{{ old('price[]', '') }}" step="0.01" required>
                                </td>
                                <td class="align-middle text-center">
                                    <button href="javascript:\/\/" onclick="deleteItem(this)"
                                            class="remove btn btn-icon btn-glow-danger btn-danger">
                                        <i class="feather icon-trash-2"></i>
                                    </button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-glow-success btn-success mb-3 mr-3" id="buttonAdd">
                        <i class="feather icon-plus"></i>
                        Добавить
                    </button>
                </div>
            </div>
        </div>


        <script>
            var count = 2;
            var counts = 1;
            var del = 'delete' + counts;
            buttonAdd.addEventListener("click", function () {

                let row = `
<tr id="tr-service` + count + `" class="delete` + count + `">
    <td id="td-name` + count + `" class="align-middle">
        <input class="form-control" type="text" id="name` + count + `" name="product[` + count + `][name]">
    </td>
    <td id="td-count` + count + `" class="align-middle">
        <input class="form-control" type="text" id="count` + count + `" name="product[` + count + `][count]">
    </td>
    <td id="td-price` + count + `" class="align-middle">
        <input class="form-control" type="text" id="price` + count + `" name="product[` + count + `][price]">
    </td>
    <td class="text-center align-middle">
        <button href="javascript:\/\/" onclick="deleteItem(this)" class="remove btn btn-icon btn-glow-danger btn-danger">
            <i class="feather icon-trash-2"></i>
        </button>
    </td>
</tr>`;
                $('#section-service').append(row);

                count++;
            });
            $('html').on('click', '.remove', function () {
                $(this).parent().parent().remove();
            });
        </script>


        <div class="form-group row">
            <label for="income_date" class="col-sm-2 col-form-label">Дата акта</label>
            <div class="col-sm-10">
                <input type="text" class="form-control date-picker" name="date" id="date"
                       value="{{ old('date', (new \Carbon\Carbon())->setTimezone('Asia/Novosibirsk')->format('d-m-Y')) }}">
            </div>
        </div>

        <div class="form-group row">
            <label for="is_payed" class="col-sm-2 col-form-label">Подписан</label>
            <div class="col-sm-10">
                <div class="checkbox checkbox-success checkbox-fill d-inline">
                    <input type="checkbox" name="is_payed" id="is_payed" value="{{ old('is_payed', '1') }}" checked>
                    <label for="is_payed" class="cr"></label>
                </div>
            </div>
        </div>

        <div class="form-group row">
            <label for="comment" class="col-sm-2 col-form-label">Сумма</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="sum" id="sum" value="{{ old('service', '') }}">
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
