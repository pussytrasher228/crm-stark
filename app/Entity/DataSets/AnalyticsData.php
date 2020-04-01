<?php
/**
 * Created by PhpStorm.
 * User: qposer
 * Date: 24.09.18
 * Time: 1:30
 */

namespace App\Entity\DataSets;


use App\Entity\Company;
use App\Entity\Expense;
use App\Entity\ExpenseCategory;
use App\Entity\Income;
use Carbon\Carbon;

class AnalyticsData
{
    private $expenses;
    private $incomes;
    private $company;
    private $incomeCategories;
    private $expenseCategories;
    private $months = [];
    private $totalExpense = [];
    private $typeExpense = [];
    private $typeIncome = [];
    private $totalIncome = [];
    private $countOfIncomes = [];
    private $countOfExpenses = [];
    private $meanIncomes = [];
    private $meanExpenses = [];

    public function __construct(Company $company)
    {
        $this->company = $company;
        $this->incomeCategories = array_unique(Income::where([['service', '<>', null],['company_id', '=', $company->id]])->get()->pluck('service')->toArray());
        $this->expenseCategories = ExpenseCategory::where([['parent_id', '<>', null],['company_id', '=', $company->id]])->get();
    }

    public function calculate(Carbon $dateFrom = null, Carbon $dateTo = null)
    {
        if (!$dateFrom) {
            $this->calculate((new Carbon('first day of previous month')));
            $this->calculate((new Carbon('first day of this month')));
            return true;
        }

        if (!$dateTo) {
            $dateTo = (new Carbon($dateFrom))->endOfMonth();
        }

        $this->expenses = Expense::where([
            ['expense_date', '>=', $dateFrom->format('Y-m-d')],
            ['expense_date', '<=', $dateTo->format('Y-m-d')],
            ['company_id', '=', $this->company->id]
        ])->get();


//        $this->incomes = Income::where(
//            [
//                ['service', '<>', null],
//                'company_id' => $this->company->id,
//                ['income_date', '>=', $dateFrom->format('Y-m-d')],
//                ['income_date', '<=', $dateTo->format('Y-m-d')],
//            ]
//        )->get();

        $this->incomes =  $this->company->inIncomesServices($dateFrom ,$dateTo)->filter(function ($income) use ($dateFrom, $dateTo) {
            /** @var Income $income */
            if ($income->income_date && $income->income_date >= $dateFrom && $income->income_date <= $dateFrom){
                return false;
            }
            return true;
        });

        $this->months[] = $dateFrom->format('m/y');
        $monthKey = count($this->months) - 1;
        $sums = [];
        $incomeSums = [];

        foreach ($this->incomes as $income) {
            $incomeName = $income->service ?: '';
            if (!isset($incomeSums[$incomeName])) {
                $incomeSums[$incomeName] = 0;
            }

            $incomeSums[$incomeName] += $income->sum;
        }

        $keys = [];
        foreach ($incomeSums as $key => $incomeSum) {
            $keys[] = $key;
            $this->typeIncome[$key][] = $incomeSum;
        }


        $expenseKeys = [];
        foreach ($this->expenses as $expense) {
            $sums[] = $expense->sum;
            $expenseKeys[] = $expense->relateCategory->name;
            if (!isset($this->typeExpense[$expense->relateCategory->parent->name][$expense->relateCategory->name][$monthKey])) {
                $this->typeExpense[$expense->relateCategory->parent->name][$expense->relateCategory->name][$monthKey] = 0;
            }
            $this->typeExpense[$expense->relateCategory->parent->name][$expense->relateCategory->name][$monthKey] += $expense->sum;

        }


        foreach($this->incomeCategories as $incomeCategory) {
            if (!in_array($incomeCategory, $keys)) {
                $this->typeIncome[$incomeCategory][] = 0;
            }
        }

        foreach($this->expenseCategories as $expenseCategory) {
            if (!in_array($expenseCategory->name, $expenseKeys)) {
                $this->typeExpense[$expenseCategory->parent->name][$expenseCategory->name][$monthKey] = 0;
            }
        }


        $this->totalIncome[$monthKey] = array_sum($incomeSums);
        $this->countOfIncomes[$monthKey] = count($incomeSums);
        $this->meanIncomes[$monthKey] = count($incomeSums) > 0 ? array_sum($incomeSums) / count($incomeSums) : 0;
        $this->totalExpense[$monthKey] = array_sum($sums);
        $this->countOfExpenses[$monthKey] = count($sums);
        $this->meanExpenses[$monthKey] = count($sums) > 0 ? array_sum($sums) / count($sums) : 0;
    }

    public function getMonths()
    {
        return $this->months;
    }

    public function getTotalIncomes()
    {
        return $this->totalIncome;
    }

    public function getTypeIncome()
    {
        return $this->typeIncome;
    }

    public function getTotalExpense()
    {
        return $this->totalExpense;
    }

    public function getPrologue()
    {
        $expenses = $this->getTotalExpense();
        $incomes = $this->getTotalIncomes();

        $prologue = [];

        foreach ($incomes as $key => $income) {
            $prologue[] = $income - $expenses[$key];
        }
        return $prologue;
    }

    public function getTotalPrologue()
    {
        $expenses = $this->getTotalExpense();
        $incomes = $this->getTotalIncomes();

        $prologue = [];
        foreach ($incomes as $key => $income) {
            $prologue[] = ($key > 0 ? $prologue[$key - 1] : 0) + $income - $expenses[$key];
        }
        return $prologue;
    }

    public function getExpensesByType($type)
    {
        $returnAr = [];
        if (isset($this->typeExpense[$type])) {
            $ar = $this->typeExpense[$type];
            $i = 0;
            while (true) {
                $exit = false;
                foreach ($ar as $value) {
                    if (!isset($value[$i])) {
                        $exit = true;
                        break;
                    }
                    $returnAr[$i] = 0;
                    $returnAr[$i] += array_sum(
                        array_map(function ($ar) use ($i) {
                            return $ar[$i];
                        }, $ar)
                    );
                }
                if ($exit) { break; }
                $i++;
            }
        }
        return $returnAr;
    }

    public function getTypeExpense()
    {
        return $this->typeExpense;
    }

    public function getCountOfIncomes()
    {
        return $this->countOfIncomes;
    }

    public function getCountOfExpenses()
    {
        return $this->countOfExpenses;
    }

    public function getMeanExpenses()
    {
        return $this->meanExpenses;
    }

    public function getMeanIncomes()
    {
        return $this->meanIncomes;
    }
}
