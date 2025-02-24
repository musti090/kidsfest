<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecondStepPrecinct extends Model
{
    use HasFactory;
    public function butun_sheherler()
    {
        return $this->belongsTo(AllCity::class,'all_city_id');
    }
}
