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
use App\Entity\ExpenseCategory;
use App\Http\Requests\Company\CategoryRequest;
use App\Http\Requests\Company\ExpenseRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ExpenseService
{

    public function create(ExpenseRequest $request, Company $company)
    {
         DB::transaction(function () use ($request, $company) {

            $expense = Expense::make([
                'category' => $request->category,
                'sum' => $request->sum,
                'user' => $request->user,
                'comment' => $request->comment,
                'company_id' => $company->id,
                'expense_date' => $request->date ? new Carbon($request->date) : null,
                'id_project' => $request->project_id,
            ]);

            $expense->saveOrFail();
            $project = $expense->project;
            if (!empty($project)) {
                 DB::transaction(function () use ($project, $expense) {

                    $project = $project->update([
                        'fact_expense' => $project->fact_expense + $expense->sum,
                    ]);

                    return $expense;
                });
            }

            return $expense;
        });
    }

    public function update(ExpenseRequest $request, Expense $expense)
    {

        $project = $expense->project;
        if (!empty($project)) {
             DB::transaction(function () use ($project, $expense, $request) {

                $project = $project->update([
                    'fact_expense' => $project->fact_expense - $expense->sum + $request->sum,
                ]);


            });
        }
        return DB::transaction(function () use ($request, $expense) {

            $expense = $expense->update([
                'category' => $request->category,
                'sum' => $request->sum,
                'user' => $request->user,
                'comment' => $request->comment,
                'expense_date' => $request->date ? new Carbon($request->date) : null,
                'id_project' => $request->project_id,
            ]);

            return $expense;
        });
    }

    public function createCategory(CategoryRequest $request, Company $company)
    {
        return DB::transaction(function () use ($request, $company) {

            $category = ExpenseCategory::make([
                'name' => $request->name,
                'company_id' => $company->id,
                'parent_id' => $request->parent ?: null,
            ]);

            $category->saveOrFail();

            return $category;
        });
    }

    public function updateCategory(CategoryRequest $request, ExpenseCategory $category)
    {
        return DB::transaction(function () use ($request, $category) {

            $category->update([
                'name' => $request->name,
                'parent_id' => $request->parent ?: null,
                'disabled' => $request->disabled ? true : false,
            ]);

            return $category;
        });
    }

}
