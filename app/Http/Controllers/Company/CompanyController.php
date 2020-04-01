<?php

namespace App\Http\Controllers\Company;

use App\Entity\Company;
use App\Entity\DataSets\AnalyticsData;
use App\Entity\DataSets\ChartData;
use App\Entity\Expense;
use App\Entity\Income;
use App\Entity\Client;
use App\Entity\IncomePlan;
use App\Entity\Logo;
use App\Entity\ReadyAccount;
use App\Http\Requests\Company\CompanyRequest;
use App\Services\CompanyService;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CompanyController extends Controller
{
    /**
     * @var CompanyService
     */
    private $service;

    public function __construct(CompanyService $service)
    {
        $this->service = $service;
    }

    public function create()
    {
        return view('company.create');
    }

    public function store(CompanyRequest $request)
    {
        $company = $this->service->create($request);

        return redirect()->route('companies.show', ['token' => $company->token]);
    }

    public function showAnalytics(Request $request)
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
            $dateFrom = (new Carbon('first day of this month'))->setTimezone(new \DateTimeZone('Asia/Novosibirsk'))->setTimeFromTimeString('00:00:00');
        } else {
            $dateFrom = (new Carbon($dateFrom))->setTimezone(new \DateTimeZone('Asia/Novosibirsk'))->setTimeFromTimeString('00:00:00');
        }

        $paidDateFrom = $request->get('date_paid_from');
        $paidDateTo = $request->get('date_paid_to');
        $services = $request->get('services');
        $isPaid = $request->get('is_paid');
        $isNotPaid = $request->get('is_not_paid');

        if ($paidDateTo) {
            $paidDateTo = new Carbon($paidDateTo);
        }

        if ($paidDateFrom) {
            $paidDateFrom = new Carbon($paidDateFrom);
        }

        /** @var Collection $incomes */
        $incomes = $company->incomes()->orderBy('income_date', 'DESC')->orderBy('id', 'DESC')->get()->filter(function ($income) use ($dateFrom, $dateTo, $paidDateFrom, $paidDateTo, $services, $isPaid, $isNotPaid) {
            /** @var Income $income */
            if ($income->date && $income->date <= $dateTo && $income->date >= $dateFrom) {
                if ($paidDateFrom || $paidDateTo) {
                    if (!$income->income_date || $income->income_date > $paidDateTo || $income->income_date < $paidDateFrom) {
                        return false;
                    }
                }

                if (!empty($services) && !in_array($income->service, $services)) {
                    return false;
                }

                if ($isPaid && !$income->is_payed && !$isNotPaid) {
                    return false;
                }

                if ($isNotPaid && $income->is_payed && !$isPaid) {
                    return false;
                }

                return true;
            }
        });

        $fullCalculate = [
            'count' => $incomes->count(),
            'price' => array_sum($incomes->pluck('sum')->toArray())
        ];

        $expenses = $company->expenses()->orderBy('expense_date', 'DESC')->orderBy('id', 'DESC')->get()->filter(function ($expense) use ($dateFrom, $dateTo) {
            /** @var Expense $expense */
            if ($expense->expense_date && $expense->expense_date <= $dateTo && $expense->expense_date >= $dateFrom) {
                return true;
            }
        });

        $costs = new AnalyticsData($company);

        $period = CarbonPeriod::create($dateFrom, '1 day', $dateTo);
        $months = [];
        foreach ($period->toArray() as $date) {
            if (isset($months[$date->format('Y-m')])) continue;
            $months[$date->format('Y-m')] = new Carbon($date->format('Y-m-d'));
        }

        foreach ($months as $month) {
            $costs->calculate($month);
        }

        $firstChartData = json_encode([
            'labels' => $costs->getMonths(),
            'datasets' => [[
                'label' => "Прибыль",
                'data' => $costs->getPrologue(),
                'backgroundColor' => ChartData::getRandColors(1),
            ]],
        ]);

        $incomess = Income::where([
            ['client', '<>', null],
            ['income_date', '>=', $dateFrom],
            ['income_date', '<=', $dateTo],
        ])->get()->groupBy('client');

        $incomess = $incomess->map(function ($income) {
            return array_sum($income->map(function ($incomeObj) {
                return $incomeObj->sum;
            })->toArray());
        })->toArray();

        arsort($incomess);

        $incomess = array_slice($incomess, 0, 5);

        $secondChartData = json_encode([
            'labels' => array_keys($incomess),
            'datasets' => [[
                'label' => "Прибыль",
                'data' => array_values($incomess),
                'backgroundColor' => ChartData::getRandColors(count($incomess)),
            ]],
        ]);

        $thirdChartData = json_encode([
            'labels' => $costs->getMonths(),
            'datasets' => [[
                'label' => "Количество сделок",
                'data' => $costs->getCountOfIncomes(),
                'backgroundColor' => ChartData::getRandColors(1),
            ]],
        ]);

        $fourChartData = json_encode([
            'labels' => array_map(function ($month) {
                $date = explode('/', $month);
                $date = Carbon::createFromDate(20 . $date[1], $date[0], 1);
                $month = $date->month;
                $year = $date->year;
                return ChartData::getMonth($month) . ' ' . $year;
            }, $costs->getMonths()),
            'datasets' => [[
                'label' => "Средняя сумма сделки",
                'data' => $costs->getMeanIncomes(),
                'backgroundColor' => ChartData::getRandColors(count($costs->getMonths())),
            ]],
        ]);

        $payIncomes = Income::where([
            ['pay_service', '<>', null],
            ['income_date', '>=', $dateFrom],
            ['income_date', '<=', $dateTo],
        ])->get()->groupBy('pay_service')->toArray();

        $payIncomes = array_map(function ($deals) {
            return array_sum(array_map(function ($deal) {
                return $deal['sum'];
            }, $deals));
        }, $payIncomes);

        $fiveChartData = json_encode([
            'labels' => array_keys($payIncomes),
            'datasets' => [[
                'label' => "Средняя сумма сделки",
                'data' => array_values($payIncomes),
                'backgroundColor' => ChartData::getRandColors(count($costs->getMonths())),
            ]],
        ]);

        $sixChartData = json_encode([
            'labels' => array_keys($costs->getMonths()),
            'datasets' => [[
                'label' => "Выручка",
                'data' => array_values($costs->getTotalIncomes()),
                'backgroundColor' => ChartData::getRandColors(1),
            ]],
        ]);

        $categoryIncomes = Income::where([
            ['service', '<>', null],
            ['income_date', '>=', $dateFrom],
            ['income_date', '<=', $dateTo],
        ])->get()->groupBy('service')->toArray();

        $clients = Client::where(['company_id' => $company->id])->whereNull('disabled')->get();

        return view('company.analytics.show', compact(
            'company',
            'costs',
            'incomes',
            'expenses',
            'firstChartData',
            'secondChartData',
            'thirdChartData',
            'fourChartData',
            'categoryIncomes',
            'fiveChartData',
            'sixChartData',
            'dateFrom',
            'dateTo',
            'clients',
            'fullCalculate'
        ));
    }

    public function showExprenses(Request $request)
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
        $services = $request->get('services');
        $isPaid = $request->get('is_paid');
        $isNotPaid = $request->get('is_not_paid');

        if ($paidDateTo) {
            $paidDateTo = new Carbon($paidDateTo);
        }

        if ($paidDateFrom) {
            $paidDateFrom = new Carbon($paidDateFrom);
        }

        /** @var Collection $incomes */
        $incomes = $company->incomes()->orderBy('income_date', 'DESC')->orderBy('id', 'DESC')->get()->filter(function ($income) use ($dateFrom, $dateTo, $paidDateFrom, $paidDateTo, $services, $isPaid, $isNotPaid) {
            /** @var Income $income */
            if ($income->date && $income->date <= $dateTo && $income->date >= $dateFrom) {
                if ($paidDateFrom || $paidDateTo) {
                    if (!$income->income_date || $income->income_date > $paidDateTo || $income->income_date < $paidDateFrom) {
                        return false;
                    }
                }

                if (!$income->income_date || $income->income_date > $paidDateTo || $income->income_date < $paidDateFrom) {
                    return false;
                }

                if (!empty($services) && !in_array($income->service, $services)) {
                    return false;
                }

                if ($isPaid && !$income->is_payed && !$isNotPaid) {
                    return false;
                }

                if ($isNotPaid && $income->is_payed && !$isPaid) {
                    return false;
                }

                return true;
            }
        });

        $incomesIn = $company->inIncomesServices($dateFrom ,$dateTo)->groupBy('service')->toArray();

        $fullCalculate = [
            'count' => $incomes->count(),
            'price' => array_sum($incomes->pluck('sum')->toArray())
        ];

        $expenses = $company->expenses()->orderBy('expense_date', 'DESC')->orderBy('id', 'DESC')->get()->filter(function ($expense) use ($dateFrom, $dateTo) {
            /** @var Expense $expense */
            if ($expense->expense_date && $expense->expense_date <= $dateTo && $expense->expense_date >= $dateFrom) {
                return true;
            }
        });

        $costs = new AnalyticsData($company);

        $period = CarbonPeriod::create($dateFrom, '1 day', $dateTo);
        $months = [];
        foreach ($period->toArray() as $date) {
            if (isset($months[$date->format('Y-m')])) continue;
            $months[$date->format('Y-m')] = new Carbon($date->format('Y-m-d'));
        }

        foreach ($months as $month) {
            $costs->calculate($month);
        }

        $firstChartData = json_encode([
            'labels' => $costs->getMonths(),
            'datasets' => [[
                'label' => "Прибыль",
                'data' => $costs->getPrologue(),
                'backgroundColor' => ChartData::getRandColors(1),
            ]],
        ]);


            $incomess = Income::where([
                ['client', '<>', null],
                ['income_date', '>=', $dateFrom],
                ['income_date', '<=', $dateTo],
                ['company_id', '=', $company->id]
            ])->get()->groupBy('client');


        $incomess = $incomess->map(function ($income) {
            return array_sum($income->map(function ($incomeObj) {
                return $incomeObj->sum;
            })->toArray());
        })->toArray();

        arsort($incomess);

        $incomess = array_slice($incomess, 0, 5);

        $secondChartData = json_encode([
            'labels' => array_keys($incomess),
            'datasets' => [[
                'label' => "Прибыль",
                'data' => array_values($incomess),
                'backgroundColor' => ChartData::getRandColors(count($incomess)),
            ]],
        ]);

        $thirdChartData = json_encode([
            'labels' => $costs->getMonths(),
            'datasets' => [[
                'label' => "Количество сделок",
                'data' => $costs->getCountOfIncomes(),
                'backgroundColor' => ChartData::getRandColors(1),
            ]],
        ]);

        $fourChartData = json_encode([
            'labels' => array_map(function ($month) {
                $date = explode('/', $month);
                $date = Carbon::createFromDate(20 . $date[1], $date[0], 1);
                $month = $date->month;
                $year = $date->year;
                return ChartData::getMonth($month) . ' ' . $year;
            }, $costs->getMonths()),
            'datasets' => [[
                'label' => "Средняя сумма сделки",
                'data' => $costs->getMeanIncomes(),
                'backgroundColor' => ChartData::getRandColors(count($costs->getMonths())),
            ]],
        ]);

        $payIncomes = Income::where([
            ['pay_service', '<>', null],
            ['income_date', '>=', $dateFrom],
            ['income_date', '<=', $dateTo],
            ['company_id', '=', $company->id]
        ])->get()->groupBy('pay_service')->toArray();

        $payIncomes = array_map(function ($deals) {
            return array_sum(array_map(function ($deal) {
                return $deal['sum'];
            }, $deals));
        }, $payIncomes);

        $fiveChartData = json_encode([
            'labels' => array_keys($payIncomes),
            'datasets' => [[
                'label' => "Средняя сумма сделки",
                'data' => array_values($payIncomes),
                'backgroundColor' => ChartData::getRandColors(count($costs->getMonths())),
            ]],
        ]);

        $sixChartData = json_encode([
            'labels' => array_keys($costs->getMonths()),
            'datasets' => [[
                'label' => "Выручка",
                'data' => array_values($costs->getTotalIncomes()),
                'backgroundColor' => ChartData::getRandColors(1),
            ]],
        ]);

            $categoryIncomes = Income::where([
                ['service', '<>', null],
                ['income_date', '>=', $dateFrom],
                ['income_date', '<=', $dateTo],
                ['company_id', '=', $company->id]
            ])->get()->groupBy('service')->toArray();


        $clients = Client::where(['company_id' => $company->id])->whereNull('disabled')->get();

        return view('company.exprense.show', compact(
            'company',
            'costs',
            'incomes',
            'expenses',
            'firstChartData',
            'secondChartData',
            'thirdChartData',
            'fourChartData',
            'categoryIncomes',
            'fiveChartData',
            'sixChartData',
            'dateFrom',
            'dateTo',
            'clients',
            'fullCalculate',
            'incomesIn'
        ));
    }

    public function showTotal(Request $request)
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
        $services = $request->get('services');
        $isPaid = $request->get('is_paid');
        $isNotPaid = $request->get('is_not_paid');
        $clients_req = $request->get('clients');

        if ($paidDateTo) {
            $paidDateTo = new Carbon($paidDateTo);
        }

        if ($paidDateFrom) {
            $paidDateFrom = new Carbon($paidDateFrom);
        }

        $costs = new AnalyticsData($company);

        $period = CarbonPeriod::create($dateFrom, '1 day', $dateTo);
        $months = [];
        foreach ($period->toArray() as $date) {
            if (isset($months[$date->format('Y-m')])) continue;
            $months[$date->format('Y-m')] = new Carbon($date->format('Y-m-d'));
        }

        foreach ($months as $month) {
            $costs->calculate($month);
        }

        $clients = Client::where(['company_id' => $company->id])->whereNull('disabled')->get()->filter(function ($clients) use ($clients_req) {
            /** @var Client $clients */

            if ($clients_req == $clients->name) {
                return true;
            }

            if (!$clients_req){
                return true;
            }

        });

        return view('company.total.show', compact(
            'company',
            'costs',
            'incomes',
            'expenses',
            'firstChartData',
            'secondChartData',
            'thirdChartData',
            'fourChartData',
            'categoryIncomes',
            'fiveChartData',
            'sixChartData',
            'dateFrom',
            'dateTo',
            'clients',
            'fullCalculate'
        ));
    }

    public function show(Request $request)
    {
        // $param = token;
        //$company = Company::where(['token' => $token])->first();

        $company = Auth::user()->company;

        if (!$company) {
            throw new NotFoundHttpException('Компания не найдена');
        }
        $date = Carbon::now();

        $dateTo = $request->get('date_to');
        $dateFrom = $request->get('date_from');

        $dateTos = $request->get('date_to');
        $dateFroms = $request->get('date_from');

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
        $services = $request->get('services');
        $isPaid = $request->get('is_paid');
        $isNotPaid = $request->get('is_not_paid');
        $search = $request->get('search');


        if ($paidDateTo) {
            $paidDateTo = new Carbon($paidDateTo);
        }

        if ($paidDateFrom) {
            $paidDateFrom = new Carbon($paidDateFrom);
        }

        /** @var Collection $incomes */
        $incomes = $company->incomes()->orderBy('account_number', 'DESC')->get()->filter(function ($income) use ($dateFrom, $dateTo, $paidDateFrom, $paidDateTo, $services, $isPaid, $isNotPaid, $search, $company) {
            /** @var Income $income */


                if ($dateTo !=  (new Carbon('now'))->setTimezone(new \DateTimeZone('Asia/Novosibirsk'))->setTimeFromTimeString('23:59:59') || $dateFrom != $dateFrom = (new Carbon('first day of this month'))->setTimezone(new \DateTimeZone('Asia/Novosibirsk'))->setTimeFromTimeString('00:00:00')->subMonth(6)) {
                    if ($income->income_date <= $dateTo && $income->income_date >= $dateFrom) {
                        return true;
                    }
                }
                else {
                    if ($income->date)
                    {
                        return true;
                    }
                }
                if (!empty($services) && !in_array($income->service, $services)) {
                    return false;
                }

                if ($isPaid && !$income->is_payed && !$isNotPaid) {
                    return false;
                }

                if ($isNotPaid && $income->is_payed && !$isPaid) {
                    return false;
                }

                if (!empty($search) && !(stristr($income->account_number, $search))) {
                    if (!(stristr($income->normalClient, $search))) {
                        if (!(stristr($income->payService, $search))) {
                            return false;
                        }
                    }
                }

        });


        $fullCalculate = [
            'count' => $incomes->count(),
            'price' => array_sum($incomes->pluck('sum')->toArray()),
            'products' => $incomes,

            'count_payed' => $incomes->where('is_payed', 1)->count(),
            'price_payed' => array_sum($incomes->where('is_payed', 1)->pluck('sum')->toArray()),
            'products_payed' => $incomes->where('is_payed', 1),

            'count_not_payed' => $incomes->where('is_payed', '!=', 1)->count(),
            'price_not_payed' => array_sum($incomes->where('is_payed', '!=', 1)->pluck('sum')->toArray()),
            'products_not_payed' => $incomes->where('is_payed', '!=', 1),
        ];


        $categoryIncomes = Income::where([
            ['service', '<>', null],
            ['income_date', '>=', $dateFrom],
            ['income_date', '<=', $dateTo],
            ['company_id', '=', $company->id]
        ])->get()->groupBy('data')->toArray();

        $mont = str_replace(0, '', date("m"))-1;

        $clients = Client::where(['company_id' => $company->id])->whereNull('disabled')->get();

        $incomesMounth = Income::where([
            ['income_date', '>=', date('Y-m-01')],
            ['income_date', '<=', date('Y-m-t')],
            ['company_id', '=', $company->id]
        ])->orderBy('date', 'DESC')->orderBy('id', 'DESC')->get();


        $fullCalculateMounth = [
            'price' => array_sum($incomesMounth->pluck('sum')->toArray()),
        ];


        $incomePlans = IncomePlan::where(['company' => Auth::user()->company->id, 'year'=> date( 'Y' ), 'month' => $mont])->get();

        foreach ($incomePlans as $incomePlan) {
            if (!empty($incomePlan->plan)) {
                $proc = $fullCalculateMounth['price'] * 100 / $incomePlan->plan;
            }
        }

        return view('company.show', compact(
            'company',
            'costs',
            'incomes',
            'logo',
            'expenses',
            'firstChartData',
            'secondChartData',
            'thirdChartData',
            'fourChartData',
            'categoryIncomes',
            'fiveChartData',
            'sixChartData',
            'dateFrom',
            'dateTo',
            'clients',
            'fullCalculate',
            'incomePlans',
            'date',
            'proc',
            'fullCalculateMounth'
        ));
    }
}
