<?php

namespace App\Http\Controllers\Company;

use App\Entity\Company;
use App\Entity\Expense;
use App\Entity\ExpenseCategory;
use App\Http\Requests\Company\CategoryRequest;
use App\Http\Requests\Company\ExpenseRequest;
use App\Services\ExpenseService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExpenseCategoryController extends Controller
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

        $categories = ExpenseCategory::where(['company_id' => $company->id, 'parent_id' => null])->get();

        return view('category.create', compact('company','categories'));
    }

    public function store(CategoryRequest $request)
    {
        //$company = Company::where(['token' => $token])->first();
        $company = Auth::user()->company;

        $this->service->createCategory($request, $company);

        return redirect()->route('admin', ['token' => $company->token]);
    }

    public function edit(string $id)
    {
        //$company = Company::where(['token' => $token])->first();
        $company = Auth::user()->company;

        $category = ExpenseCategory::findOrFail($id);

        $categories = ExpenseCategory::where(['company_id' => $company->id, 'parent_id' => null])->get();

        return view('category.edit', compact('company', 'category', 'categories'));
    }

    public function update(CategoryRequest $request, string $id)
    {
        //$company = Company::where(['token' => $token])->first();
        $company = Auth::user()->company;

        $category = ExpenseCategory::findOrFail($id);

        $this->service->updateCategory($request, $category);

        return redirect()->route('admin', ['token' => $company->token]);
    }
}
