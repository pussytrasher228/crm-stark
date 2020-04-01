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
use App\Entity\Regular;
use App\Http\Requests\Company\IncomeRequest;
use App\Http\Controllers\Controller;
use App\Services\IncomeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PDF;
class RegularController extends Controller
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


        $regularClients = $company->activeRegularClients()->filter(function ($act) use ($dateFrom, $dateTo, $isPaid, $isNotPaid, $paidDateTo, $paidDateFrom, $clients) {
            /** @var Act $act */
            if ($act->date && $act->date <= $dateTo && $act->date >= $dateFrom) {
                if ($paidDateFrom || $paidDateTo) {
                    if (!$act->date || $act->date > $paidDateTo || $act->date < $paidDateFrom) {
                        return false;
                    }
                }


                return true;
            }

        });

        $regularClientsNo = $company->NoActiveRegularClients()->filter(function ($act) use ($dateFrom, $dateTo, $isPaid, $isNotPaid, $paidDateTo, $paidDateFrom, $clients) {
            /** @var Act $act */
            if ($act->date && $act->date <= $dateTo && $act->date >= $dateFrom) {
                if ($paidDateFrom || $paidDateTo) {
                    if (!$act->date || $act->date > $paidDateTo || $act->date < $paidDateFrom) {
                        return false;
                    }
                }


                return true;
            }

        });

        $fullCalculate = [
            'count' => $regularClients->count(),
            'price' => array_sum($regularClients->pluck('sum')->toArray())
        ];

        $fullCalculateN = [
            'count' => $regularClientsNo->count(),
            'price' => array_sum($regularClientsNo->pluck('sum')->toArray())
        ];

        return view('regularClients.show', compact('company',
            'regularClients',
            'regularClientsNo',
            'dateFrom',
            'dateTo',
            'fullCalculate',
            'fullCalculateN'
        ));
    }

    public function create()
    {
        $company = Auth::user()->company;
        return view('regularClients.create', compact('company'));
    }

    public function store(Request $request)
    {
        $company = Auth::user()->company;

        DB::transaction(function () use ($request, $company) {


            $regularClients = Regular::make([
                'date' => $request->date ? new Carbon($request->date) : null,
                'company_id' => $company->id,
                'client' => $request->client,
                'service' => $request->service,
                'pay_service' => $request->pay_service,
                'sum' => $request->sum,
                'comment' => $request->comment,
                'disabled' => $request->disabled ? true : false,
            ]);

            $regularClients->saveOrFail();

            return $regularClients;
        });

        return redirect()->route('regularClients.show', ['token' => $company->token]);
    }

    public function delete(string $income)
    {
        $income = Regular::findOrFail($income);
        //$company = Company::where(['token' => $token])->first();
        $company = Auth::user()->company;

        if (!$company || $income->company->id !== $company->id) {
            throw new NotFoundHttpException('Такой компании не найдено');
        }

        $income->delete();

        return redirect()->route('regularClients.show', ['token' => $company->token]);
    }

    public function edit(string $act)
    {
        $regular = Regular::findOrFail($act);
        //$company = Company::where(['token' => $token])->first();
        $company = Auth::user()->company;


        return view('regularClients.edit', compact('company', 'regular'));
    }

    public function update(Request $request, string $act)
    {
        $regular = Regular::findOrFail($act);
        //$company = Company::where(['token' => $token])->first();
        $company = Auth::user()->company;

        if (!$company || $regular->company->id !== $company->id) {
            throw new NotFoundHttpException('Такой компании не найдено');
        }

        return DB::transaction(function () use ($request, $regular, $company) {

            $regular->update([
                'date' => $request->date ? new Carbon($request->date) : null,
                'company_id' => $company->id,
                'client' => $request->client,
                'service' => $request->service,
                'pay_service' => $request->pay_service,
                'sum' => $request->sum,
                'comment' => $request->comment,
                'disabled' => $request->disabled ? true : false,
            ]);

            return redirect()->route('regularClients.show', ['token' => $company->token]);

        });
    }

}