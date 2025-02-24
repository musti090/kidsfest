<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollectiveTeenager extends Model
{
    use HasFactory;

    public function collective_director()
    {
        return $this->belongsTo(CollectiveDirector::class,'collective_id','collective_id');
    }
}
