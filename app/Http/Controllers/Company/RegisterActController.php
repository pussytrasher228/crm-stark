<?php

namespace App\Http\Controllers\Company;

use App\Entity\Act;
use App\Entity\Client;
use App\Entity\ClientCheckingAccount;
use App\Entity\Company;
use App\Entity\Income;
use App\Entity\Logo;
use App\Entity\PayService;
use App\Entity\Product;
use App\Entity\ReadyAccount;
use App\Entity\RegisterAct;
use App\Entity\Regular;
use App\Entity\Service;
use App\Http\Requests\Company\IncomeRequest;
use App\Http\Controllers\Controller;
use App\Services\IncomeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PDF;

class RegisterActController extends Controller
{
    public function show(Request $request)
    {
        $company = Auth::user()->company;
        if (!$company) {
            throw new NotFoundHttpException('Компания не найдена');
        }

        $pay_servicess = $request->get('pay_service');



        $register = $company->registerActs()->orderBy('number', 'DESC')->orderBy('id', 'DESC')->get()->filter(function ($register) use ($pay_servicess) {

            if ($pay_servicess == $register->pay_service) {
                return true;
            }

            if (!$pay_servicess){
                return true;
            }
        });

        return view('registerAct.show', compact('company',
            'register',
            'pay_servicess'));
    }

    public function create()
    {
        $company = Auth::user()->company;
        return view('registerAct.create', compact('company'));
    }

    public function store(Request $request)
    {
        $company = Auth::user()->company;

        DB::transaction(function () use ($request, $company) {


            $registerAct = RegisterAct::make([
                'date' => $request->date ? new Carbon($request->date) : null,
                'company_id' => $company->id,
                'client_id' => $request->client,
                'pay_service' => $request->pay_service,
                'comment' => $request->comment,
                'number' => $request->number,
            ]);

            $registerAct->saveOrFail();

            return $registerAct;
        });

        return redirect()->route('registerAct.show', ['token' => $company->token]);
    }

    public function edit(RegisterAct $registerAct)
    {
        $company = Auth::user()->company;

        return view('registerAct.edit', compact('company', 'registerAct'));
    }

    public function update(Request $request, RegisterAct $registerAct)
    {
        $company = Auth::user()->company;

        if (!$company || $registerAct->company->id !== $company->id) {
            throw new NotFoundHttpException('Такой компании не найдено');
        }

        return DB::transaction(function () use ($request, $registerAct, $company) {

            $registerAct->update([
                'date' => $request->date ? new Carbon($request->date) : null,
                'company_id' => $company->id,
                'client_id' => $request->client,
                'pay_service' => $request->pay_service,
                'comment' => $request->comment,
                'number' => $request->number,
            ]);

            return redirect()->route('registerAct.show', ['token' => $company->token]);

        });
    }

    public function delete(RegisterAct $registerAct)
    {
        $company = Auth::user()->company;

        if (!$company || $registerAct->company->id !== $company->id) {
            throw new NotFoundHttpException('Такой компании не найдено');
        }

        $registerAct->delete();

        return redirect()->route('registerAct.show', ['token' => $company->token]);
    }

}