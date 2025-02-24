<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollectiveDirector extends Model
{
    use HasFactory;

    public function collective_information()
    {
        return $this->belongsTo(Collective::class,'collective_id');
    }
    public function collective_teenagers()
    {
        return $this->hasMany(CollectiveTeenager::class,'collective_id','collective_id');
    }

    public function collective_awards_info()
    {
        return $this->hasMany(CollectiveAward::class,'collective_id','collective_id');
    }
    public function collective_user_images()
    {
        return $this->hasMany(CollectiveImage::class,'collective_id','collective_id');
    }
}
