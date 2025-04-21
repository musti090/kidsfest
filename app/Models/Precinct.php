<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Precinct extends Model
{
    use HasFactory;

    protected $table = 'precincts';
    public function sheher()
    {
        return $this->belongsTo(AllCity::class,'city_id');
    }
}
