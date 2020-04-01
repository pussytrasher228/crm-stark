<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

class RegisterAct extends Model
{
    public $table = "register_acts";
    protected $fillable = [
        'number',
        'date',
        'client_id',
        'pay_service',
        'company_id',
        'file_id',
        'comments',
    ];
    protected $casts = [
        'date' => 'date',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function normalClient()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    public function payService()
    {
        return $this->belongsTo(PayService::class, 'pay_service', 'id');
    }
}