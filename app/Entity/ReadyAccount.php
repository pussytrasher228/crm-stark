<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

class ReadyAccount extends Model
{
    public $table = "ready_account";
    public $timestamps = false;

    protected $fillable = [
        'name',
        'checking_account',
        'pay_services',
        'ks',
        'inn',
        'kpp',
        'bik',
        'bank_name',
        'account_number',
        'services',
        'sum',
        'date',
    ];

}