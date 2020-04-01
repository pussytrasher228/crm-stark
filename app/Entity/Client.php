<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'name',
        'company_id',
        'syte',
        'disabled',
    ];

    protected $casts = [
        'disabled' => 'bool',
    ];

    public function checkingAccounts()
    {
        return $this->hasMany(ClientCheckingAccount::class, 'client_id', 'id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function incomes()
    {
        return $this->hasMany(Income::class, 'client', 'id');
    }

    public function acts()
    {
        return $this->hasMany(Act::class, 'client', 'id');
    }

    public function employee()
    {
        return $this->hasMany(Employee::class, 'clients_id', 'id');
    }
}
