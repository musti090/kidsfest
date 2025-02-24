<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducationSchool extends Model
{
    use HasFactory;

    public function school_names()
    {
        return $this->hasMany(EducationSchoolName::class,'school_id');
    }

}
