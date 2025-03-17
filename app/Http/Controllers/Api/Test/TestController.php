<?php

namespace App\Http\Controllers\Api\Test;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class TestController extends Controller
{
    public function test(Request $request)
    {
        //  return uniqid();
/*        $fin_code = $request->fin_code;
        $serial_number = $request->serial_number;
        if ($request->card_old_or_new != 2) {
            $serial_number = "AA" . $serial_number;
        }
        $data = Http::withHeaders(['X-Bridge-Authorization' => env('ASAN_TOKEN')])
            ->get(env('ASAN_URL') . '?documentNumber=' . $serial_number . '&fin=' . $fin_code)->json();

        $data = $data['data'];
        return    $data = $data[0];

        return      $person_info = $data['personAz'];
   return     $person_info['images'][0]['imageStream'];*/

    //  return  $all = DB::table('collective_teenagers')->select('id','gender')->get();


        $all = DB::table('personal_users')->select('id','name')->get();
        foreach ($all as $key => $value) {
            echo "<div>".$value->id."-". $value->name."</div>";

           /* $photo1 = explode(".", $value->photo)[1];
            $photo0 = explode(".", $value->photo)[0];*/

             /*  if (!($photo1 == 'JPG' || $photo1 == 'jpeg' || $photo1 == 'jpg'|| $photo1 == 'jfif'
                      || $photo1 == 'webp' || $photo1 == 'png'
                      || $photo1 == 'htm' || $photo1 == 'PNG' || $photo1 == 'bmp' || $photo1 == 'BMP')) {
                    echo "<div>". $value->id." ".explode(".",$value->photo)[1]."</div>";
                  }*/

           /* if ($photo1 == 'heic') {
                $photo = $photo0.".jpg";

                DB::table('personal_users')
                    ->where('id', $value->id)
                    ->update(['photo' => $photo]);

                // echo   "<div>" . $value->id . " " . explode(".", $value->photo)[1] . "</div>";
            }
            else{
               echo 'Salam';
            }*/
        }
        //   $arr = explode(".",$all)[1];

       // exit;


    }
}
