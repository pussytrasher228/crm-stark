<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

class Regular extends Model
{
    public $table = "regular_clients";

    protected $fillable = [
        'date',
        'company_id',
        'client',
        'service',
        'pay_service',
        'sum',
        'disabled',
        'comment'
    ];

    protected $casts = [
        'date' => 'date',
        'disabled' => 'boolean',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function getFromDateAttribute() {
        return \Carbon\Carbon::parse($value)->format('d-m-Y');
    }

    public function normalClient()
    {
        return $this->belongsTo(Client::class, 'client', 'id');
    }

    public function payService()
    {
        return $this->belongsTo(PayService::class, 'pay_service', 'id');
    }

    public function activePayServices()
    {
        return $this->payServices->filter(function ($pay_services) {
            return $pay_services->income;
        });
    }

}
