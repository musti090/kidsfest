<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class TestController extends Controller
{
    public function test(Request $request)
    {


    //  return  $all = DB::table('collective_teenagers')->select('id','gender')->get();


      //  $all = DB::table('personal_users')->select('id','name')->get();
        $all = DB::table('collectives')->select('id','collective_name')->get();
        foreach ($all as $key1 => $value) {
            echo "<div>".$value->id."-". $value->collective_name."</div>";

            $teenagers = DB::table('collective_teenagers')->select('id')->where('collective_id',$value->id)->get();
            if($teenagers->count() > 0){
                foreach ($teenagers as $key2 => $teenager) {
                    echo "<div>".$teenager->id."</div>";
                }
            }
            else{
                echo "Yoxdur!!!";
            }


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

        exit;


    }


    public function add()
    {
        $eduschools =  DB::table('education_school_new_names')->get();
        $nax =  DB::table('nax')->get();

        foreach ($nax as $n) {
            DB::table('education_school_new_names')->insert([
                'name' => $n->name,
                'school_id' => $n->school_id
            ]);
        }
    }
}
