<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

class AccountNumber extends Model
{
    public $table = "account_number";
    public $timestamps = false;

    protected $fillable = [
        'account_number',
        'act_number',
        'company_id',
        'ip_act_number'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }
}
