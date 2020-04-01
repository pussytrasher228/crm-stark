<?php

namespace App\Entity;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'name',
        'email',
        'position',
        'phone',
        'comment',
        'clients_id',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'clients_id', 'id');
    }
}