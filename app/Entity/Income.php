<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    const PAY_SERVICES = [
        'Наличные',
        'ООО "Хорс"',
        'ИП Сотников В.Д.',
    ];

    protected $fillable = [
        'category',
        'sum',
        'user',
        'comment',
        'company_id',
        'income_date',
        'client',
        'service',
        'pay_service',
        'external_id',
        'account_number',
        'date',
        'is_payed',
        'plan_date',
        'client_cheking_accounts',
        'external_id',
        'ready_acts',
        'id_project',
    ];

    protected $casts = [
        'income_date' => 'date',
        'plan_date' => 'date',
        'date' => 'date',
        'updated_at' => 'date',
        'created_at' => 'date',
        'is_payed' => 'boolean',
        'ready_acts' => 'boolean',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'id_project', 'id');
    }

    public function normalClient()
    {
        return $this->belongsTo(Client::class, 'client', 'id');
    }

    public function payService()
    {
        return $this->belongsTo(PayService::class, 'pay_service', 'id');
    }

    public function services()
    {
        return $this->belongsTo(Service::class, 'service', 'id');
    }

    public function activePayServices()
    {
        return $this->payServices->filter(function ($pay_services) {
            return $pay_services->income;
        });
    }


    public function products()
    {
        return $this->hasMany(Product::class, 'income_id', 'id');
    }

    public function isPay()
    {
        return $this::where('is_payed', 1)->get();

    }

    public function clientCheckingAccount()
    {
        return $this->hasOne(ClientCheckingAccount::class, 'id', 'client_cheking_accounts');
    }
    /**
     * Возвращает сумму прописью
     */
    public function num2str($num)
    {
        $nul='ноль';
        $ten=array(
            array('','один','два','три','четыре','пять','шесть','семь', 'восемь','девять'),
            array('','одна','две','три','четыре','пять','шесть','семь', 'восемь','девять'),
        );
        $a20=array('десять','одиннадцать','двенадцать','тринадцать','четырнадцать' ,'пятнадцать','шестнадцать','семнадцать','восемнадцать','девятнадцать');
        $tens=array(2=>'двадцать','тридцать','сорок','пятьдесят','шестьдесят','семьдесят' ,'восемьдесят','девяносто');
        $hundred=array('','сто','двести','триста','четыреста','пятьсот','шестьсот', 'семьсот','восемьсот','девятьсот');
        $unit=array( // Units
            array('копейка' ,'копейки' ,'копеек',	 1),
            array('рубль'   ,'рубля'   ,'рублей'    ,0),
            array('тысяча'  ,'тысячи'  ,'тысяч'     ,1),
            array('миллион' ,'миллиона','миллионов' ,0),
            array('миллиард','милиарда','миллиардов',0),
        );
        //
        list($rub,$kop) = explode('.',sprintf("%015.2f", floatval($num)));
        $out = array();
        if (intval($rub)>0) {
            foreach(str_split($rub,3) as $uk=>$v) { // by 3 symbols
                if (!intval($v)) continue;
                $uk = sizeof($unit)-$uk-1; // unit key
                $gender = $unit[$uk][3];
                list($i1,$i2,$i3) = array_map('intval',str_split($v,1));
                // mega-logic
                $out[] = $hundred[$i1]; # 1xx-9xx
                if ($i2>1) $out[]= $tens[$i2].' '.$ten[$gender][$i3]; # 20-99
                else $out[]= $i2>0 ? $a20[$i3] : $ten[$gender][$i3]; # 10-19 | 1-9
                // units without rub & kop
                if ($uk>1) $out[]= $this->morph($v,$unit[$uk][0],$unit[$uk][1],$unit[$uk][2]);
            } //foreach
        }
        else $out[] = $nul;
        $out[] = $this->morph(intval($rub), $unit[1][0],$unit[1][1],$unit[1][2]); // rub
        $out[] = $kop.' '.$this->morph($kop,$unit[0][0],$unit[0][1],$unit[0][2]); // kop
        return  trim(preg_replace('/ {2,}/', ' ', join(' ',$out)));
    }

    public function morph($n, $f1, $f2, $f5)
    {
        $n = abs(intval($n)) % 100;
        if ($n>10 && $n<20) return $f5;
        $n = $n % 10;
        if ($n>1 && $n<5) return $f2;
        if ($n==1) return $f1;
        return $f5;
    }
}
