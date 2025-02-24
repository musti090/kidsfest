<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalUserParent extends Model
{
    use HasFactory;

    public function personal_card_information(){
        return $this->hasOne(PersonalUserCardInformation::class,'personal_user_id','personal_user_id');
    }
}
