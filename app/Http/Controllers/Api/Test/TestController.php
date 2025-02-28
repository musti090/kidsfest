<?php

namespace App\Http\Controllers\Api\Test;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class TestController extends Controller
{
    public function test(Request $request)
    {

      //  return uniqid();
        $fin_code = $request->fin_code;
        $serial_number = $request->serial_number;
        if ($request->card_old_or_new != 2) {
            $serial_number = "AA" . $serial_number;
        }
     return   $data = Http::withHeaders(['X-Bridge-Authorization' => env('ASAN_TOKEN')])
            ->get(env('ASAN_URL') . '?documentNumber=' . $serial_number . '&fin=' . $fin_code)->json();

        $data = $data['data'];
        $data = $data[0];
    }
}
