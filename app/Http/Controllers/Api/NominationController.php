<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Nomination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class NominationController extends Controller
{
    public function personalNominations()
    {
        Cache::forget('personalNominations');
        $data = Cache::rememberForever('personalNominations', function () {
            return Nomination::select('id', 'name')->where('type', '=', 1)->get();

        });
        return response($data, 200);
    }

    public function collectiveNominations()
    {
        Cache::forget('collectiveNominations');
        $data = Cache::rememberForever('collectiveNominations', function () {
            return Nomination::select('id', 'name','message')->where('type', '=', 2)->get();
        });

        return response($data, 200);
    }
}
