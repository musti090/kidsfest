<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MNRegion extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function region_users()
    {
        return $this->belongsToMany(User::class,'admin_regions');
    }
   
}
