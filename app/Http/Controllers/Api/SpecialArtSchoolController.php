<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MNRegion;
use App\Models\SpecialArtSchoolRegion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SpecialArtSchoolController extends Controller
{
    public function index()
    {
        //Cache::forget('SpecialArtSchoolRegion');
        $data = Cache::rememberForever('SpecialArtSchoolRegion', function ()  {
            return SpecialArtSchoolRegion::select('id','name')->get();

        });

        return  response($data,200);
    }
}
