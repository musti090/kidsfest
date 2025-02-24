<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nomination extends Model
{
    use HasFactory;

    public function nomination_has_precinct()
    {
        return $this->belongsToMany(Precinct::class,'precincts_has_nominations');
    }
    public function nomination_has_precinct_second_step()
    {
        return $this->belongsToMany(SecondStepPrecinct::class,'second_step_precincts_has_nominations');
    }
    public function nomination_has_precinct_third_step()
    {
        return $this->belongsToMany(ThirdStepPrecinct::class,'third_step_precincts_has_nominations');
    }
    public function nomination_has_criterion()
    {
        return $this->belongsToMany(Criterion::class,'criterion_nominations');
    }
}
