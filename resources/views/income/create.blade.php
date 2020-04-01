@extends('layouts.app')

@section('content')

    <h1>Новая оплата</h1>
    <hr>
    <form action="{{ route('income.store', ['token' => $company->token]) }}" method="POST"
          enctype="multipart/form-data">
        @csrf

        <div class="form-group row">
            <label for="category" class="col-sm-2 col-form-label">Клиент</label>
            <div class="col-sm-10">
                <select name="category" id="category" class="form-control select2 clients">
                    @foreach ($company->activeClients() as $client)
                        <option value="{{ $client->id }}">{{ $client->name }}</option>
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
            <label for="client_cheking_accounts" class="col-sm-2 col-form-label">Юр. лицо</label>
            <div class="col-sm-10">
                <select name="client_cheking_accounts" id="client_cheking_accounts" class="form-control select2">
                </select>
            </div>
        </div>

        <script>

            function load() {
                $('.optionChek').each(function (i, el) {
                    $(el).remove();
                });
                $.ajax({
                    url: "{{route('income.getClientCheckingAccount')}}",
                    data: {"value": $("#category").val(), '_token': '{{csrf_token()}}'},
                    type: "post",
                    success: function (data) {
                        $.each(data, function (index, value) {
                            console.log(value.name);
                            var option = document.createElement('OPTION');
                            option.value = (value.id);
                            option.textContent = (value.name);
                            option.className = 'optionChek';
                            document.querySelector('#client_cheking_accounts').appendChild(option);
                        });
                    }
                });
            }

            /*
             * Люди, не используйте JQuery, прошу, лучше уж написать пару строк на ванильном JS,
             * Нежели разгребать за вами старый код.
             * Пофикшено: Если юзать JQ, то не работает $(document).load() какого-то черта.
             */

            document.addEventListener("DOMContentLoaded", function () {
                load();

                // Там оказывается select2 на JQ завязан. Супер! (через ванильный onChange работать НЕ БУДЕТ)
                /*
                 * Exorcizo te, immundissime spiritus, omnis incursio adversarii, omne phantasma,
                 * omnis legio, in nomine Domini nostri Jesu Christi eradicare, et effugare ab hoc plasmate Dei.
                 * Ipse tibi imperat, qui te de supernis caelorum in inferiora terrae demergi praecepit.
                 * Ipse tibi imperat, qui mari, ventis, et tempestatibus impersvit.
                 * Audi ergo, et time, satana, inimice fidei, hostis generis humani, mortis adductor, vitae raptor,
                 * justitiae declinator, malorum radix, fomes vitiorum, seductor hominum, proditor gentium, incitator invidiae,
                 * origo avaritiae, causa discordiae, excitator dolorum: quid stas, et resistis, cum scias.
                 * Christum Dominum vias tuas perdere? Illum metue, qui in Isaac immolatus est, in joseph venumdatus,
                 * in sgno occisus, in homine cruci- fixus, deinde inferni triumphator fuit.
                 * Sequentes cruces fiant in fronte obsessi. Recede ergo in nomine Patris et Filii,
                 * et Spiritus Sancti: da locum Spiritui Sancto, per hoc signum sanctae Cruci Jesu Christi Domini nostri:
                 * Qui cum Patre et eodem Spiritu Sancto vivit et regnat Deus, Per omnia saecula saeculorum. Et cum spiritu tuo. Amen.
                 */
                $('#category').on("change", function (e) {
                    load();
                });

                // document.getElementById("category").addEventListener("change", function () {
                //     load();
                // });
            });

        </script>

        <div class="form-group row">
            <label for="comment" class="col-sm-2 col-form-label">Комментарий</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="comment" id="comment" value="{{ old('comment', '') }}">
            </div>
        </div>

        <div class="form-group row">
            <label for="service" class="col-sm-2 col-form-label">Услуга</label>
            <div class="col-sm-10">
                <div id="services">
                    <select name="service" id="service" class="form-control">
                        @foreach ($company->activeServices() as $service)
                            <option value="{{ $service->id }}">{{ $service->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="form-group row">
            <label for="pay_service" class="col-sm-2 col-form-label">Получатель</label>
            <div class="col-sm-10">
                <select name="pay_service" id="pay_service" class="form-control">
                    @foreach ($company->payServices as $payService)
                        <option value="{{ $payService->id }}">{{ $payService->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="card mt-5 mb-5">
            <div class="card-body pl-0 pr-0 pt-0 pb-0">
                <div class="form-group">
                    <div class="table-responsive">
                        <table class="table product-table">
                            <thead>
                            <tr>
                                <th width="60%" class="text-left">Наименование</th>
                                <th width="15%" class="text-left">Количество</th>
                                <th width="15%" class="text-left">Цена</th>
                                <th width="10%" class="text-center"></th>
                            </tr>
                            </thead>
                            <tbody id="section-service">
                            <tr>
                                <td class="align-middle product-name">
                                    <input type="text" class="form-control" name="product[1][name]" id="product"
                                           value="{{ old('product[]') }}" required>
                                </td>
                                <td class="align-middle product-count">
                                    <input
                                        type="number" class="form-control" name="product[1][count]"
                                        id="count" value="1" step="1"
                                        oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                        max="999" maxlength="3"
                                        required>
                                </td>
                                <td class="align-middle product-price">
                                    <input
                                        type="number" class="form-control" name="product[1][price]"
                                        id="price" value="{{ old('price[]', '') }}"
                                        step="0.01" max="1000000"
                                        oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                        maxlength="7"
                                        required>
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

                <div class="d-flex justify-content-start">
                    <button type="button" class="btn btn-glow-success btn-success ml-3 mb-3 mr-3" id="buttonAdd">
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
        <input class="form-control" type="number" id="count` + count + `" name="product[` + count + `][count]"
            oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
            max="999" maxlength="3"
            required>
    </td>
    <td id="td-price` + count + `" class="align-middle">
        <input class="form-control" type="number" id="price` + count + `" name="product[` + count + `][price]"
            oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
            maxlength="7"
            required>
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
            <label for="is_payed" class="col-sm-2 col-form-label">Оплачено</label>
            <div class="col-sm-10">
                <div class="checkbox checkbox-success checkbox-fill d-inline">
                    <input type="checkbox" name="is_payed" id="is_payed" value="{{ old('is_payed', '1') }}">
                    <label for="is_payed" class="cr"></label>
                </div>
            </div>
        </div>

        <div class="form-group row">
            <label for="date" class="col-sm-2 col-form-label">Дата счета</label>
            <div class="col-sm-10">
                <input type="text" class="form-control date-picker" name="date" id="date"
                       value="{{ old('date', (new \Carbon\Carbon())->setTimezone('Asia/Novosibirsk')->format('d-m-Y')) }}">
            </div>
        </div>

        <div class="form-group row">
            <label for="income_date" class="col-sm-2 col-form-label">Дата оплаты</label>
            <div class="col-sm-10">
                <input type="text" class="form-control date-picker" name="income_date" id="income_date"
                       value="{{ old('income_date') }}">
            </div>
        </div>

        <div class="form-group row">
            <label for="plan_date" class="col-sm-2 col-form-label">Планируемая дата оплаты</label>
            <div class="col-sm-10">
                <input type="text" class="form-control date-picker" name="plan_date" id="plan_date"
                       value="{{ old('plan_date') }}">
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
