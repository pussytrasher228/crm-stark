<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

class IncomePlan extends Model
{
    protected $table = 'income_plans';

    protected $fillable = [
        'month',
        'year',
        'plan',
        'company',
        'mounth_name',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company', 'id');
    }

    public function getMounth()
    {
        $mounth = [
            'Январь',
            'Февраль',
            'Март',
            'Апрель',
            'Май',
            'Июнь',
            'Июль',
            'Август',
            'Сентябрь',
            'Октябрь',
            'Ноябрь',
            'Декабрь'
        ];

        return $mounth;
    }
}
