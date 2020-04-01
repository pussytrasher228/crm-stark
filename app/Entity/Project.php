<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'name',
        'company_id',
        'description',
        'plan_income',
        'plan_expense',
        'fact_income',
        'fact_expense',
        'date_start',
        'date_end',
    ];
    protected $casts = [
        'date_start' => 'date',
        'date_end' => 'date',
    ];

    public function expenses()
    {
        return $this->hasMany(Expense::class, 'id_project', 'id');
    }

    public function incomes()
    {
        return $this->hasMany(Income::class, 'id_project', 'id');
    }
}
