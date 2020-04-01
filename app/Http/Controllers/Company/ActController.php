<?php

namespace App\Http\Controllers\Company;

use App\Entity\Act;
use App\Entity\ActProducts;
use App\Entity\ActService;
use App\Entity\Client;
use App\Entity\ClientCheckingAccount;
use App\Entity\Company;
use App\Entity\Income;
use App\Entity\Logo;
use App\Entity\PayService;
use App\Entity\Product;
use App\Entity\ReadyAccount;
use App\Http\Requests\Company\IncomeRequest;
use App\Http\Controllers\Controller;
use App\Services\IncomeService;
use FontLib\Table\Type\name;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PDF;
class ActController extends Controller
{
    public function show(Request $request)
    {
        $company = Auth::user()->company;

        if (!$company) {
            throw new NotFoundHttpException('Компания не найдена');
        }

        $dateTo = $request->get('date_to');
        $dateFrom = $request->get('date_from');

        if (!$dateTo) {
            $dateTo = (new Carbon('now'))->setTimezone(new \DateTimeZone('Asia/Novosibirsk'))->setTimeFromTimeString('23:59:59');
        } else {
            $dateTo = (new Carbon($dateTo))->setTimezone(new \DateTimeZone('Asia/Novosibirsk'))->setTimeFromTimeString('23:59:59');
        }

        if (!$dateFrom) {
            $dateFrom = (new Carbon('first day of this month'))->setTimezone(new \DateTimeZone('Asia/Novosibirsk'))->setTimeFromTimeString('00:00:00')->subMonth(6);
        } else {
            $dateFrom = (new Carbon($dateFrom))->setTimezone(new \DateTimeZone('Asia/Novosibirsk'))->setTimeFromTimeString('00:00:00');
        }

        $paidDateFrom = $request->get('date_paid_from');
        $paidDateTo = $request->get('date_paid_to');
        $isPaid = $request->get('is_paid');
        $isNotPaid = $request->get('is_not_paid');
        $search = $request->get('search');
        $clients = $request->get('clients');


        if ($paidDateTo) {
            $paidDateTo = new Carbon($paidDateTo);
        }

        if ($paidDateFrom) {
            $paidDateFrom = new Carbon($paidDateFrom);
        }


        $act = $company->act()->orderBy('data', 'DESC')->orderBy('id', 'DESC')->get()->filter(function ($act) use ($dateFrom, $dateTo, $isPaid, $isNotPaid, $paidDateTo, $paidDateFrom, $clients) {
            /** @var Act $act */
            if ($act->data && $act->data <= $dateTo && $act->data >= $dateFrom) {
                if ($paidDateFrom || $paidDateTo) {
                    if (!$act->data || $act->data > $paidDateTo || $act->data < $paidDateFrom) {
                        return false;
                    }
                }

                if ($isPaid && !$act->status && !$isNotPaid) {
                    return false;
                }

                if ($isNotPaid && $act->status && !$isPaid) {
                    return false;

                }
                foreach ($act->checkingAccounts as $val) {
                    if (!empty($clients) && !in_array($val->name, $clients)) {
                        return false;
                    }
                }

                return true;
            }

        });


        return view('act.show', compact('company',
            'act',
            'dateFrom',
            'dateTo'
        ));
    }

    public function create()
    {
        $company = Auth::user()->company;
        return view('act.create', compact('company'));
    }

