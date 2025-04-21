<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JudgesList extends Model
{
    use HasFactory;

    protected $table = 'judges_list';
    protected $fillable = ['name'];
}
