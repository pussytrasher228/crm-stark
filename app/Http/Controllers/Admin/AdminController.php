<?php

namespace App\Http\Controllers\Admin;

use App\Entity\AccountNumber;
use App\Entity\Client;
use App\Entity\Company;
use App\Entity\ExpenseCategory;
use App\Entity\IncomePlan;
use App\Entity\Logo;
use App\Entity\Service;
use App\Entity\PayService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Company\CategoryRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Response;

class AdminController extends Controller
{
    public function show()
    {
        $services = Service::where(['company_id' => Auth::user()->company->id])->get();

        $clients = Client::where(['company_id' => Auth::user()->company->id])->get();

        $payServices = PayService::where(['company_id' => Auth::user()->company->id])->get();

        $accountNumber = AccountNumber::where(['company_id' => Auth::user()->company->id])->get();

        $logo = Logo::where(['company_id' => Auth::user()->company->id])->get();

        $categories = ExpenseCategory::where(['company_id' => Auth::user()->company->id, 'parent_id' => null])->get();

        $incomePlans = IncomePlan::where(['company' => Auth::user()->company->id, 'year'=> date( 'Y' )])->get();

        return view('admin.show', [
            'company' => Auth::user()->company,
            'services' => $services,
            'clients' => $clients,
            'payServices' => $payServices,
            'accountNumbers' => $accountNumber,
            'logo' => $logo,
            'categories' => $categories,
            'incomePlans' => $incomePlans,
        ]);
    }

    public function createClient()
    {
        return view('admin.client.create');
    }

    public function createIncomePlans()
    {
        $mounth = [
            'Январь',
            'Февраль',
            'Март',
            'Апрель',
            'Май',
            'Июнь',
            'Июль',
            'Август',
            'Сентябрь',
            'Октябрь',
            'Ноябрь',
            'Декабрь'
        ];

        return view('admin.income_plans.create', [
            'mounth' => $mounth
        ]);
    }

    public function storeIncomePlans(Request $request)
    {
        $company = Auth::user()->company;

        DB::transaction(function () use ($request, $company) {
            foreach ($request->mounth as $key => $value) {

                $incomePlans = IncomePlan::make([
                    'month' => $key,
                    'year' => $value['yars'],
                    'plan' => $value['plan'],
                    'mounth_name' => $value['mounth_name'],
                    'company' => $company->id
                ]);

                $incomePlans->saveOrFail();
            }



            return $incomePlans;
        });

        return redirect()->route('admin', ['token' => $company->token]);
    }

    public function editIncomePlans($years)
    {
        $incomePlans = IncomePlan::where(['company' => Auth::user()->company->id, 'year'=> $years])->get();

        return view('admin.income_plans.edit', compact('incomePlans'));
    }

    public function updateIncomePlans(Request $request)
    {
        $company = Auth::user()->company;

        foreach ($request->mounth as $key => $value) {
            $incomePlans = IncomePlan::findOrFail($value['id']);

                $incomePlans->update([
                    'plan' => $value['plan'],
                ]);
        }

        return redirect()->route('admin', ['token' => $company->token]);
    }

    public function storeClient(Request $request)
    {
        $company = Auth::user()->company;

        DB::transaction(function () use ($request, $company) {

            $income = Client::make([
                'name' => $request->name,
                'company_id' => $company->id,
                'syte' => $request->syte,
            ]);

            $income->saveOrFail();

            return $income;
        });

        return redirect()->route('companies.show', ['token' => $company->token]);
    }

    public function createService()
    {
        return view('admin.service.create');
    }

    public function storeService(Request $request)
    {
        $company = Auth::user()->company;

        DB::transaction(function () use ($request, $company) {

            $income = Service::make([
                'name' => $request->name,
                'company_id' => $company->id,
                'income' => $request->income ? true : false,
            ]);

            $income->saveOrFail();

            return $income;
        });

        return redirect()->route('admin', ['token' => $company->token]);
    }

    public function createPayService()
    {
        return view('admin.pay_service.create');
    }

    public function editPayService(PayService $payService)
    {
        return view('admin.pay_service.edit', compact('payService'));
    }

    public function storeAccountNumber(Request $request)
    {
        $company = Auth::user()->company;

        DB::transaction(function () use ($request, $company) {

            $accountNumber = AccountNumber::make([
                'account_number' => $request->account_number,
                'act_number' => $request->act_number,
                'company_id' => $company->id,
                'ip_act_number' => $request->ip_act_number,
            ]);

            $accountNumber->saveOrFail();

            return $accountNumber;
        });

        return redirect()->route('admin', ['token' => $company->token]);
    }


    public function storePayService(Request $request)
    {
        $company = Auth::user()->company;

        DB::transaction(function () use ($request, $company) {


            $payService = PayService::make([
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
                'company_id' => $company->id,
//                'bank_account' => $request->bank_account,
                'type_company' => $request->type_company,
                'income' => $request->income ? true : false,
            ]);

            $payService->saveOrFail();

            return $payService;
        });

        return redirect()->route('admin', ['token' => $company->token]);
    }

