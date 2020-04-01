@extends('layouts.app')

@section('content')

    <h1>Формирование счета</h1>

    <form action="{{ route('income.print.exit', $income) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="client_account">Юр. лицо клиента</label>
            <select name="client_account" id="client_account" class="form-control">
                @foreach ($income->normalClient->checkingAccounts as $checkingAccount)
                    <option value="{{ $checkingAccount->id }}">{{ $checkingAccount->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="pay_service">Сервис оплаты</label>
            <select name="pay_service" id="pay_service" class="form-control">
                @foreach ($company->payServices as $service)
                    <option value="{{ $service->id }}">{{ $service->name }}</option>
                @endforeach
            </select>
        </div>

        <button class="btn btn-success" type="submit">
            Сформировать счет
        </button>

    </form>

@endsection
