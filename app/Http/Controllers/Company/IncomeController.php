<?php

namespace App\Http\Controllers\Company;

use App\Entity\ClientCheckingAccount;
use App\Entity\Company;
use App\Entity\Income;
use App\Entity\Logo;
use App\Entity\PayService;
use App\Entity\Product;
use App\Entity\Project;
use App\Entity\ReadyAccount;
use App\Entity\Service;
use App\Http\Requests\Company\IncomeRequest;
use App\Http\Controllers\Controller;
use App\Services\IncomeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PDF;

class IncomeController extends Controller
{
    /**
     * @var IncomeService
     */
    private $service;

    public function __construct(IncomeService $service)
    {
        $this->service = $service;
    }

    public function create()
    {
        //$company = Company::where(['token' => $token])->first();
        $company = Auth::user()->company;

        return view('income.create', compact('company'));
    }

    public function store(IncomeRequest $request)
    {
        //$company = Company::where(['token' => $token])->first();
        $company = Auth::user()->company;

        $this->service->create($request, $company);
        return redirect()->route('companies.show', ['token' => $company->token]);
    }

    public function getDate(Request $request)
    {
        $company = Auth::user()->company;
        $income = Income::findOrFail($request->incomeId);

        if (!$company || $income->company->id !== $company->id) {
            throw new NotFoundHttpException('Такой компании не найдено');
        }

        $project = $income->project;

        if (!empty($project)) {
            DB::transaction(function () use ($project, $income) {

                $project = $project->update([
                    'fact_income' => $project->fact_income + $income->sum,
                ]);

            });
        }

        return DB::transaction(function () use ($request, $income, $project) {

        $income = $income->update([
            'income_date' => $request->incomeDate ? new Carbon($request->incomeDate) : null,
            'is_payed' => true ]);

        return $income;
        });

    }

    public static function serviceExprense($id)
    {
        $service = Service::find($id);
        return $service;
    }


    public function edit(string $income)
    {
        $income = Income::findOrFail($income);
        //$company = Company::where(['token' => $token])->first();
        $company = Auth::user()->company;

        if (!$company || $income->company->id !== $company->id) {
            throw new NotFoundHttpException('Такой компании не найдено');
        }

        return view('income.edit', compact('company', 'income'));
    }

    public function update(IncomeRequest $request, string $income)
    {
        $income = Income::findOrFail($income);
        //$company = Company::where(['token' => $token])->first();
        $company = Auth::user()->company;

        if (!$company || $income->company->id !== $company->id) {
            throw new NotFoundHttpException('Такой компании не найдено');
        }

        $this->service->update($request, $income);

        return redirect()->route('companies.show', ['token' => $company->token]);
    }


    public function delete(string $income)
    {
        $income = Income::findOrFail($income);
        //$company = Company::where(['token' => $token])->first();
        $company = Auth::user()->company;

        if (!$company || $income->company->id !== $company->id) {
            throw new NotFoundHttpException('Такой компании не найдено');
        }

        $project = $income->project;

        if (!empty($project)) {
            DB::transaction(function () use ($project, $income) {

                $project = $project->update([
                    'fact_income' => $project->fact_income - $income->sum,
                ]);

            });
        }

        $income->delete();

        return redirect()->route('companies.show', ['token' => $company->token]);
    }

    public function setPayed(Income $income)
    {
        $company = Auth::user()->company;

        if (!$company || $income->company->id !== $company->id) {
            throw new NotFoundHttpException('Такой компании не найдено');
        }

        $income->update([
            'is_payed' => true,
        ]);

        return redirect()->route('companies.show', ['token' => $company->token]);
    }

    public function setUnpayed(Income $income)
    {
        $company = Auth::user()->company;

        if (!$company || $income->company->id !== $company->id) {
            throw new NotFoundHttpException('Такой компании не найдено');
        }

        $project = $income->project;

        if (!empty($project)) {
            DB::transaction(function () use ($project, $income) {

                $project = $project->update([
                    'fact_income' => $project->fact_income - $income->sum,
                ]);

            });
        }

        $income->update([
            'is_payed' => false,
            'income_date' => null,
        ]);

        return redirect()->route('companies.show', ['token' => $company->token]);
    }

    public function printPdf(Income $income)
    {
        $company = Auth::user()->company;

        if (!$company || $income->company->id !== $company->id) {
            throw new NotFoundHttpException('Такой компании не найдено');
        }

        return view('income.pre_print', compact('income', 'company'));
    }

    public function storeReadyAccount(Request $request, Income $income, PayService $payService, ClientCheckingAccount $clientCheckingAccount)
    {

        DB::transaction(function () use ($request, $income, $payService, $clientCheckingAccount) {

            $readyAccount = ReadyAccount::make([
                'name' => $clientCheckingAccount->name? $payService->name : '',
                'inn' => $payService->inn ? $payService->inn : '',
                'kpp' => $payService->kpp ? $payService->kpp : '',
                'ks' => $payService->ks ? $payService->ks : '',
                'checking_account' => $payService->checking_account ? $payService->checking_account : '',
                'pay_services' => $payService->name ? $payService->name : '',
                'bik' => $payService->bik ? $payService->bik : '',
                'bank_name' => $payService->bank_name ? $payService->bank_name : '',
                'account_number' => $income->account_number,
                'services' => $income->service,
                'sum' => $income->sum,
                'date' => $income->date,
            ]);

            $readyAccount->saveOrFail();

            return $readyAccount;
        });
    }

    public function printExit(Request $request, Income $income)
    {
        $company = Auth::user()->company;

        if (!$company || $income->company->id !== $company->id) {
            throw new NotFoundHttpException('Такой компании не найдено');
        }

        $payService = PayService::find($request->pay_service);
        $clientCheckingAccount = ClientCheckingAccount::find($request->client_account);
        $logo = Logo::where(['company_id' => Auth::user()->company->id])->get();

        $this->storeReadyAccount($request, $income, $payService, $clientCheckingAccount);

        $pdf = PDF::loadView('income.print', compact('payService', 'clientCheckingAccount', 'income', 'logo', 'company'));

        return $pdf->stream('счет №' . $income->account_number . ' от ' . $income->date->format('d-m-Y') . '.pdf');
    }

    public function getClientCheckingAccount(Request $request)
    {
        $clientsCheckingAccounts = ClientCheckingAccount::where('client_id',$request->value)->get();

        foreach ($clientsCheckingAccounts as $value) {
            $clientsCheckingAccount[] = $value;
        }

        return $clientsCheckingAccount;
    }
}
