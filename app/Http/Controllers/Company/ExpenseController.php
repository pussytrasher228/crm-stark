<?php

namespace App\Http\Controllers\Company;

use App\Entity\Company;
use App\Entity\Expense;
use App\Entity\ExpenseCategory;
use App\Http\Requests\Company\ExpenseRequest;
use App\Services\ExpenseService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExpenseController extends Controller
{
    /**
     * @var ExpenseService
     */
    private $service;

    public function __construct(ExpenseService $service)
    {
        $this->service = $service;
    }

    public function create()
    {
        //$company = Company::where(['token' => $token])->first();
        $company = Auth::user()->company;

        $categories = ExpenseCategory::where(['parent_id' => null, 'company_id' => 1])->get();

        return view('expense.create', compact(
            'company',
            'categories'
        ));
    }

    public function store(ExpenseRequest $request)
    {
        //$company = Company::where(['token' => $token])->first();
        $company = Auth::user()->company;

        $this->service->create($request, $company);

        return redirect()->route('companiesExprenses.show', ['token' => $company->token]);
    }

    public function edit(string $expense)
    {
        $expense = Expense::findOrFail($expense);
        //$company = Company::where(['token' => $token])->first();
        $company = Auth::user()->company;

        if (!$company || $expense->company->id !== $company->id) {
            throw new NotFoundHttpException('Такой компании не найдено');
        }

        $categories = ExpenseCategory::where(['parent_id' => null])->get();

        return view('expense.edit', compact('company', 'expense', 'categories'));
    }

    public function update(ExpenseRequest $request, string $expense)
    {
        $expense = Expense::findOrFail($expense);
        //$company = Company::where(['token' => $token])->first();
        $company = Auth::user()->company;

        if (!$company || $expense->company->id !== $company->id) {
            throw new NotFoundHttpException('Такой компании не найдено');
        }

        $this->service->update($request, $expense);

        return redirect()->route('companies.show', ['token' => $company->token]);
    }

    public function delete(string $expense)
    {
        $expense = Expense::findOrFail($expense);
        //$company = Company::where(['token' => $token])->first();
        $company = Auth::user()->company;

        if (!$company || $expense->company->id !== $company->id) {
            throw new NotFoundHttpException('Такой компании не найдено');
        }

        $project = $expense->project;

        if (!empty($project)) {
            return DB::transaction(function () use ($project, $expense, $company) {

                $project = $project->update([
                    'fact_expense' => $project->fact_expense - $expense->sum,
                ]);
                $expense->delete();
                return redirect()->route('companies.show', ['token' => $company->token]);
            });
        } else {
            $expense->delete();

            return redirect()->route('companies.show', ['token' => $company->token]);
        }

    }
}
