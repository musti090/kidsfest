<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collective extends Model
{
    use HasFactory;

    protected $fillable = ['collective_nomination_id'];

    public function collective_users()
    {
        return $this->hasMany(CollectiveTeenager::class,'collective_id');
    }
	   public function collective_director()
    {
        return $this->hasOne(CollectiveDirector::class,'collective_id');
    }
}
