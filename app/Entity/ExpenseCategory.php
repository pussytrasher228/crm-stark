<?php

namespace App\Entity;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ExpenseCategory extends Model
{
    protected $fillable = [
        'name',
        'parent_id',
        'company_id',
        'disabled',
    ];

    protected $casts = [
        'updated_at' => 'date',
        'created_at' => 'date',
        'disabled' => 'bool',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id', 'id');
    }

    public function childs()
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }
}