    public function store(Request $request)
    {
        $company = Auth::user()->company;

        $incomses = Income::findOrFail($request->income_id);
        $pays = PayService::findOrFail($incomses->pay_service);

        $incomeNumberAccount = [];
        $numberAccount = [];

        foreach ($company->act as $act) {
            $incomeNumberAccount[] = $act->number;
        }

        foreach ($company->accountNumber as $accountNumbe) {
            $numberAccount[] = $accountNumbe->act_number;
        }

        if ($incomeNumberAccount == null && $numberAccount == null) {
            $maxNumber = 1;
        }
        elseif ($incomeNumberAccount == null) {
            foreach ($company->accountNumber as $accountNumber) {
                $maxNumber = $accountNumber->act_number;
            }
        }
        elseif ($numberAccount == null) {
            $maxNumber = max($incomeNumberAccount)+1;
        } else {
            $maxIncome = max($incomeNumberAccount);
            $maxNumberAccount = max($numberAccount);
            $maxNumber = max($maxIncome, $maxNumberAccount) + 1;
        }

        $ip_incomeNumberAccount = [];
        $ip_numberAccount = [];

        foreach ($company->act as $act) {
            $ip_incomeNumberAccount[] = $act->ip_act_number;
        }

        foreach ($company->accountNumber as $accountNumbe) {
            $ip_numberAccount[] = $accountNumbe->ip_act_number;
        }

        if ($ip_incomeNumberAccount == null && $ip_numberAccount == null) {
            $ip_maxNumber = 1;
        }
        elseif ($ip_incomeNumberAccount == null) {
            foreach ($company->accountNumber as $accountNumber) {
                $maxNumber = $accountNumber->ip_act_number;
            }
        }
        elseif ($ip_numberAccount == null) {
            $ip_maxNumber = max($ip_incomeNumberAccount)+1;
        } else {
            $ip_maxIncome = max($ip_incomeNumberAccount);
            $ip_maxNumberAccount = max($ip_numberAccount);
            $ip_maxNumber = max($ip_maxIncome, $ip_maxNumberAccount) + 1;
        }





        DB::transaction(function () use ($request, $company, $maxNumber, $ip_maxNumber, $pays) {
            if ($pays->type_company == 'ИП' || $pays == null) {
                $act = Act::make([
                    'ip_act_number' => $ip_maxNumber,
                    'summa' => $request->sum,
                    'data' => $request->date ? new Carbon($request->date) : null,
                    'client' => $request->client ?: null,
                    'status' => $request->is_payed ? true : false,
                    'company_id' => $company->id,
                    'pay_service' => $request->pay,
                    'client' => $request->client ?: null,
                ]);
            } else {
                $act = Act::make([
                    'number' => $maxNumber,
                    'summa' => $request->sum,
                    'data' => $request->date ? new Carbon($request->date) : null,
                    'client' => $request->client ?: null,
                    'status' => $request->is_payed ? true : false,
                    'company_id' => $company->id,
                    'pay_service' => $request->pay,
                    'client' => $request->client ?: null,
                ]);

            }


            $act->saveOrFail();

            foreach ($request->section as $value) {
                $income = Income::where( ['account_number' => $value['name']], ['company_id' => Auth::user()->company->id]);

                    $act_service = ActService::make([
                        'act_id' => $act->id,
                        'income_id' => $value['name'],
                    ]);

                 $income->update([
                    'ready_acts' => 1]);

                    $act_service->saveOrFail();
            }
            foreach ($request->product as $value) {
                $act_products = ActProducts::make([
                   'act_id' => $act->id,
                   'product' => $value['name'],
                   'count' => $value['count'],
                   'price' => $value['price'],
                ]);
                $act_products->saveOrFail();
            }

            return $act;
        });

        return redirect()->route('act.show', ['token' => $company->token]);
    }

    public function getDate(Request $request)
    {
        $company = Auth::user()->company;
        $act = Act::findOrFail($request->actId);

        if (!$company || $act->company->id !== $company->id) {
            throw new NotFoundHttpException('Такой компании не найдено');
        }

        return DB::transaction(function () use ($request, $act) {

            $income = $act->update([
                'status' => true ]);
            return $income;
        });

    }

    public function setUnpayed(Act $act)
    {
        $company = Auth::user()->company;

        if (!$company || $act->company->id !== $company->id) {
            throw new NotFoundHttpException('Такой компании не найдено');
        }

        $act->update([
            'status' => false,
        ]);

        return redirect()->route('act.show', ['token' => $company->token]);
    }

    public function delete(string $act, Request $request)
    {
        $act = Act::findOrFail($act);
        //$company = Company::where(['token' => $token])->first();
        $company = Auth::user()->company;


        if (!$company || $act->company->id !== $company->id) {
            throw new NotFoundHttpException('Такой компании не найдено');
        }

        $act->delete();

        foreach ($request->incoms as $val) {
            $income = Income::where(['account_number' => $val], ['company_id' => Auth::user()->company->id]);
            $incomes = $income->update([
                'ready_acts' => 0]);
        }

        return redirect()->route('act.show', ['token' => $company->token]);
    }

