<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalUser extends Model
{
    use HasFactory;

    protected $fillable = ['image','name','surname','patronymic','birthDate','registration_address','live_address','gender','mn_region_id','school_type_id','school_id','nomination_id','art_type','art_school_id','art_education','year','created_at','updated_at'];

    public function personal_card_information(){
        return $this->hasOne(PersonalUserCardInformation::class,'personal_user_id');
    }

    public function personal_parent_information(){
        return $this->hasOne(PersonalUserParent::class,'personal_user_id');
    }


}
