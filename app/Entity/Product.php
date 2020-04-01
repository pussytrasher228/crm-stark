<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public $table = "product";
    public $timestamps = false;

    protected $fillable = [
        'product',
        'count',
        'price',
        'income_id',
    ];


    public function incomes()
    {
        return $this->hasOne(Income::class, 'id', 'income_id');
    }
}
