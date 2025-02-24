<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducationSchoolName extends Model
{
    use HasFactory;

    protected $fillable = ['school_id','name','district'];
    protected $appends = ['full_name'];

    public function getFullNameAttribute()
    {
        return $this->district."/".$this->name;
    }

}
