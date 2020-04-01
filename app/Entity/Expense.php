<?php

namespace App\Entity;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'category',
        'sum',
        'user',
        'comment',
        'company_id',
        'expense_date',
        'id_project',
    ];

    protected $casts = [
        'expense_date' => 'date',
        'updated_at' => 'date',
        'created_at' => 'date',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'id_project', 'id');
    }

    public function relateCategory()
    {
        return $this->hasOne(ExpenseCategory::class, 'id', 'category');
    }
}
