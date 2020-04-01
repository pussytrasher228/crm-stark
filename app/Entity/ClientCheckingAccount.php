<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

class ClientCheckingAccount extends Model
{
    protected $table = 'client_checking_accounts';

    protected $fillable = [
        'name',
        'checking_account',
        'ks',
        'inn',
        'kpp',
        'bik',
        'bank_name',
        'ur_address',
        'fact_address',
        'mail_address',
        'client_id'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    public function act()
    {
        return $this->belongsTo(Act::class, 'id', 'client');
    }
}
