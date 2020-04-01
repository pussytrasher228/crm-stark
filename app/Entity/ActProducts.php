<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

class ActProducts extends Model
{
    public $table = "act_products";
    public $timestamps = false;
    protected $fillable = [
        'act_id',
        'product',
        'count',
        'price',
    ];

    public function Act()
    {
        return $this->hasOne(Act::class, 'id', 'act_id');
    }


}