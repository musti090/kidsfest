<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalUserCardInformation extends Model
{
    use HasFactory;

    public function personal_user_form_information()
    {
        return $this->belongsTo(PersonalUser::class,'personal_user_id');
    }

    public function personal_user_parent_information()
    {
        return $this->hasOne(PersonalUserParent::class,'personal_user_id','personal_user_id');
    }

    public function personal_user_awards()
    {
        return $this->hasMany(Award::class,'personal_user_id','personal_user_id');
    }
}
