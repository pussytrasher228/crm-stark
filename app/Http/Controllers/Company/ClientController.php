<?php

namespace App\Http\Controllers\Company;

use App\Entity\Client;
use App\Entity\ClientCheckingAccount;
use App\Entity\Employee;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    public function show(Client $client)
    {
        $company = Auth::user()->company;
        if ($client->company->id != $company->id) {
            return redirect()->route('companies.show', ['token' => $company->token]);
        }

        $incomes = $client->incomes()->orderBy('date', 'desc')->get();

        return view('client.show', compact('client', 'company', 'incomes'));
    }

    public function edit(Client $client)
    {
        $company = Auth::user()->company;
        if ($client->company->id != $company->id) {
            return redirect()->route('companies.show', ['token' => $company->token]);
        }

        return view('admin.client.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        $company = Auth::user()->company;
        if ($client->company->id != $company->id) {
            return redirect()->route('companies.show', ['token' => $company->token]);
        }

        $client = DB::transaction(function () use ($request, $client) {

            $client->update([
                'name' => $request->name,
                'syte' => $request->syte,
                'disabled' => $request->disabled ? true : null,
            ]);

            return $client;
        });

        $incomes = $client->incomes;

        return view('client.show', compact('client', 'company', 'incomes'));
    }

    public function createClientAccount(Client $client)
    {
        return view('admin.client_accounts.create', compact('client'));
    }

    public function createEmployees(Client $client)
    {
        return view('admin.client_accounts.create_employees', compact('client'));
    }

    public function storeEmployees(Request $request, Client $client)
    {
        DB::transaction(function () use ($request, $client) {

            $employees = Employee::make([
                'name' => $request->name ?: null,
                'email' => $request->email ?: null,
                'phone' => $request->phone ?: null,
                'position' => $request->position ?: null,
                'comment' => $request->comment ?: null,
                'clients_id' => $client->id,
            ]);

            $employees->saveOrFail();

            return $employees;
        });

        return redirect()->route('client.show', $client);
    }

    public function storeClientAccount(Request $request, Client $client)
    {
        DB::transaction(function () use ($request, $client) {

            $clientAccount = ClientCheckingAccount::make([
                'name' => $request->name,
                'checking_account' => $request->checking_account,
                'ks' => $request->ks,
                'inn' => $request->inn,
                'kpp' => $request->kpp,
                'bik' => $request->bik,
                'bank_name' => $request->bank_name,
                'ur_address' => $request->ur_address,
                'fact_address' => $request->fact_address,
                'mail_address' => $request->mail_address,
                'client_id' => $client->id,
            ]);

            $clientAccount->saveOrFail();

            return $clientAccount;
        });

        return redirect()->route('client.show', $client);
    }

    public function editClientAccount(ClientCheckingAccount $clientAccount)
    {
        return view('admin.client_accounts.edit', compact('clientAccount'));
    }

    public function editEmployees(Employee $employees)
    {
        return view('admin.client_accounts.edit_employees', compact('employees'));
    }

    public function updateClientAccount(Request $request, ClientCheckingAccount $clientAccount)
    {
        $clientAccount = DB::transaction(function () use ($request, $clientAccount) {

            $clientAccount->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'position' => $request->position,
                'comment' => $request->comment,
            ]);

            return $clientAccount;
        });

        return redirect()->route('client.show', ['client' => $clientAccount->client]);
    }

    public function updateEmployees(Request $request, Employee $employees)
    {
        $employees = DB::transaction(function () use ($request, $employees) {

            $employees->update([
                'name' => $request->name ?: null,
                'email' => $request->email ?: null,
                'phone' => $request->phone ?: null,
                'position' => $request->position ?: null,
                'comment' => $request->comment ?: null,
            ]);

            return $employees;
        });

        return redirect()->route('client.show', ['client' => $employees->client]);
    }
}
