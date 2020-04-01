<?php
/**
 * Created by PhpStorm.
 * User: qposer
 * Date: 29.09.18
 * Time: 23:45
 */

namespace App\Entity\DataSets;


class ChartData
{

    const COLORS = [
        'red' =>  'rgba(235,0,0,1)',
        'blue' => 'rgba(0,0,235,1)',
        'green' => 'rgba(0,235,0,1)',
    ];

    const MONTHS = [
        1 => 'Январь',
        2 => 'Февраль',
        3 => 'Март',
        4 => 'Апрель',
        5 => 'Май',
        6 => 'Июнь',
        7 => 'Июль',
        8 => 'Август',
        9 => 'Сентябрь',
        10 => 'Октябрь',
        11 => 'Ноябрь',
        12 => 'Декабрь',
    ];

    public static function getRandColors(int $count)
    {
        $colors = [];

        for ($i = 0; $i < $count; $i++) {
            $colors[] = 'rgba(' . rand(0, 255) . ',' . rand(0,255) . ',' . rand(0,255) . ',' . rand(7, 10) / 10 . ')';
        }

        return $colors;
    }

    public static function getMonth(int $key)
    {
        return self::MONTHS[$key];
    }

}