    public function edit(string $act)
    {
        $act = Act::findOrFail($act);
        //$company = Company::where(['token' => $token])->first();
        $company = Auth::user()->company;

        if (!$company || $act->company->id !== $company->id) {
            throw new NotFoundHttpException('Такой компании не найдено');
        }

        return view('act.edit', compact('company', 'act'));
    }

    public function printExit(Request $request, Act $act)
    {
        $company = Auth::user()->company;

        if (!$company || $act->company->id !== $company->id) {
            throw new NotFoundHttpException('Такой компании не найдено');
        }

        $payService = PayService::find($request->pay_service);
        $clientCheckingAccount = ClientCheckingAccount::find($request->client_account);
        $logo = Logo::where(['company_id' => Auth::user()->company->id])->get();

        $pdf = PDF::loadView('act.print', compact('payService', 'clientCheckingAccount', 'act', 'logo','company'));

        return $pdf->stream('акт №' . $act->number . ' от ' . $act->data . '.pdf');
    }

    public function getIncome(Request $request)
    {
        $company = Auth::user()->company;
        $income = Income::where( ['account_number' => $request->actIncome], ['company_id' => Auth::user()->company->id])->get();
        $data = [
            'account_number' => null,
            'product' => null,
            'client' => null,
            'sum' => null,
            'comment' => null,
            'income_date' => null,
            'plan_date' => null,
            'pay_service' => null,
            'service' => null

        ];
        foreach ($income as $value){
            $data['account_number'] = $value->account_number;
            $data['product'] = $value->products;
            $data['client'] = $value->normalClient;
            $data['sum'] = $value->sum;
            if (!empty($value->comment)) {
                $data['comment'] = $value->comment;
            } else {
                $data['comment'] = 'Описания нет';
            }
            if (!empty($value->income_date) && $value->is_payed !=0) {
                $data['income_date'] = $value->income_date->format('d-m-Y');
            } else {
                $data['income_date'] = 'Счет еще не оплачен';
            }
            if (!empty($value->payService->name)) {
                $data['pay_service'] = $value->payService->name;
            }
            if (!empty($value->plan_date)) {
                $data['plan_date'] = $value->plan_date;
            } else {
                $data['plan_date'] = "Не запланировано";
            }
            $data['service'] = $value->services->name;
        }

        return $data;

    }

    public function update(Request $request, string $act)
    {
        $act = Act::findOrFail($act);
        //$company = Company::where(['token' => $token])->first();
        $company = Auth::user()->company;

        if (!$company || $act->company->id !== $company->id) {
            throw new NotFoundHttpException('Такой компании не найдено');
        }

        return DB::transaction(function () use ($request, $act, $company) {
            foreach ($act->actProducts as $product) {
                $product->delete();
            }

            foreach ($act->actService as $actService) {
                $actService->delete();
            }

            foreach ($request->product as $value) {
                    $actProduct = ActProducts::make([
                        'product' => $value['name'],
                        'count' => $value['count'],
                        'price' => $value['price'],
                        'act_id' => $act->id,
                    ]);
                $actProduct->saveOrFail();
            }

            foreach ($request->section as $value) {
                $income = Income::where( ['account_number' => $value['name']], ['company_id' => Auth::user()->company->id]);

                $act_service = ActService::make([
                    'act_id' => $act->id,
                    'income_id' => $value['name'],
                ]);

                $income->update([
                    'ready_acts' => 1]);

                $act_service->saveOrFail();
            }

        $act->update([
            'number' => $request->number,
            'summa' => $request->sum,
            'data' => $request->act_date ? new Carbon($request->act_date) : null,
            'client' => $request->client ?: null,
            'status' => $request->is_payed ? true : false,
            'company_id' => $company->id,
            'pay_service' => $request->pay
            ]);


        return redirect()->route('act.show', ['token' => $company->token]);

        });
    }



}
