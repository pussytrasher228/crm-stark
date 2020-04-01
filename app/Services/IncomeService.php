<?php
/**
 * Created by PhpStorm.
 * User: qposer
 * Date: 24.09.18
 * Time: 22:23
 */

namespace App\Services;


use App\Entity\Company;
use App\Entity\Expense;
use App\Entity\Income;
use App\Entity\Product;
use App\Http\Requests\Company\ExpenseRequest;
use App\Http\Requests\Company\IncomeRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class IncomeService
{

    public function create(IncomeRequest $request, Company $company)
    {
            $incomeNumberAccount = [];
            $numberAccount = [];

            foreach ($company->incomes as $income) {
                $incomeNumberAccount[] = $income->account_number;
            }

            foreach ($company->accountNumber as $accountNumbe) {
                $numberAccount[] = $accountNumbe->account_number;
            }

            if ($incomeNumberAccount == null && $numberAccount == null) {
                $maxNumber = 1;
            }
            elseif ($incomeNumberAccount == null) {
               foreach ($company->accountNumber as $accountNumber) {
                   $maxNumber = $accountNumber->account_number;
               }
            }
            elseif ($numberAccount == null) {
                    $maxNumber = max($incomeNumberAccount)+1;
            } else {
                $maxIncome = max($incomeNumberAccount);
                $maxNumberAccount = max($numberAccount);
                $maxNumber = max($maxIncome, $maxNumberAccount) + 1; }


        return DB::transaction(function () use ($request, $company, $maxNumber) {
            $summa = 0;
            foreach ($request->product as $value) {
            if (!empty($value['count']) && !empty($value['name']) && !empty($value['price'])) {
                    $value['count'];
                    $value['price'];
                    $summa += $value['count'] * $value['price'];
                }
            }


        if ($company->id == 1) {
            $token = '899402388:AAGN3qBBAViYvmImtZ6Az0zWlL4-6ZDpPG4';
            $chat_id = '-350841895';
            $text = 'Счет ' . ucfirst($_SERVER['SERVER_NAME']) . ' Номер счета: ' . $maxNumber;

            $url = "https://api.telegram.org/bot" . $token . "/sendMessage?disable_web_page_preview=true&chat_id=" . $chat_id . "&text=" . urlencode($text);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_exec($ch);
            curl_close($ch);

        }
            $income = Income::make([
                'category' => $request->category,
                'account_number' => $maxNumber,
                'sum' => $summa,
                'user' => $request->user,
                'comment' => $request->comment,
                'company_id' => $company->id,
                'income_date' => $request->income_date ? new Carbon($request->income_date) : null,
                'date' => $request->date ? new Carbon($request->date) : null,
                'service' => $request->service ?: null,
                'client' => $request->category,
                'pay_service' => $request->pay_service ?: null,
                'is_payed' => $request->is_payed ? true : false,
                'plan_date' => $request->plan_date ? new Carbon($request->plan_date) : null,
                'client_cheking_accounts' => $request->client_cheking_accounts,
                'id_project' => $request->project_id,

            ]);

            $income->saveOrFail();

            foreach ($request->product as $value) {
                if (!empty($value['count']) && !empty($value['name']) && !empty($value['price'])) {
                $product = Product::make([
                    'product' => $value['name'],
                    'count' => $value['count'],
                    'price' => $value['price'],
                    'income_id' => $income->id,
                ]);
                $product->saveOrFail();
                $products[] = $product;
            }
        }

            return $income;
        });
    }


    public function update(IncomeRequest $request, Income $income)
    {
        $summa = 0;
        foreach ($request->product as $value) {
            if (!empty($value['count']) && !empty($value['name']) && !empty($value['price'])) {
                $value['count'];
                $value['price'];
                $summa += $value['count'] * $value['price'];
            }
        }

        return DB::transaction(function () use ($request, $income, $summa) {
            foreach ($income->products as $product) {
                $product->delete();
            }

            foreach ($request->product as $value) {
                if (!empty($value['count']) && !empty($value['name']) && !empty($value['price'])) {
                    $product = Product::make([
                        'product' => $value['name'],
                        'count' => $value['count'],
                        'price' => $value['price'],
                        'income_id' => $income->id,
                    ]);
                    $product->saveOrFail();
                }
            }

            $project = $income->project;

            if (!empty($project)) {
                DB::transaction(function () use ($project, $income, $summa) {

                    $project = $project->update([
                        'fact_income' => $project->fact_income - $income->sum + $summa,
                    ]);

                });
            }

            $income = $income->update([
                'category' => $request->category,
                'account_number' => $request->account_number,
                'sum' => $summa,
                'user' => $request->user,
                'comment' => $request->comment,
                'income_date' => $request->income_date ? new Carbon($request->income_date) : null,
                'date' => $request->date ? new Carbon($request->date) : null,
                'service' => $request->service ?: null,
                'client' => $request->category,
                'pay_service' => $request->pay_service ?: null,
                'is_payed' => $request->is_payed ? true : false,
                'plan_date' => $request->plan_date ? new Carbon($request->plan_date) : null,
                'client_cheking_accounts' => $request->client_cheking_accounts,
                'id_project' => $request->project_id,]);


            return $income;
        });
    }
}
