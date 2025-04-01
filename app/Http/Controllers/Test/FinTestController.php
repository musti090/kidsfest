<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FinTestController extends Controller
{
    public function index(Request $request)
    {
        //  return uniqid();
        $fin_code = $request->fin_code;
        $serial_number = $request->serial_number;
        if ($request->card_old_or_new != 2) {
            $serial_number = "AA" . $serial_number;
        }
        $data = Http::withHeaders(['X-Bridge-Authorization' => env('ASAN_TOKEN')])
            ->get(env('ASAN_URL') . '?documentNumber=' . $serial_number . '&fin=' . $fin_code)->json();

        return $data = $data['data'];
        return    $data = $data[0];

        return      $person_info = $data['personAz'];
        return     $person_info['images'][0]['imageStream'];
    }
}
