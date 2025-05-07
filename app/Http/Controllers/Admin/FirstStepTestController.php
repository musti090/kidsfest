<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CollectiveAward;
use App\Models\PersonalSpecialCategory;
use App\Models\Award;
use App\Models\PersonalUserCode;
use App\Models\CollectiveUserCode;
use App\Models\City;
use App\Models\Collective;
use App\Models\CollectiveDirector;
use App\Models\MNRegion;
use App\Models\SecondStepPrecinct;
use App\Models\Nomination;
use App\Models\PersonalUser;
use App\Models\PersonalUserCardInformation;
use App\Models\PersonalUserParent;
use App\Models\Precinct;
use App\Models\PrecinctsHasNomination;
use App\Models\User;
use App\Models\UserForTest;
use App\Models\UserForTestSecondStep;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;

class FirstStepTestController extends Controller
{


    public function PrecinctHasNominations()
    {

        $precincts = Precinct::all();

        foreach ($precincts as $p) {
            $x = PrecinctsHasNomination::where('precinct_id', $p->id)->get();
            foreach ($x as $y) {
                $y->city_id = $p->city_id;
                $y->save();
            }
        }

    }


    public function testTable()
    {

         $tableNames = DB::table('tableName')->get();


        foreach ($tableNames as $tableName) {
            $personal_user = DB::table('personal_users')
                ->leftJoin('personal_user_card_information', 'personal_user_card_information.personal_user_id', '=', 'personal_users.id')
                ->where('personal_user_card_information.fin_code', '=', $tableName->FIN)
                ->first();

           if ($personal_user) {
                DB::table('personal_users')
                    ->where('id', $personal_user->id)
                    ->update([
                        'art_education' => $tableName->IM,
                        'art_type' => 1
                    ]);
            }

        }

    }

    public function menteqeUser()
    {
        /*
                // return  $personal = PersonalUserCardInformation::where('precinct_id',null)->get()->count();
                // return  $personal = CollectiveDirector::where('precinct_id',null)->get()->count();

                $personal = PersonalUserCardInformation::with('personal_user_form_information')->where('precinct_id',null)->orderBy('city_id','asc')->get();
                $kol = CollectiveDirector::with('collective_information')->where('precinct_id',null)->orderBy('city_id','asc')->get();
                echo "<h1>Fərdi</h1>";

                foreach ($personal as $k => $p){
                    echo ($k+1).") ".$p->personal_user_form_information->name." ".$p->personal_user_form_information->surname." ".$p->personal_user_form_information->patronymic." | ".City::where('id',$p->city_id)->first()->city_name." | ".Nomination::where('id',$p->personal_user_form_information->nomination_id)->first()->name." | İştirakçı kodu: "."<b>$p->UIN</b>"."<br>";
                }
                echo "<h1>Kollektiv</h1>";
                foreach ($kol as $t=>$p){
                    echo ($t+1).") ".$p->collective_information->collective_name." | ".City::where('id',$p->city_id)->first()->city_name." | ".Nomination::where('id',$p->collective_information->collective_nomination_id)->first()->name." | Kollektivin kodu: "."<b>$p->UIN</b>"."<br>";
                  //  echo City::where('id',$p->city_id)->first()->city_name."--------->".Nomination::where('id',$p->collective_information->collective_nomination_id)->first()->name."<br>";
                }
                return 55;
        */


        /*
                 $precincts = Precinct::orderBy('id','asc')->get();
                                foreach ($precincts as $k => $p){
                                    $parol = Str::random(6).'@'.Str::random(6);
                                   // echo $p->place_name."<br>";
                                    $user = new User();
                                    $user->city_id = $p->city_id;
                                    $user->precinct_id = $p->id;
                                    $user->name = $p->place_name;
                                    $user->username  = "User".($k + 1);
                                    $user->email  = "user".($k + 1)."@gmail.com";
                                    $user->password = Hash::make($parol);
                                    $user->is_admin = 1;
                                    $user->save();

                                    $nu = new UserForTest();
                                    $nu->city_id = $p->city_id;
                                    $nu->precinct_id = $p->id;
                                    $nu->name = $p->place_name;
                                    $nu->username  = "User".($k + 1);
                                    $nu->email  = "user".($k + 1)."@gmail.com";
                                    $nu->parol = $parol;
                                    $nu->save();

                                }

                              $users = User::all();
                              foreach($users as $k => $u){
                                  if($u->id > 3){
                                    //  echo $u->id."<br>";
                                      DB::table('model_has_roles')->updateOrInsert([
                                          'role_id' => 3,
                                          'model_type' => 'App\Models\User',
                                          'model_id' => $u->id
                                      ]);
                                  }
                              }
        */
        $data = UserForTest::all();
        return view('backend.pages.menteqeUser.index', compact('data'));
    }


}
