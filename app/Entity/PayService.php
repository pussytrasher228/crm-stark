<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

class PayService extends Model
{
    protected $fillable = [
        'name',
        'checking_account',
        'ks',
        'inn',
        'kpp',
        'bik',
        'bank_name',
        'bank_account',
        'ur_address',
        'fact_address',
        'mail_address',
        'company_id',
        'type_company',
        'income',
    ];

    protected $casts = [
        'income' => 'boolean',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }
    public static function getPayService($pay)
    {
        return PayService::where('name', $pay)->get();
    }
    public function incomePayServices()
    {
        return  PayService::where('income', true)->get();
    }

}