    public function createAccountNumber()
    {
        return view('admin.account_number.create');
    }

    public function editAccountNumber(AccountNumber $accountNumber)
    {
        return view('admin.account_number.edit', compact('accountNumber'));
    }

    public function editCompany()
    {
        $company = Auth::user()->company;
        return view('admin.company.edit', compact('company'));
    }

    public function updateCompany(Request $request)
    {
        $company = Auth::user()->company;

        $company = DB::transaction(function () use ($request, $company) {

            $company->update([
                'name' => $request->company_name,
                'phone' => $request->company_phone,
                'site' => $request->company_site,
                'email' => $request->company_email,
                'addres' => $request->company_addres,
                'direct' => $request->direct,
            ]);

            return $company;
        });

        return redirect()->route('admin', ['token' => $company->token]);
    }

    public function editService(Service $service)
    {
        return view('admin.service.edit', compact('service'));
    }
    public function updateService(Request $request, Service $service)
    {
        $company = Auth::user()->company;
        if ($service->company->id != $company->id) {
            return redirect()->route('companies.show', ['token' => $company->token]);
        }

        $service = DB::transaction(function () use ($request, $service) {

            $service->update([
                'name' => $request->name,
                'disabled' => $request->disabled ? true : null,
                'income' => $request->income ? true : false,
            ]);

            return $service;
        });


        return redirect()->route('admin', ['token' => $company->token]);
    }

    public function updateAccountNumber(Request $request, AccountNumber $accountNumber)
    {
        $company = Auth::user()->company;
        if ($accountNumber->company->id != $company->id) {
            return redirect()->route('companies.show', ['token' => $company->token]);
        }

        $accountNumber = DB::transaction(function () use ($request, $accountNumber) {

            $accountNumber->update([
                'account_number' => $request->account_number,
                'act_number' => $request->act_number,
                'ip_act_number' => $request->ip_act_number,
            ]);

            return $accountNumber;
        });

        return redirect()->route('admin', ['token' => $company->token]);
    }


    public function updatePayService(Request $request, PayService $payService)
    {
        $company = Auth::user()->company;
        if ($payService->company->id != $company->id) {
            return redirect()->route('companies.show', ['token' => $company->token]);
        }

        $payService = DB::transaction(function () use ($request, $payService) {

            $payService->update([
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
//                'bank_account' => $request->bank_account,
                'type_company' => $request->type_company,
                'income' => $request->income ? true : false,
            ]);

            return $payService;
        });

        return redirect()->route('admin', ['token' => $company->token]);
    }

    public function removePayService(PayService $payService)
    {
        $company = Auth::user()->company;

        if (!$company || $payService->company->id !== $company->id) {
            throw new NotFoundHttpException('Такой компании не найдено');
        }

        $payService->delete();

        return redirect()->route('admin', ['token' => $company->token]);
    }

    public function removeClient(Client $client)
    {
        $company = Auth::user()->company;

        if (!$company || $client->company->id !== $company->id) {
            throw new NotFoundHttpException('Такой компании не найдено');
        }

        $client->delete();

        return redirect()->route('admin', ['token' => $company->token]);
    }

    public function removeAccountNumber(AccountNumber $accountNumber)
    {
        $company = Auth::user()->company;

        if (!$company || $accountNumber->company->id !== $company->id) {
            throw new NotFoundHttpException('Такой компании не найдено');
        }

        $accountNumber->delete();

        return redirect()->route('admin', ['token' => $company->token]);
    }

    public function removeService(Service $service)
    {
        $company = Auth::user()->company;

        if (!$company || $service->company->id !== $company->id) {
            throw new NotFoundHttpException('Такой компании не найдено');
        }

        $service->delete();

        return redirect()->route('admin', ['token' => $company->token]);
    }

    public function createLogo()
    {
        return view('admin.logo.create');
    }

    public function storeLogo(Request $request)
    {
        $this->validate($request, [
            'logo' => 'nullable|image'
        ]);
        $company = Auth::user()->company;

        DB::transaction(function () use ($request, $company) {

            $logo = Logo::make([
                'company_id' => $company->id,
            ]);

            $logo->saveOrFail();
            $logo->uploadLogo($request->file('logo'));

            return $logo;
        });


        return redirect()->route('admin', ['token' => $company->token]);

    }

    public function removeLogo(Logo $logo)
    {
        $company = Auth::user()->company;

        if (!$company || $logo->company->id !== $company->id) {
            throw new NotFoundHttpException('Такой компании не найдено');
        }

        $logo->delete();

        return redirect()->route('admin', ['token' => $company->token]);
    }

    public function showTest()
    {
        return view('test.edit');
    }
}
