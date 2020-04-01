<?php
/**
 * Created by PhpStorm.
 * User: qposer
 * Date: 24.09.18
 * Time: 0:34
 */

namespace App\Services;


use App\Entity\Company;
use App\Http\Requests\Company\CompanyRequest;
use Illuminate\Support\Facades\DB;

class CompanyService
{

    public function create(CompanyRequest $request): Company
    {
        return DB::transaction(function () use ($request) {

            $company = Company::make([
                'name' => $request->name,
                'token' => uniqid(),
            ]);

            $company->saveOrFail();

            return $company;
        });
    }

}
