<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalUser extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function personal_card_information(){
        return $this->hasOne(PersonalUserCardInformation::class,'personal_user_id');
    }

    public function personal_parent_information(){
        return $this->hasOne(PersonalUserParent::class,'personal_user_id');
    }

  
}
