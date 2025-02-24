<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalUserCardInformation extends Model
{
    use HasFactory;

    protected $fillable = ['personal_user_id','all_city_id','UIN','fin_code','card_old_or_new','serial_number','year','age'];
    public function personal_user_form_information()
    {
        return $this->belongsTo(PersonalUser::class, 'personal_user_id');
    }

    public function personal_user_parent_information()
    {
        return $this->hasOne(PersonalUserParent::class, 'personal_user_id', 'personal_user_id');
    }

    public function personal_user_awards()
    {
        return $this->hasMany(Award::class, 'personal_user_id', 'personal_user_id');
    }
}
