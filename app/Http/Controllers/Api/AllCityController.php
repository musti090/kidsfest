<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AllCity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AllCityController extends Controller
{
    public function index()
    {
         Cache::forget('AllCity');
        $data = Cache::rememberForever('AllCity', function ()  {
            return AllCity::select('id','city_name')->get();
        });
        return  response($data,200);
    }
}
