<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Logo extends Model
{
    public $table = "logo";
    public $timestamps = false;

    protected $fillable = [
        'company_id',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function uploadLogo($logo)
    {
        if($logo == null) {return;}
        if ($this->logo != null)
        {
            Storage::delete('uploads/' . $this->logo);
        }

        $filename = str_random(10) . '.' . $logo->extension();
        $logo->storeAs('uploads', $filename);
        $this->logo = $filename;
        $this->save();
    }

    public function getLogo()
    {
        return '/uploads/' . $this->logo;
    }
}
