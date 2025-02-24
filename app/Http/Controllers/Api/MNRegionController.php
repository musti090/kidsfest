<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MNRegion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MNRegionController extends Controller
{
    public function index()
    {
        Cache::forget('MNRegion');
        $data = Cache::rememberForever('MNRegion', function ()  {
            return MNRegion::select('id','name')->get();
        });

        return  response($data,200);
    }
}
