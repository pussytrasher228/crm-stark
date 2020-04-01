<?php

namespace App\Http\Controllers\Company;

use App\Entity\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class ProjectController extends Controller
{
    public function show()
    {
        $company = Auth::user()->company;

        return view('project.show',  compact('company'));
    }

    public function create()
    {
        //$company = Company::where(['token' => $token])->first();
        $company = Auth::user()->company;

        return view('project.create', compact('company'));
    }

    public function store(Request $request)
    {
        $company = Auth::user()->company;

        $project = Project::make([
            'name' => $request->name,
            'description' => $request->description,
            'plan_income' => $request->plan_income,
            'plan_expense' => $request->plan_expense,
            'company_id' => $company->id,
        ]);

        $project->saveOrFail();

        return redirect()->route('project', ['token' => $company->token]);
    }

    public function edit(Project $project)
    {
        $company = Auth::user()->company;
        return view('project.edit', compact('project',
        'company'
        ));
    }

    public function update(Request $request, Project $project)
    {
        $company = Auth::user()->company;
        $project = DB::transaction(function () use ($request, $project) {

            $project->update([
                'name' => $request->name,
                'description' => $request->description,
                'plan_income' => $request->plan_income,
                'plan_expense' => $request->plan_expense,
            ]);

            return $project;
        });

        return redirect()->route('project', ['token' => $company->token]);
    }

    public function remove(Project $project)
    {
        $company = Auth::user()->company;
        $project->delete();

        return redirect()->route('project', ['token' => $company->token]);
    }

    public function detals(Project $project)
    {
        $company = Auth::user()->company;
        return view('project.detals', compact('project',
            'company'
        ));
    }
}
