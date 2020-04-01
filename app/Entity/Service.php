<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'name',
        'company_id',
        'disabled',
        'income',
    ];

    protected $casts = [
        'income' => 'boolean',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }
}
