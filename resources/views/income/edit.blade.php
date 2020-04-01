@extends('layouts.app')

@section('content')

    <h1>Редактирование оплаты</h1>

    <form action="{{ route('income.update', ['token' => $company->token, 'id' => $income->id]) }}" method="POST"
          enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="account_number">Номер счета</label>
            <input type="number" class="input-group form-control" name="account_number" id="account_number"
                   value="{{ $income->account_number }}" step="0.01">
        </div>

        <div class="form-group">
            <label for="category">Клиент</label>
            <select name="category" id="category" class="form-control select2" required>
                @foreach ($company->activeClients() as $client)
                    <option value="{{ $client->id }}" {{ $client->id == $income->normalClient->id ? 'selected' : '' }}>
                        {{ $client->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="category">Проект</label>
            <select name="project_id" id="project_id" class="form-control select2 clients">
                <option value="">Без проекта</option>
                @foreach ($company->projects as $project)
                    <option value="{{ $project->id }}" {{ $project->id == $income->id_project ? 'selected' : '' }}>{{
                        $project->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="client_cheking_accounts">Юр. лицо</label>
            <select name="client_cheking_accounts" id="client_cheking_accounts" class="form-control select2">
                @foreach ($company->activeClients() as $client)
                    @foreach($client->checkingAccounts as $check)
                        <option
                            value="{{ $check->id }}" {{ $check->id == $income->client_cheking_accounts ? 'selected' : '' }}>
                            {{ $check->name }}
                        </option>
                    @endforeach
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="is_payed">Оплачено</label>
            <input type="checkbox" name="is_payed" id="is_payed" {{ $income->is_payed ? 'checked' : '' }}>
        </div>

        <div class="form-group">
            <label for="comment">Комментарий</label>
            <input type="text" class="input-group form-control" name="comment" id="comment"
                   value="{{ $income->comment }}">
        </div>

        <div class="form-group">
            <label for="service">Услуга</label>
            <select name="service" id="service" class="form-control">
                @foreach ($company->services as $service)
                    <option value="{{ $service->id }}" {{ $service->id == $income->service ? 'selected' : '' }}>{{
                        $service->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="pay_service">Получатель</label>
            <select name="pay_service" id="pay_service" class="form-control">
                @foreach ($company->payServices as $payService)
                    <option
                        value="{{ $payService->id }}" {{ $payService->id == $income->pay_service ? 'selected' : '' }}>{{
                        $payService->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="date">Дата счета</label>
            <input type="text" class="input-group form-control date-picker" name="date" id="date"
                   value="{{ $income->date->format('d-m-Y') }}">
        </div>

        <div class="form-group">
            <label for="income_date">Дата оплаты</label>
            <input type="text" class="input-group form-control date-picker" name="income_date" id="income_date"
                   value="{{ $income->income_date ? $income->income_date->format('d-m-Y') : '' }}">
        </div>

        <div class="form-group">
            <label for="plan_date">Планируемая дата оплаты</label>
            <input type="text" class="input-group form-control date-picker" name="plan_date" id="plan_date"
                   value="{{ $income->plan_date ? $income->plan_date->format('d-m-Y') : '' }}">
        </div>

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
                    @foreach($income->products as $key => $product)
                        <tr>
                            <td class="align-middle product-name">
                                <input type="text" class="form-control" name="product[1][name]" id="product"
                                       value="{{ $product->product }}" required>
                            </td>
                            <td class="align-middle product-count">
                                <input
                                    type="number" class="form-control" name="product[1][count]"
                                    id="count" value="{{ $product->count }}" step="1"
                                    oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                    max="999" maxlength="3"
                                    required>
                            </td>
                            <td class="align-middle product-price">
                                <input
                                    type="number" class="form-control" name="product[1][price]"
                                    id="price" value="{{ $product->price }}"
                                    step="0.01" max="1000000"
                                    oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                    maxlength="7"
                                    required>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="form-group">
            <button class="btn btn-success" type="submit">
                Сохранить
            </button>
            <a href="#" onclick="history.back();" class="btn btn-danger">Отмена</a>
        </div>

    </form>

@endsection
