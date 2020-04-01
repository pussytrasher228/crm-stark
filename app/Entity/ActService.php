<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

class ActService extends Model
{
    public $table = "act_service";
    protected $fillable = [
        'act_id',
        'income_id',
        'income_number',
    ];

    public function incomes()
    {
        return $this->hasMany(Income::class, 'id', 'income_id');
    }

    public function Act()
    {
        return $this->hasOne(Act::class, 'id', 'act_id');
    }


}