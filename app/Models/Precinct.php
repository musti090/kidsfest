<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Precinct extends Model
{
    use HasFactory;
    public function sheher()
    {
        return $this->belongsTo(City::class,'city_id');
    }
}
