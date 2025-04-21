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

class TestController extends Controller
{
    public function personalUserCode()
    {
         $puc = PersonalUserCode::/*skip(800)->take(100)->*/get();
        foreach ($puc as $k => $p){
           // echo $k + 1 ." ".$p->user_code.'<br>';
           $x = PersonalUserCardInformation::where('UIN',$p->user_code)->first();
           $x->is_final_pass = 1;
           $x->save();
        }
    }
    public function collectiveUserCode()
    {
        $puc = CollectiveUserCode::/*skip(800)->take(100)->*/get();
        foreach ($puc as $k => $p){
            // echo $k + 1 ." ".$p->user_code.'<br>';
            $x = CollectiveDirector::where('UIN',$p->user_code)->first();
            $x->is_final_pass = 1;
            $x->save();
        }
    }
    public function nomreler()
    {

      //  $per = PersonalUserParent::select('phone_number')->where('score','>=',30)->get();
        $per = PersonalUserParent::select('phone_number','parent_name')->whereHas('personal_card_information', function ($q) {
            $q->where('is_final_pass','=',1);
        })->get();
        $col = CollectiveDirector::select('phone_number')->where('is_final_pass','=',1)->get();

        return view('nomreler', compact('per', 'col'));
    }

    public function testimdi()
    {

        return view('testim');
    }

    public function testim2()
    {

        $personal = PersonalUserCardInformation::with('personal_user_form_information')->get();
        $collective = CollectiveDirector::with('collective_information')->get();
        foreach ($personal as $p) {
            /*   if ($p->personal_user_form_information->mn_region_id >= 10 && $p->personal_user_form_information->mn_region_id <= 21) {
                              echo $p->precinct_id = floor($p->personal_user_form_information->mn_region_id / 6)."<br>";
                           }*/
            /* $p->precinct_id = null;
               $p->save();*/
            if ($p->precinct_id == null) {
                if ($p->personal_user_form_information->mn_region_id >= 10 && $p->personal_user_form_information->mn_region_id <= 21) {
                    $p->precinct_id = rand(1, 3);
                    $p->save();
                } elseif ($p->personal_user_form_information->mn_region_id == 69) {
                    $p->precinct_id = rand(4, 7);
                    $p->save();
                } elseif ($p->personal_user_form_information->mn_region_id >= 1 && $p->personal_user_form_information->mn_region_id <= 2) {
                    $p->precinct_id = 8;
                    $p->save();
                }
            }
        }
        foreach ($collective as $c) {
            if ($c->precinct_id == null) {
                if ($c->collective_information->collective_mn_region_id >= 10 && $c->collective_information->collective_mn_region_id <= 21) {
                    $c->precinct_id = rand(1, 3);
                    $c->save();
                } elseif ($c->collective_information->collective_mn_region_id == 69) {
                    $c->precinct_id = rand(4, 7);
                    $c->save();
                } elseif ($c->collective_information->collective_mn_region_id >= 1 && $c->collective_information->collective_mn_region_id <= 2) {
                    $c->precinct_id = 8;
                    $c->save();
                }
            }
        }
    }

    public function testim3()
    {
        $regions = Cache::rememberForever('MNRegionCount', function () {
            return MNRegion::select('id', 'name')->get();
        });
        $personalNominations = Cache::rememberForever('personalNominations', function () {
            return Nomination::select('id', 'name', 'type')->where('type', 1)->get();
        });
        $collectiveNominations = Cache::rememberForever('collectiveNominations', function () {
            return Nomination::select('id', 'name', 'type')->where('type', 2)->get();
        });

        /*  foreach ($regions as $key => $r) {
              //  echo $r->name . "<br>";
              $pernomCount = [];
              $colnomCount = [];

              foreach ($personalNominations as $nom) {
                  $pernomCount[] = [
                      PersonalUser::has('personal_card_information')->where('mn_region_id', $r->id)->where('nomination_id', $nom->id)->get()->count(),
                      $nom->name
                  ];
              }
              foreach ($collectiveNominations as $col_nom) {
                  $colnomCount[] = [
                      Collective::has('collective_director')->where('collective_nomination_id', $col_nom->id)->where('collective_mn_region_id', $r->id)->get()->count(),
                      $col_nom->name
                  ];
              }*/
        // echo $pernomCount[1][0]."<br>";
        // echo "<hr>";


        return view('testim', compact('regions', 'personalNominations', 'collectiveNominations'));


    }

    public function testim($fin_code, $serial_number)
    {
        return $child_data = Http::withHeaders(['X-Bridge-Authorization' => env('ASAN_TOKEN')])
            ->get(env('ASAN_URL') . '?documentNumber=' . $serial_number . '&fin=' . $fin_code)->json()['data'];

//        $data = CollectiveTeenager::all();
//        foreach ($data as $d){
//            if(!$d->collective_director){
//                echo $d->id."<br>";
//            }
//        }


        //  return   CollectiveTeenager::find(30)->collective_director;
        /*        foreach ($data as $d):



                   $fin_code = $d->fin_code;
                    $serial_number = $d->serial_number;
                    $card_old_or_new = $d->card_old_or_new;
                    $id = $d->id;
                    if ($d->card_name == null) {
                        if ($card_old_or_new != 2) {
                            $serial_number = "AA" . $serial_number;
                        }
                        $child_data = Http::withHeaders(['X-Bridge-Authorization' => env('ASAN_TOKEN')])
                            ->get(env('ASAN_URL') . '?documentNumber=' . $serial_number . '&fin=' . $fin_code)->json()['data'];
                        $child_data = $child_data[0];

                        $yeni = CollectiveTeenager::find($id);
                        $yeni->card_name = $child_data['personAz']['name'] ?? null;
                        $yeni->card_surname = $child_data['personAz']['surname'] ?? null;
                        $yeni->card_patronymic = $child_data['personAz']['patronymic'] ?? null;
                        $yeni->card_birthDate = $child_data['personAz']['birthdate']['date'] ?? null;
                        $yeni->card_gender = $child_data['personAz']['gender'] ?? null;
                        $yeni->save();

                    }

                endforeach;*/


        /*  return  $child_data = Http::withHeaders(['X-Bridge-Authorization' => env('ASAN_TOKEN')])
            ->get(env('ASAN_URL') . '?documentNumber=' . $serial_number . '&fin=' . $fin_code)->json()['data'];*/
        /*$data = CollectiveTeenager::where('fin_code', '!=', null)->get();
        foreach ($data as $d):

            $fin_code = $d->fin_code;
            $serial_number = $d->serial_number;
            $card_old_or_new = $d->card_old_or_new;
            $id = $d->id;
            if ($d->card_name == null){
                if ($card_old_or_new != 2) {
                    $serial_number = "AA" . $serial_number;
                }
                $child_data = Http::withHeaders(['X-Bridge-Authorization' => env('ASAN_TOKEN')])
                    ->get(env('ASAN_URL') . '?documentNumber=' . $serial_number . '&fin=' . $fin_code)->json()['data'];
                $child_data = $child_data[0];

                $yeni = CollectiveTeenager::find($id);
                $yeni->card_name = $child_data['personAz']['name'] ?? null;
                $yeni->card_surname = $child_data['personAz']['surname'] ?? null;
                $yeni->card_patronymic = $child_data['personAz']['patronymic'] ?? null;
                $yeni->card_birthDate = $child_data['personAz']['birthdate']['date'] ?? null;
                $yeni->card_gender = $child_data['personAz']['gender'] ?? null;
                $yeni->save();

            }

        endforeach;
*/
        /*     $sil = PersonalUser::with('personal_card_information','personal_parent_information')->get();
             foreach ($sil as $key => $d){
                 if($d->personal_card_information == null){
                     echo 'pu'.$d->name."<br>";
                     $d->delete();
                 }
             }*/

        /*
                $data_per = PersonalUserCardInformation::with('personal_user_form_information', 'personal_user_parent_information')->get();
                foreach ($data_per as $key => $d) {
                    if ($d->personal_user_parent_information == null) {
                        echo 'val' . $d->UIN . "<br>";
                        $d->personal_user_form_information->delete();
                        $d->delete();
                    }
                }
                $data_per_in = PersonalUser::with('personal_card_information', 'personal_parent_information')->get();
                foreach ($data_per_in as $key => $d) {
                    if ($d->personal_card_information == null) {
                        echo $d->name . "<br>";
                        //$d->personal_user_form_information->delete();
                        $d->delete();
                    }
                }
                $data_col = Collective::with('collective_director')->get();
                foreach ($data_col as $key => $d) {
                    if ($d->collective_director == null) {
                        echo 'kolinfo' . $d->collective_name . "<br>";
                        $d->delete();
                    }

                }*/

        /*
               $regions = MNRegion::select('id', 'name')->get();
                $personal_region__count = [];
                $region__count = [];
                foreach ($regions as $r):
                    $region__count[] = [
                       PersonalUser::has('personal_card_information')->where('mn_region_id', $r->id)->get()->count(),
                        Collective::withCount('collective_users')->where('collective_mn_region_id', $r->id)->get()->count(),
                       $r->name
                    ];
                endforeach;


        print_r($region__count);*/


    }

    public function personaliSil($id_code)
    {
        $data = PersonalUserCardInformation::with('personal_user_form_information', 'personal_user_parent_information', 'personal_user_awards')->where('UIN', $id_code)->firstOrFail();

        if ($data->personal_user_form_information) {
            $data->personal_user_form_information->delete();
        }
        if ($data->personal_user_parent_information) {
            $data->personal_user_parent_information->delete();
        }
        if ($data->personal_user_awards) {
            foreach ($data->personal_user_awards as $dpa) {
                $dpa->delete();
            }

        }
        $data->delete();
        return "Silindi.";


    }

    public function kollektiviSil($id_code)
    {
        $data = CollectiveDirector::with('collective_information', 'collective_teenagers', 'collective_awards_info', 'collective_user_images')->where('UİN', $id_code)->firstOrFail();
        if ($data->collective_user_images) {

            foreach ($data->collective_user_images as $cui) {
                $cui->delete();
            }
        }

        if ($data->collective_information) {
            $data->collective_information->delete();
        }
        if ($data->collective_teenagers) {
            foreach ($data->collective_teenagers as $ct) {
                $ct->delete();
            }
        }
        if ($data->collective_awards_info) {
            foreach ($data->collective_awards_info as $cai) {
                $cai->delete();
            }

        }
        $data->delete();
        return "Silindi.";
    }

    public function testim4()
    {
        $data = PersonalUserCardInformation::with('personal_user_form_information', 'personal_user_parent_information')->where('mn_region_old_id', 84)->get();
        return view('backend.pages.dashboard.zensus', compact('data'));
    }

    public function testim4kol()
    {
        $data = CollectiveDirector::with('collective_information')->where('collective_mn_region_old_id', 84)->get();
        return view('backend.pages.dashboard.zensus-col', compact('data'));
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
        /*      $users = User::all();
              foreach($users as $u){
                  if($u->id > 19){
                      echo $u->id."<br>";
                      DB::table('model_has_roles')->updateOrInsert([
                          'role_id' => 3,
                          'model_type' => 'App\Models\User',
                          'model_id' => $u->id
                      ]);
                  }
              }*/
        /*        $precincts = Precinct::orderBy('place_name','asc')->get();
                foreach ($precincts as $k => $p){
                    $parol = Str::random(14);
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

                }*/
        $data = UserForTest::all();
        return view('backend.pages.menteqeUser.index', compact('data'));
    }
    public function menteqeUser2()
    {
/*
           $users = User::all();
              foreach($users as $u){
                  if($u->id > 141){
                      echo $u->id."<br>";
                      DB::table('model_has_roles')->updateOrInsert([
                          'role_id' => 3,
                          'model_type' => 'App\Models\User',
                          'model_id' => $u->id
                      ]);
                  }
              }
*/
        /*
      $precincts = SecondStepPrecinct::orderBy('place_name','asc')->get();
                foreach ($precincts as $k => $p){
                    $parol = Str::random(14);
                   // echo $p->place_name."<br>";
                    $user = new User();
                    $user->all_city_id = $p->all_city_id;
                    $user->second_step_precinct_id = $p->id;
                    $user->name = $p->place_name;
                    $user->username  = "User".($k + 1);
                    $user->email  = "user".($k + 1)."@gmail.com";
                    $user->password = Hash::make($parol);
                    $user->is_admin = 1;
                    $user->save();

                    $nu = new UserForTestSecondStep();
                    $nu->all_city_id = $p->all_city_id;
                    $nu->second_step_precinct_id = $p->id;
                    $nu->name = $p->place_name;
                    $nu->username  = "User".($k + 1);
                    $nu->email  = "user".($k + 1)."@gmail.com";
                    $nu->parol = $parol;
                    $nu->save();

                }
*/
       $data = UserForTestSecondStep::all();
    return view('backend.pages.menteqeUser.secondStep', compact('data'));
    }

    public function menteqeIstirakci()
    {

/*

        $personal = PersonalUserCardInformation::get();
        $kol = CollectiveDirector::get();

          foreach ($personal as $p){
              $p->city_id = null;
              $p->precinct_id = null;
              $p->date = null;
              $p->time = null;
              $p->save();

          }
          foreach ($kol as $p){
              $p->city_id = null;
              $p->precinct_id = null;
              $p->date = null;
              $p->time = null;
              $p->save();

          }
*/

        /*****************************************************************************************************************************************************************/
/*
                $personal = PersonalUserCardInformation::with('personal_user_form_information')->where('city_id', null)->get();
                foreach ($personal as $p) {
                    if ($p->personal_user_form_information->mn_region_id >= 10 && $p->personal_user_form_information->mn_region_id <= 21) {
                        $p->city_id = 1;
                        $p->save();
                    }
                    elseif ($p->personal_user_form_information->mn_region_id == 69) {
                        $p->city_id = 2;
                        $p->save();
                    }
                    elseif ($p->personal_user_form_information->mn_region_id >= 1 && $p->personal_user_form_information->mn_region_id <= 2) {
                        $p->city_id = 3;
                        $p->save();
                    }
                    elseif ($p->personal_user_form_information->mn_region_id == 38) {
                        $p->city_id = 4;
                        $p->save();
                    }
                    elseif ($p->personal_user_form_information->mn_region_id == 7) {
                        $p->city_id = 5;
                        $p->save();
                    }
                    elseif ($p->personal_user_form_information->mn_region_id == 42) {
                        $p->city_id = 6;
                        $p->save();
                    }
                    elseif ($p->personal_user_form_information->mn_region_id == 49) {
                        $p->city_id = 7;
                        $p->save();
                    }
                    elseif ($p->personal_user_form_information->mn_region_id == 72) {
                        $p->city_id = 8;
                        $p->save();
                    }
                    elseif ($p->personal_user_form_information->mn_region_id == 32) {
                        $p->city_id = 9;
                        $p->save();
                    }
                    elseif ($p->personal_user_form_information->mn_region_id == 29) {
                        $p->city_id = 10;
                        $p->save();
                    }
                    elseif ($p->personal_user_form_information->mn_region_id == 58) {
                        $p->city_id = 11;
                        $p->save();
                    }
                    elseif ($p->personal_user_form_information->mn_region_id == 33) {
                        $p->city_id = 12;
                        $p->save();
                    }
                    elseif ($p->personal_user_form_information->mn_region_id == 35) {
                        $p->city_id = 13;
                        $p->save();
                    }
                    elseif ($p->personal_user_form_information->mn_region_id == 66) {
                        $p->city_id = 14;
                        $p->save();
                    }
                    elseif ($p->personal_user_form_information->mn_region_id == 3) {
                        $p->city_id = 15;
                        $p->save();
                    }
                    elseif ($p->personal_user_form_information->mn_region_id == 4) {
                        $p->city_id = 16;
                        $p->save();
                    }
                    elseif ($p->personal_user_form_information->mn_region_id == 24) {
                        $p->city_id = 17;
                        $p->save();
                    }
                    elseif ($p->personal_user_form_information->mn_region_id == 30) {
                        $p->city_id = 18;
                        $p->save();
                    }
                    elseif ($p->personal_user_form_information->mn_region_id == 78) {
                        $p->city_id = 19;
                        $p->save();
                    }
                    elseif ($p->personal_user_form_information->mn_region_id == 6) {
                        $p->city_id = 20;
                        $p->save();
                    }
                    elseif ($p->personal_user_form_information->mn_region_id == 31) {
                        $p->city_id = 21;
                        $p->save();
                    }
                    elseif ($p->personal_user_form_information->mn_region_id == 47) {
                        $p->city_id = 22;
                        $p->save();
                    }
                    elseif ($p->personal_user_form_information->mn_region_id == 74) {
                        $p->city_id = 23;
                        $p->save();
                    }
                    elseif ($p->personal_user_form_information->mn_region_id == 79) {
                        $p->city_id = 24;
                        $p->save();
                    }
                    elseif ($p->personal_user_form_information->mn_region_id == 37) {
                        $p->city_id = 25;
                        $p->save();
                    }
                    elseif ($p->personal_user_form_information->mn_region_id == 50) {
                        $p->city_id = 26;
                        $p->save();
                    }
                    elseif ($p->personal_user_form_information->mn_region_id == 52) {
                        $p->city_id = 27;
                        $p->save();
                    }
                    elseif ($p->personal_user_form_information->mn_region_id == 68) {
                        $p->city_id = 28;
                        $p->save();
                    }
                    elseif ($p->personal_user_form_information->mn_region_id == 70) {
                        $p->city_id = 29;
                        $p->save();
                    }
                    elseif ($p->personal_user_form_information->mn_region_id == 8) {
                        $p->city_id = 30;
                        $p->save();
                    }
                    elseif ($p->personal_user_form_information->mn_region_id == 27) {
                        $p->city_id = 31;
                        $p->save();
                    }
                    elseif ($p->personal_user_form_information->mn_region_id == 54) {
                        $p->city_id = 32;
                        $p->save();
                    }
                    elseif ($p->personal_user_form_information->mn_region_id == 55) {
                        $p->city_id = 34;
                        $p->save();
                    }
                    elseif ($p->personal_user_form_information->mn_region_id == 56) {
                        $p->city_id = 35;
                        $p->save();
                    }
                    elseif ($p->personal_user_form_information->mn_region_id == 81) {
                        $p->city_id = 36;
                        $p->save();
                    }
                    elseif ($p->personal_user_form_information->mn_region_id == 57) {
                        $p->city_id = 37;
                        $p->save();
                    }
                    elseif ($p->personal_user_form_information->mn_region_id == 5) {
                        $p->city_id = 38;
                        $p->save();
                    }
                    elseif ($p->personal_user_form_information->mn_region_id == 80) {
                        $p->city_id = 39;
                        $p->save();
                    }
                    elseif ($p->personal_user_form_information->mn_region_id == 85) {
                        $p->city_id = 40;
                        $p->save();
                    }
                    elseif ($p->personal_user_form_information->mn_region_id == 34) {
                        $p->city_id = 41;
                        $p->save();
                    }
                    elseif ($p->personal_user_form_information->mn_region_id == 45) {
                        $p->city_id = 42;
                        $p->save();
                    }
                    elseif ($p->personal_user_form_information->mn_region_id == 82) {
                        $p->city_id = 43;
                        $p->save();
                    }
                    elseif ($p->personal_user_form_information->mn_region_id == 23) {
                        $p->city_id = 44;
                        $p->save();
                    }
                    elseif ($p->personal_user_form_information->mn_region_id == 41) {
                        $p->city_id = 45;
                        $p->save();
                    }
                    elseif ($p->personal_user_form_information->mn_region_id == 63) {
                        $p->city_id = 46;
                        $p->save();
                    }
                    elseif ($p->personal_user_form_information->mn_region_id == 64) {
                        $p->city_id = 47;
                        $p->save();
                    }
                    elseif ($p->personal_user_form_information->mn_region_id == 73) {
                        $p->city_id = 48;
                        $p->save();
                    }
                    elseif ($p->personal_user_form_information->mn_region_id == 83) {
                        $p->city_id = 49;
                        $p->save();
                    }
                    elseif ($p->personal_user_form_information->mn_region_id == 22) {
                        $p->city_id = 50;
                        $p->save();
                    }
                    elseif ($p->personal_user_form_information->mn_region_id == 46) {
                        $p->city_id = 51;
                        $p->save();
                    }
                    elseif ($p->personal_user_form_information->mn_region_id == 61) {
                        $p->city_id = 52;
                        $p->save();
                    }
                    elseif ($p->personal_user_form_information->mn_region_id == 48) {
                        $p->city_id = 53;
                        $p->save();
                    }
                    elseif ($p->personal_user_form_information->mn_region_id == 76) {
                        $p->city_id = 54;
                        $p->save();
                    }
                    elseif ($p->personal_user_form_information->mn_region_id == 25) {
                        $p->city_id = 55;
                        $p->save();
                    }
                    elseif ($p->personal_user_form_information->mn_region_id == 36) {
                        $p->city_id = 56;
                        $p->save();
                    }
                    elseif ($p->personal_user_form_information->mn_region_id == 60) {
                        $p->city_id = 57;
                        $p->save();
                    }
                    elseif ($p->personal_user_form_information->mn_region_id == 65) {
                        $p->city_id = 58;
                        $p->save();
                    }
                    elseif ($p->personal_user_form_information->mn_region_id == 59) {
                        $p->city_id = 59;
                        $p->save();
                    }
                    elseif ($p->personal_user_form_information->mn_region_id == 40) {
                        $p->city_id = 44;
                        $p->save();
                    }
                    elseif ($p->personal_user_form_information->mn_region_id == 26) {
                        $p->city_id = 67;
                        $p->save();
                    }
                    elseif ($p->personal_user_form_information->mn_region_id == 9) {
                        $p->city_id = 59;
                        $p->save();
                    }
                    elseif ($p->personal_user_form_information->mn_region_id == 28) {
                        $p->city_id = 59;
                        $p->save();
                    }
                    elseif ($p->personal_user_form_information->mn_region_id == 44) {
                        $p->city_id = 59;
                        $p->save();
                    }
                    elseif ($p->personal_user_form_information->mn_region_id == 62) {
                        $p->city_id = 59;
                        $p->save();
                    }
                    elseif ($p->personal_user_form_information->mn_region_id == 67) {
                        $p->city_id = 59;
                        $p->save();
                    }
                    elseif ($p->personal_user_form_information->mn_region_id == 71) {
                        $p->city_id = 59;
                        $p->save();
                    }
                    elseif ($p->personal_user_form_information->mn_region_id == 75) {
                        $p->city_id = 59;
                        $p->save();
                    }

                }
*/

        /***************************************************************************************************************************************************************************/
/*
         $precincts = Precinct::all();

         foreach ($precincts as $p){
           $x = PrecinctsHasNomination::where('precinct_id',$p->id)->get();
           foreach ($x as $y){
               $y->city_id = $p->city_id;
               $y->save();
           }
         }
*/


/*****************************************************************************************************************************************************************************************/

                      $phasnom = PrecinctsHasNomination::skip(800)->take(100)->get();
                       foreach ($phasnom as $p){
                           $n_id = $p->nomination_id;
                           $x = PersonalUserCardInformation::whereHas('personal_user_form_information', function($q) use($n_id){
                               $q->where('nomination_id', '=', $n_id);
                           })->where('precinct_id',null)->where('city_id',$p->city_id)->get();
                           foreach ($x as $y){
                               $y->precinct_id = $p->precinct_id;
                               $y->save();
                           }
                       }

/******************************************************************************************************************************************************/

        /*
           $personal = PersonalUserCardInformation::all();

           foreach ($personal as $p){
               $p->score = null;
               $p->is_absent = null;
               $p->precinct_id = null;
               $p->date	 = null;
               $p->time	 = null;
               $p->save();

           }
       */
    }

    public function menteqeVaxt()
    {
/*
                $x = PersonalUserCardInformation::where('date','!=',null)->get();
                foreach ($x as $y){
                    $y->date = null;
                    $y->time = null;
                    $y->save();
                }
              return  $x = PersonalUserCardInformation::where('date','!=',null)->get();
*/
         $phasnom = PrecinctsHasNomination::where('type', 2)->skip(600)->take(300)->get();


        $saatlar = ['10:00', '11:00', '12:00', '13:00'];
        //  $p = PrecinctsHasNomination::find(20);
        foreach ($phasnom as $p) {

            $n_id = $p->nomination_id;

            $x = PersonalUserCardInformation::whereHas('personal_user_form_information', function ($q) use ($n_id) {
                $q->where('nomination_id', '=', $n_id);
            })->where('precinct_id', $p->precinct_id)->get();
            //  if($x->count() == 0) continue;
            // echo $x->count()."<br>";
            $count = 0;
            $n = 0;
            $g = 0;
            $f = 0;
            foreach ($x as $k => $y) {
                if (isset($this->getMyDate($p->start_date, $p->end_date)[$n])) {
                    $y->date = $this->getMyDate($p->start_date, $p->end_date)[$n];
                    $y->time = $saatlar[$f];
                    $y->save();
                    //echo   $j =  $this->getMyDate($p->start_date,$p->end_date)[$n]." ".$saatlar[$f]."<br>";
                }

                $g++;
                if ($g == 10) {
                    $g = 0;
                    $f++;
                }
                $count++;
                if ($count == 40) {
                    $count = 0;
                    $g = 0;
                    $f = 0;
                    $n++;
                }

            }

        }

        //  print_r($this->getMyDate($p->start_date,$p->end_date)) ;


        // print_r($z);

        /*    foreach ($phasnom as $p){


            }*/

        // print_r($this->getMyDate('2023-04-18')) ;


    }

    public function menteqeVaxtBaki()
    {
/*
        $x = PersonalUserCardInformation::whereHas('personal_user_form_information', function ($q)  {
            $q->where('nomination_id', '=', 4);
        })->where('precinct_id', 89)->get();

      foreach ($x as $y){
              $y->precinct_id = 48;
              $y->save();
      }



        return  999;
*/





            // Bine qesebe medeniyyet merkezi
        /*
         $x = PersonalUserCardInformation::whereHas('personal_user_form_information', function ($q)  {
                $q->where('nomination_id', '=', 16);
            })->where('city_id', 1)->where('date','=',null)->get();
        */
/*
        // Beyləqan rayon Mədəniyyət Mərkəzi


           $nom_id = 16;
           $city_id = 1;
           $start_date = '2023-05-02';
           $end_date = '2023-05-19';
           $precinct_id = 66;



        $x = PersonalUserCardInformation::whereHas('personal_user_form_information', function ($q) use ($nom_id,$city_id) {
            $q->where('nomination_id', '=', $nom_id);
        })->where('city_id', $city_id)->where('date','=',null)->get();
*/

        $saatlar = ['10:00', '11:00', '12:00', '13:00'];

         $x = PersonalUserCardInformation::whereHas('personal_user_form_information', function ($q)  {
            $q->where('nomination_id', '=', 1);
        })->where('precinct_id', 119)->get();

        $start_date = '2023-04-17';
        $end_date = '2023-04-19';



            $count = 0;
            $n = 0;
            $g = 0;
            $f = 0;
            foreach ($x as $k => $y) {
                if (isset($this->getMyDate($start_date, $end_date)[$n])) {
                    $y->date = $this->getMyDate($start_date, $end_date)[$n];
                    $y->time = $saatlar[$f];
                    // menteqe deyis
                    //$y->precinct_id = $precinct_id;
                    $y->save();
                    //echo   $j =  $this->getMyDate($p->start_date,$p->end_date)[$n]." ".$saatlar[$f]."<br>";
                }

                $g++;
                if ($g == 10) {
                    $g = 0;
                    $f++;
                }
                $count++;
                if ($count == 40) {
                    $count = 0;
                    $g = 0;
                    $f = 0;
                    $n++;
                }

            }




    }

    public function menteqeIstirakciKol()
    {
/*
        $phasnomcol = PrecinctsHasNomination::all();
                foreach ($phasnomcol as $p){
                    $n_id = $p->nomination_id;
                    $x = CollectiveDirector::whereHas('collective_information', function($q) use($n_id){
                        $q->where('collective_nomination_id', '=', $n_id);
                    })->where('precinct_id',null)->where('city_id',$p->city_id)->get();
                    foreach ($x as $y){
                        $y->precinct_id = $p->precinct_id;
                        $y->save();
                    }
                }
*/

        /*

                        $precincts = Precinct::all();

                        foreach ($precincts as $p){
                          $x = PrecinctsHasNomination::where('precinct_id',$p->id)->get();
                          foreach ($x as $y){
                              $y->city_id = $p->city_id;
                              $y->save();
                          }
                        }
        */
/*
                        $kol = CollectiveDirector::with('collective_information')->where('city_id', null)->get();

                foreach ($kol as $p) {
                    if ($p->collective_information->collective_mn_region_id >= 10 && $p->collective_information->collective_mn_region_id <= 21) {
                        $p->city_id = 1;
                        $p->save();
                    }
                    elseif ($p->collective_information->collective_mn_region_id == 69) {
                        $p->city_id = 2;
                        $p->save();
                    }
                    elseif ($p->collective_information->collective_mn_region_id >= 1 && $p->collective_information->collective_mn_region_id <= 2) {
                        $p->city_id = 3;
                        $p->save();
                    }
                    elseif ($p->collective_information->collective_mn_region_id == 38) {
                        $p->city_id = 4;
                        $p->save();
                    }
                    elseif ($p->collective_information->collective_mn_region_id == 7) {
                        $p->city_id = 5;
                        $p->save();
                    }
                    elseif ($p->collective_information->collective_mn_region_id == 42) {
                        $p->city_id = 6;
                        $p->save();
                    }
                    elseif ($p->collective_information->collective_mn_region_id == 49) {
                        $p->city_id = 7;
                        $p->save();
                    }
                    elseif ($p->collective_information->collective_mn_region_id == 72) {
                        $p->city_id = 8;
                        $p->save();
                    }
                    elseif ($p->collective_information->collective_mn_region_id == 32) {
                        $p->city_id = 9;
                        $p->save();
                    }
                    elseif ($p->collective_information->collective_mn_region_id == 29) {
                        $p->city_id = 10;
                        $p->save();
                    }
                    elseif ($p->collective_information->collective_mn_region_id == 58) {
                        $p->city_id = 11;
                        $p->save();
                    }
                    elseif ($p->collective_information->collective_mn_region_id == 33) {
                        $p->city_id = 12;
                        $p->save();
                    }
                    elseif ($p->collective_information->collective_mn_region_id == 35) {
                        $p->city_id = 13;
                        $p->save();
                    }
                    elseif ($p->collective_information->collective_mn_region_id == 66) {
                        $p->city_id = 14;
                        $p->save();
                    }
                    elseif ($p->collective_information->collective_mn_region_id == 3) {
                        $p->city_id = 15;
                        $p->save();
                    }
                    elseif ($p->collective_information->collective_mn_region_id == 4) {
                        $p->city_id = 16;
                        $p->save();
                    }
                    elseif ($p->collective_information->collective_mn_region_id == 24) {
                        $p->city_id = 17;
                        $p->save();
                    }
                    elseif ($p->collective_information->collective_mn_region_id == 30) {
                        $p->city_id = 18;
                        $p->save();
                    }
                    elseif ($p->collective_information->collective_mn_region_id == 78) {
                        $p->city_id = 19;
                        $p->save();
                    }
                    elseif ($p->collective_information->collective_mn_region_id == 6) {
                        $p->city_id = 20;
                        $p->save();
                    }
                    elseif ($p->collective_information->collective_mn_region_id == 31) {
                        $p->city_id = 21;
                        $p->save();
                    }
                    elseif ($p->collective_information->collective_mn_region_id == 47) {
                        $p->city_id = 22;
                        $p->save();
                    }
                    elseif ($p->collective_information->collective_mn_region_id == 74) {
                        $p->city_id = 23;
                        $p->save();
                    }
                    elseif ($p->collective_information->collective_mn_region_id == 79) {
                        $p->city_id = 24;
                        $p->save();
                    }
                    elseif ($p->collective_information->collective_mn_region_id == 37) {
                        $p->city_id = 25;
                        $p->save();
                    }
                    elseif ($p->collective_information->collective_mn_region_id == 50) {
                        $p->city_id = 26;
                        $p->save();
                    }
                    elseif ($p->collective_information->collective_mn_region_id == 52) {
                        $p->city_id = 27;
                        $p->save();
                    }
                    elseif ($p->collective_information->collective_mn_region_id == 68) {
                        $p->city_id = 28;
                        $p->save();
                    }
                    elseif ($p->collective_information->collective_mn_region_id == 70) {
                        $p->city_id = 29;
                        $p->save();
                    }
                    elseif ($p->collective_information->collective_mn_region_id == 8) {
                        $p->city_id = 30;
                        $p->save();
                    }
                    elseif ($p->collective_information->collective_mn_region_id == 27) {
                        $p->city_id = 31;
                        $p->save();
                    }
                    elseif ($p->collective_information->collective_mn_region_id == 54) {
                        $p->city_id = 32;
                        $p->save();
                    }
                    elseif ($p->collective_information->collective_mn_region_id == 55) {
                        $p->city_id = 34;
                        $p->save();
                    }
                    elseif ($p->collective_information->collective_mn_region_id == 56) {
                        $p->city_id = 35;
                        $p->save();
                    }
                    elseif ($p->collective_information->collective_mn_region_id == 81) {
                        $p->city_id = 36;
                        $p->save();
                    }
                    elseif ($p->collective_information->collective_mn_region_id == 57) {
                        $p->city_id = 37;
                        $p->save();
                    }
                    elseif ($p->collective_information->collective_mn_region_id == 5) {
                        $p->city_id = 38;
                        $p->save();
                    }
                    elseif ($p->collective_information->collective_mn_region_id == 80) {
                        $p->city_id = 39;
                        $p->save();
                    }
                    elseif ($p->collective_information->collective_mn_region_id == 85) {
                        $p->city_id = 40;
                        $p->save();
                    }
                    elseif ($p->collective_information->collective_mn_region_id == 34) {
                        $p->city_id = 41;
                        $p->save();
                    }
                    elseif ($p->collective_information->collective_mn_region_id == 45) {
                        $p->city_id = 42;
                        $p->save();
                    }
                    elseif ($p->collective_information->collective_mn_region_id == 82) {
                        $p->city_id = 43;
                        $p->save();
                    }
                    elseif ($p->collective_information->collective_mn_region_id == 23) {
                        $p->city_id = 44;
                        $p->save();
                    }
                    elseif ($p->collective_information->collective_mn_region_id == 41) {
                        $p->city_id = 45;
                        $p->save();
                    }
                    elseif ($p->collective_information->collective_mn_region_id == 63) {
                        $p->city_id = 46;
                        $p->save();
                    }
                    elseif ($p->collective_information->collective_mn_region_id == 64) {
                        $p->city_id = 47;
                        $p->save();
                    }
                    elseif ($p->collective_information->collective_mn_region_id == 73) {
                        $p->city_id = 48;
                        $p->save();
                    }
                    elseif ($p->collective_information->collective_mn_region_id == 83) {
                        $p->city_id = 49;
                        $p->save();
                    }
                    elseif ($p->collective_information->collective_mn_region_id == 22) {
                        $p->city_id = 50;
                        $p->save();
                    }
                    elseif ($p->collective_information->collective_mn_region_id == 46) {
                        $p->city_id = 51;
                        $p->save();
                    }
                    elseif ($p->collective_information->collective_mn_region_id == 61) {
                        $p->city_id = 52;
                        $p->save();
                    }
                    elseif ($p->collective_information->collective_mn_region_id == 48) {
                        $p->city_id = 53;
                        $p->save();
                    }
                    elseif ($p->collective_information->collective_mn_region_id == 76) {
                        $p->city_id = 54;
                        $p->save();
                    }
                    elseif ($p->collective_information->collective_mn_region_id == 25) {
                        $p->city_id = 55;
                        $p->save();
                    }
                    elseif ($p->collective_information->collective_mn_region_id == 36) {
                        $p->city_id = 56;
                        $p->save();
                    }
                    elseif ($p->collective_information->collective_mn_region_id == 60) {
                        $p->city_id = 57;
                        $p->save();
                    }
                    elseif ($p->collective_information->collective_mn_region_id == 65) {
                        $p->city_id = 58;
                        $p->save();
                    }
                    elseif ($p->collective_information->collective_mn_region_id == 59) {
                        $p->city_id = 59;
                        $p->save();
                    }
                    elseif ($p->collective_information->collective_mn_region_id == 40) {
                        $p->city_id = 66;
                        $p->save();
                    }
                    elseif ($p->collective_information->collective_mn_region_id == 26) {
                        $p->city_id = 67;
                        $p->save();
                    }
                    elseif ($p->collective_information->collective_mn_region_id == 9) {
                        $p->city_id = 59;
                        $p->save();
                    }
                    elseif ($p->collective_information->collective_mn_region_id == 28) {
                        $p->city_id = 59;
                        $p->save();
                    }
                    elseif ($p->collective_information->collective_mn_region_id == 44) {
                        $p->city_id = 59;
                        $p->save();
                    }
                    elseif ($p->collective_information->collective_mn_region_id == 62) {
                        $p->city_id = 59;
                        $p->save();
                    }
                    elseif ($p->collective_information->collective_mn_region_id == 67) {
                        $p->city_id = 59;
                        $p->save();
                    }
                    elseif ($p->collective_information->collective_mn_region_id == 71) {
                        $p->city_id = 59;
                        $p->save();
                    }
                    elseif ($p->collective_information->collective_mn_region_id == 75) {
                        $p->city_id = 59;
                        $p->save();
                    }

                }

*/
        /*
           $personal = PersonalUserCardInformation::all();

           foreach ($personal as $p){
               $p->score = null;
               $p->is_absent = null;
               $p->precinct_id = null;
               $p->date	 = null;
               $p->time	 = null;
               $p->save();

           }
       */
    }

    public function menteqeVaxtKol()
    {
        /*
                        $x = CollectiveDirector::where('date','!=',null)->get();
                        foreach ($x as $y){
                            $y->date = null;
                            $y->time = null;
                            $y->save();
                        }
                      return  $x = CollectiveDirector::where('date','!=',null)->get();*/

        //$phasnom = PrecinctsHasNomination::take(30)->get();
        /*
        $p = PrecinctsHasNomination::all();

        foreach ($p as $d){
            if($d->nomination_id >=20 ){
                $d->type = 3;
                $d->save();
            }
            else{
                $d->type = 2;
                $d->save();
            }
        }

            return 99;
        */
         $phasnom = PrecinctsHasNomination::where('type', 3)->get();
       // return $phasnom = PrecinctsHasNomination::where('type', 2)->get()->count();

        $saatlar = ['10:00', '11:00', '12:00', '13:00'];

        foreach ($phasnom as $p) {

            $n_id = $p->nomination_id;

            $x = CollectiveDirector::whereHas('collective_information', function ($q) use ($n_id) {
                $q->where('collective_nomination_id', '=', $n_id);
            })->where('precinct_id', $p->precinct_id)->get();
            //if($x->count() == 0) continue;
            $count = 0;
            $n = 0;
            $g = 0;
            $f = 0;
            foreach ($x as $k => $y) {
                if (isset($this->getMyDate($p->start_date, $p->end_date)[$n])) {
                    $y->date = $this->getMyDate($p->start_date, $p->end_date)[$n];
                    $y->time = $saatlar[$f];
                     $y->save();
                   // echo $j = $this->getMyDate($p->start_date, $p->end_date)[$n] . " " . $saatlar[$f] . "<br>";
                }

                $g++;
                if ($g == 10) {
                    $g = 0;
                    $f++;
                }
                $count++;
                if ($count == 40) {
                    $count = 0;
                    $g = 0;
                    $f = 0;
                    $n++;
                }
            }

        }

    }

    public function getMyDate($startDate, $endDate = null)
    {
        if ($endDate == null) {
            $endDate = $startDate;
        }
        $period = new DatePeriod(
            new DateTime($startDate),
            new DateInterval('P1D'),
            new DateTime(date('Y-m-d', strtotime($endDate . ' + 1 days')))
        );
        $x = [];
        foreach ($period as $key => $value):
            if ($value->format('Y-m-d') == '2023-04-21' || $value->format('Y-m-d') == '2023-04-22' || $value->format('Y-m-d') == '2023-04-23' || $value->format('Y-m-d') == '2023-04-24' || $value->format('Y-m-d') == '2023-05-09' || $value->format('Y-m-d') == '2023-05-29' || $value->format('Y-m-d') == '2023-06-15' || $value->format('Y-m-d') == '2023-06-26' || $value->format('Y-m-d') == '2023-06-28' || $value->format('Y-m-d') == '2023-06-29') continue;
            $date = new DateTime($value->format('Y-m-d'));
            $x[] = [
                $date->format('N'),
                $value->format('Y-m-d')
            ];
        endforeach;
        $l = [];
        foreach ($x as $key => $y) {
            if ($y[0] == 6 || $y[0] == 7) continue;
            $l[] = $y[1];
        }
        return $l;
    }

    public function test()
    {
        $personal = PersonalUserCardInformation::with('personal_user_form_information')->whereHas('personal_user_form_information', function ($q) {
            $q->where('nomination_id', '=', 1);
        })->where('precinct_id', 1)->get();

        $m = ['10:00', '11:00', '12:00', '14:00', '15:00', '16:00', '17:00'];
        $count = 0;
        foreach ($personal as $key => $p) {
            /*  $p->date = null;
              $p->time = null;
              $p->save();*/

            $x = $this->getMyDate('2023-04-18', '2023-05-02', 7);
            if ($key < 35) {
                if ($key < 5) {
                    $p->time = $m[0];
                } elseif ($key > 4 && $key < 10) {
                    $p->time = $m[1];
                } elseif ($key > 9 && $key < 15) {
                    $p->time = $m[2];
                } elseif ($key > 14 && $key < 20) {
                    $p->time = $m[3];
                } elseif ($key > 19 && $key < 25) {
                    $p->time = $m[4];
                } elseif ($key > 24 && $key < 30) {
                    $p->time = $m[5];
                } elseif ($key > 29 && $key < 35) {
                    $p->time = $m[6];
                }
                $p->date = $x[0];
                $p->save();
            } elseif ($key > 34 && $key < 70) {
                if ($key > 34 && $key < 40) {
                    $p->time = $m[0];
                } elseif ($key > 39 && $key < 45) {
                    $p->time = $m[1];
                } elseif ($key > 44 && $key < 50) {
                    $p->time = $m[2];
                } elseif ($key > 49 && $key < 55) {
                    $p->time = $m[3];
                } elseif ($key > 54 && $key < 60) {
                    $p->time = $m[4];
                } elseif ($key > 59 && $key < 65) {
                    $p->time = $m[5];
                } elseif ($key > 64 && $key < 70) {
                    $p->time = $m[6];
                }
                $p->date = $x[1];
                $p->save();
            } elseif ($key > 69 && $key < 105) {
                if ($key > 69 && $key < 75) {
                    $p->time = $m[0];
                } elseif ($key > 74 && $key < 80) {
                    $p->time = $m[1];
                } elseif ($key > 79 && $key < 85) {
                    $p->time = $m[2];
                } elseif ($key > 84 && $key < 90) {
                    $p->time = $m[3];
                } elseif ($key > 89 && $key < 95) {
                    $p->time = $m[4];
                } elseif ($key > 94 && $key < 100) {
                    $p->time = $m[5];
                } elseif ($key > 99 && $key < 105) {
                    $p->time = $m[6];
                }
                $p->date = $x[2];
                $p->save();
            } elseif ($key > 104 && $key < 140) {
                if ($key > 104 && $key < 110) {
                    $p->time = $m[0];
                } elseif ($key > 109 && $key < 115) {
                    $p->time = $m[1];
                } elseif ($key > 114 && $key < 120) {
                    $p->time = $m[2];
                } elseif ($key > 119 && $key < 125) {
                    $p->time = $m[3];
                } elseif ($key > 124 && $key < 130) {
                    $p->time = $m[4];
                } elseif ($key > 129 && $key < 135) {
                    $p->time = $m[5];
                } elseif ($key > 134 && $key < 140) {
                    $p->time = $m[6];
                }
                $p->date = $x[3];
                $p->save();
            } elseif ($key > 139 && $key < 175) {
                if ($key > 139 && $key < 145) {
                    $p->time = $m[0];
                } elseif ($key > 144 && $key < 150) {
                    $p->time = $m[1];
                } elseif ($key > 149 && $key < 155) {
                    $p->time = $m[2];
                } elseif ($key > 154 && $key < 160) {
                    $p->time = $m[3];
                } elseif ($key > 159 && $key < 165) {
                    $p->time = $m[4];
                } elseif ($key > 164 && $key < 170) {
                    $p->time = $m[5];
                } elseif ($key > 169 && $key < 175) {
                    $p->time = $m[6];
                }
                $p->date = $x[4];
                $p->save();
            } elseif ($key > 174 && $key < 210) {
                if ($key > 174 && $key < 180) {
                    $p->time = $m[0];
                } elseif ($key > 179 && $key < 185) {
                    $p->time = $m[1];
                } elseif ($key > 184 && $key < 190) {
                    $p->time = $m[2];
                } elseif ($key > 189 && $key < 195) {
                    $p->time = $m[3];
                } elseif ($key > 194 && $key < 200) {
                    $p->time = $m[4];
                } elseif ($key > 199 && $key < 205) {
                    $p->time = $m[5];
                } elseif ($key > 204 && $key < 210) {
                    $p->time = $m[6];
                }
                $p->date = $x[5];
                $p->save();
            } elseif ($key > 209 && $key < 245) {
                $p->date = $x[6];
                $p->save();
            } elseif ($key > 244 && $key < 275) {
                $p->date = $x[7];
                $p->save();
            } elseif ($key > 274 && $key < 310) {
                $p->date = $x[8];
                $p->save();
            } elseif ($key > 309 && $key < 345) {
                $p->date = $x[9];
                $p->save();
            } elseif ($key > 344 && $key < 380) {
                $p->date = $x[10];
                $p->save();
            } elseif ($key > 379 && $key < 415) {
                $p->date = $x[11];
                $p->save();
            }
        }
    }

    public function getMyRandom($rand_x)
    {
        $date = new DateTime($rand_x);
        return $date->format('N');
    }

    public function ContactCityRegion(){
/*
                $personal = PersonalUserCardInformation::with('personal_user_form_information')->where('all_city_id', null)->where('mn_region_old_id', null)->get();
                foreach ($personal as $p) {


                        if ($p->personal_user_form_information->mn_region_id >= 10 && $p->personal_user_form_information->mn_region_id <= 21) {
                            $p->all_city_id = 1;
                            $p->save();
                        }
                        elseif ($p->personal_user_form_information->mn_region_id == 69) {
                            $p->all_city_id = 2;
                            $p->save();
                        }
                        elseif ($p->personal_user_form_information->mn_region_id >= 1 && $p->personal_user_form_information->mn_region_id <= 2) {
                            $p->all_city_id = 3;
                            $p->save();
                        }
                        elseif ($p->personal_user_form_information->mn_region_id == 38) {
                            $p->all_city_id = 4;
                            $p->save();
                        }
                        elseif ($p->personal_user_form_information->mn_region_id == 7) {
                            $p->all_city_id = 5;
                            $p->save();
                        }
                        elseif ($p->personal_user_form_information->mn_region_id == 42) {
                            $p->all_city_id = 6;
                            $p->save();
                        }
                        elseif ($p->personal_user_form_information->mn_region_id == 49) {
                            $p->all_city_id = 7;
                            $p->save();
                        }
                        elseif ($p->personal_user_form_information->mn_region_id == 72) {
                            $p->all_city_id = 8;
                            $p->save();
                        }
                        elseif ($p->personal_user_form_information->mn_region_id == 32) {
                            $p->all_city_id = 9;
                            $p->save();
                        }
                        elseif ($p->personal_user_form_information->mn_region_id == 66) {
                            $p->all_city_id = 10;
                            $p->save();
                        }
                        elseif ($p->personal_user_form_information->mn_region_id == 29) {
                            $p->all_city_id = 11;
                            $p->save();
                        }
                        elseif ($p->personal_user_form_information->mn_region_id == 58) {
                            $p->all_city_id = 12;
                            $p->save();
                        }
                        elseif ($p->personal_user_form_information->mn_region_id == 33) {
                            $p->all_city_id = 13;
                            $p->save();
                        }
                        elseif ($p->personal_user_form_information->mn_region_id == 35) {
                            $p->all_city_id = 14;
                            $p->save();
                        }
                        elseif ($p->personal_user_form_information->mn_region_id == 3) {
                            $p->all_city_id = 15;
                            $p->save();
                        }
                        elseif ($p->personal_user_form_information->mn_region_id == 4) {
                            $p->all_city_id = 16;
                            $p->save();
                        }
                        elseif ($p->personal_user_form_information->mn_region_id == 24) {
                            $p->all_city_id = 17;
                            $p->save();
                        }
                        elseif ($p->personal_user_form_information->mn_region_id == 30) {
                            $p->all_city_id = 18;
                            $p->save();
                        }
                        elseif ($p->personal_user_form_information->mn_region_id == 39) {
                            $p->all_city_id = 19;
                            $p->save();
                        }
                        elseif ($p->personal_user_form_information->mn_region_id == 40) {
                            $p->all_city_id = 20;
                            $p->save();
                        }
                        elseif ($p->personal_user_form_information->mn_region_id == 77) {
                            $p->all_city_id = 21;
                            $p->save();
                        }
                        elseif ($p->personal_user_form_information->mn_region_id == 78) {
                            $p->all_city_id = 22;
                            $p->save();
                        }
                        elseif ($p->personal_user_form_information->mn_region_id == 6) {
                            $p->all_city_id = 23;
                            $p->save();
                        }
                        elseif ($p->personal_user_form_information->mn_region_id == 31) {
                            $p->all_city_id = 24;
                            $p->save();
                        }
                        elseif ($p->personal_user_form_information->mn_region_id == 47) {
                            $p->all_city_id = 25;
                            $p->save();
                        }
                        elseif ($p->personal_user_form_information->mn_region_id == 74) {
                            $p->all_city_id = 26;
                            $p->save();
                        }
                        elseif ($p->personal_user_form_information->mn_region_id == 79) {
                            $p->all_city_id = 27;
                            $p->save();
                        }
                        elseif ($p->personal_user_form_information->mn_region_id == 37) {
                            $p->all_city_id = 28;
                            $p->save();
                        }
                        elseif ($p->personal_user_form_information->mn_region_id == 50) {
                            $p->all_city_id = 29;
                            $p->save();
                        }
                        elseif ($p->personal_user_form_information->mn_region_id == 52) {
                            $p->all_city_id = 30;
                            $p->save();
                        }
                        elseif ($p->personal_user_form_information->mn_region_id == 68) {
                            $p->all_city_id = 31;
                            $p->save();
                        }
                        elseif ($p->personal_user_form_information->mn_region_id == 70) {
                            $p->all_city_id = 32;
                            $p->save();
                        }
                        elseif ($p->personal_user_form_information->mn_region_id == 8) {
                            $p->all_city_id = 33;
                            $p->save();
                        }
                        elseif ($p->personal_user_form_information->mn_region_id == 27) {
                            $p->all_city_id = 34;
                            $p->save();
                        }
                        elseif ($p->personal_user_form_information->mn_region_id == 54) {
                            $p->all_city_id = 35;
                            $p->save();
                        }
                        elseif ($p->personal_user_form_information->mn_region_id == 55) {
                            $p->all_city_id = 36;
                            $p->save();
                        }
                        elseif ($p->personal_user_form_information->mn_region_id == 56) {
                            $p->all_city_id = 37;
                            $p->save();
                        }
                        elseif ($p->personal_user_form_information->mn_region_id == 81) {
                            $p->all_city_id = 38;
                            $p->save();
                        }
                        elseif ($p->personal_user_form_information->mn_region_id == 57) {
                            $p->all_city_id = 39;
                            $p->save();
                        }
                        elseif ($p->personal_user_form_information->mn_region_id == 5) {
                            $p->all_city_id = 40;
                            $p->save();
                        }
                        elseif ($p->personal_user_form_information->mn_region_id == 80) {
                            $p->all_city_id = 41;
                            $p->save();
                        }
                        elseif ($p->personal_user_form_information->mn_region_id == 85) {
                            $p->all_city_id = 42;
                            $p->save();
                        }
                        elseif ($p->personal_user_form_information->mn_region_id == 34) {
                            $p->all_city_id = 43;
                            $p->save();
                        }
                        elseif ($p->personal_user_form_information->mn_region_id == 45) {
                            $p->all_city_id = 44;
                            $p->save();
                        }
                        elseif ($p->personal_user_form_information->mn_region_id == 82) {
                            $p->all_city_id = 45;
                            $p->save();
                        }
                        elseif ($p->personal_user_form_information->mn_region_id == 23) {
                            $p->all_city_id = 46;
                            $p->save();
                        }
                        elseif ($p->personal_user_form_information->mn_region_id == 41) {
                            $p->all_city_id = 47;
                            $p->save();
                        }
                        elseif ($p->personal_user_form_information->mn_region_id == 63) {
                            $p->all_city_id = 48;
                            $p->save();
                        }
                        elseif ($p->personal_user_form_information->mn_region_id == 64) {
                            $p->all_city_id = 49;
                            $p->save();
                        }
                        elseif ($p->personal_user_form_information->mn_region_id == 73) {
                            $p->all_city_id = 50;
                            $p->save();
                        }
                        elseif ($p->personal_user_form_information->mn_region_id == 83) {
                            $p->all_city_id = 51;
                            $p->save();
                        }
                        elseif ($p->personal_user_form_information->mn_region_id == 22) {
                            $p->all_city_id = 52;
                            $p->save();
                        }
                        elseif ($p->personal_user_form_information->mn_region_id == 46) {
                            $p->all_city_id = 53;
                            $p->save();
                        }
                        elseif ($p->personal_user_form_information->mn_region_id == 61) {
                            $p->all_city_id = 54;
                            $p->save();
                        }
                        elseif ($p->personal_user_form_information->mn_region_id == 48) {
                            $p->all_city_id = 55;
                            $p->save();
                        }
                        elseif ($p->personal_user_form_information->mn_region_id == 26) {
                            $p->all_city_id = 56;
                            $p->save();
                        }
                        elseif ($p->personal_user_form_information->mn_region_id == 43) {
                            $p->all_city_id = 57;
                            $p->save();
                        }
                        elseif ($p->personal_user_form_information->mn_region_id == 51) {
                            $p->all_city_id = 58;
                            $p->save();
                        }
                        elseif ($p->personal_user_form_information->mn_region_id == 53) {
                            $p->all_city_id = 59;
                            $p->save();
                        }
                        elseif ($p->personal_user_form_information->mn_region_id == 84) {
                            $p->all_city_id = 60;
                            $p->save();
                        }
                        elseif ($p->personal_user_form_information->mn_region_id == 76) {
                            $p->all_city_id = 61;
                            $p->save();
                        }
                        elseif ($p->personal_user_form_information->mn_region_id == 25) {
                            $p->all_city_id = 62;
                            $p->save();
                        }
                        elseif ($p->personal_user_form_information->mn_region_id == 36) {
                            $p->all_city_id = 63;
                            $p->save();
                        }
                        elseif ($p->personal_user_form_information->mn_region_id == 60) {
                            $p->all_city_id = 64;
                            $p->save();
                        }
                        elseif ($p->personal_user_form_information->mn_region_id == 65) {
                            $p->all_city_id = 65;
                            $p->save();
                        }
                        elseif ($p->personal_user_form_information->mn_region_id == 59) {
                            $p->all_city_id = 66;
                            $p->save();
                        }
                        elseif ($p->personal_user_form_information->mn_region_id == 9) {
                            $p->all_city_id = 67;
                            $p->save();
                        }
                        elseif ($p->personal_user_form_information->mn_region_id == 28) {
                            $p->all_city_id = 68;
                            $p->save();
                        }
                        elseif ($p->personal_user_form_information->mn_region_id == 44) {
                            $p->all_city_id = 69;
                            $p->save();
                        }
                        elseif ($p->personal_user_form_information->mn_region_id == 62) {
                            $p->all_city_id = 70;
                            $p->save();
                        }
                        elseif ($p->personal_user_form_information->mn_region_id == 67) {
                            $p->all_city_id = 71;
                            $p->save();
                        }
                        elseif ($p->personal_user_form_information->mn_region_id == 71) {
                            $p->all_city_id = 72;
                            $p->save();
                        }
                        elseif ($p->personal_user_form_information->mn_region_id == 75) {
                            $p->all_city_id = 73;
                            $p->save();
                        }





                }
*/

        /*
        $personal = PersonalUserCardInformation::where('all_city_id', null)->where('mn_region_old_id', '!=',null)->get();
        foreach ($personal as $p) {


            if ($p->mn_region_old_id == 84) {
                $p->all_city_id = 60;
                $p->save();
            }
            elseif ($p->mn_region_old_id == 77) {
                $p->all_city_id = 21;
                $p->save();
            }
            elseif ($p->mn_region_old_id == 53) {
                $p->all_city_id = 59;
                $p->save();
            }
            elseif ($p->mn_region_old_id == 51) {
                $p->all_city_id = 58;
                $p->save();
            }
            elseif ($p->mn_region_old_id == 43) {
                $p->all_city_id = 57;
                $p->save();
            }
            elseif ($p->mn_region_old_id == 40) {
                $p->all_city_id = 20;
                $p->save();
            }
            elseif ($p->mn_region_old_id == 39) {
                $p->all_city_id = 19;
                $p->save();
            }
            elseif ($p->mn_region_old_id == 30) {
                $p->all_city_id = 18;
                $p->save();
            }
            elseif ($p->mn_region_old_id == 26) {
                $p->all_city_id = 56;
                $p->save();
            }
            elseif ($p->mn_region_old_id == 4) {
                $p->all_city_id = 16;
                $p->save();
            }










        }
        */



/*
        $kol = CollectiveDirector::with('collective_information')->where('all_city_id', null)->where('collective_mn_region_old_id', null)->get();

        foreach ($kol as $p) {


            if ($p->collective_information->collective_mn_region_id >= 10 && $p->collective_information->collective_mn_region_id <= 21) {
                $p->all_city_id = 1;
                $p->save();
            }
            elseif ($p->collective_information->collective_mn_region_id == 69) {
                $p->all_city_id = 2;
                $p->save();
            }
            elseif ($p->collective_information->collective_mn_region_id >= 1 && $p->collective_information->collective_mn_region_id <= 2) {
                $p->all_city_id = 3;
                $p->save();
            }
            elseif ($p->collective_information->collective_mn_region_id == 38) {
                $p->all_city_id = 4;
                $p->save();
            }
            elseif ($p->collective_information->collective_mn_region_id == 7) {
                $p->all_city_id = 5;
                $p->save();
            }
            elseif ($p->collective_information->collective_mn_region_id == 42) {
                $p->all_city_id = 6;
                $p->save();
            }
            elseif ($p->collective_information->collective_mn_region_id == 49) {
                $p->all_city_id = 7;
                $p->save();
            }
            elseif ($p->collective_information->collective_mn_region_id == 72) {
                $p->all_city_id = 8;
                $p->save();
            }
            elseif ($p->collective_information->collective_mn_region_id == 32) {
                $p->all_city_id = 9;
                $p->save();
            }
            elseif ($p->collective_information->collective_mn_region_id == 66) {
                $p->all_city_id = 10;
                $p->save();
            }
            elseif ($p->collective_information->collective_mn_region_id == 29) {
                $p->all_city_id = 11;
                $p->save();
            }
            elseif ($p->collective_information->collective_mn_region_id == 58) {
                $p->all_city_id = 12;
                $p->save();
            }
            elseif ($p->collective_information->collective_mn_region_id == 33) {
                $p->all_city_id = 13;
                $p->save();
            }
            elseif ($p->collective_information->collective_mn_region_id == 35) {
                $p->all_city_id = 14;
                $p->save();
            }
            elseif ($p->collective_information->collective_mn_region_id == 3) {
                $p->all_city_id = 15;
                $p->save();
            }
            elseif ($p->collective_information->collective_mn_region_id == 4) {
                $p->all_city_id = 16;
                $p->save();
            }
            elseif ($p->collective_information->collective_mn_region_id == 24) {
                $p->all_city_id = 17;
                $p->save();
            }
            elseif ($p->collective_information->collective_mn_region_id == 30) {
                $p->all_city_id = 18;
                $p->save();
            }
            elseif ($p->collective_information->collective_mn_region_id == 39) {
                $p->all_city_id = 19;
                $p->save();
            }
            elseif ($p->collective_information->collective_mn_region_id == 40) {
                $p->all_city_id = 20;
                $p->save();
            }
            elseif ($p->collective_information->collective_mn_region_id == 77) {
                $p->all_city_id = 21;
                $p->save();
            }
            elseif ($p->collective_information->collective_mn_region_id == 78) {
                $p->all_city_id = 22;
                $p->save();
            }
            elseif ($p->collective_information->collective_mn_region_id == 6) {
                $p->all_city_id = 23;
                $p->save();
            }
            elseif ($p->collective_information->collective_mn_region_id == 31) {
                $p->all_city_id = 24;
                $p->save();
            }
            elseif ($p->collective_information->collective_mn_region_id == 47) {
                $p->all_city_id = 25;
                $p->save();
            }
            elseif ($p->collective_information->collective_mn_region_id == 74) {
                $p->all_city_id = 26;
                $p->save();
            }
            elseif ($p->collective_information->collective_mn_region_id == 79) {
                $p->all_city_id = 27;
                $p->save();
            }
            elseif ($p->collective_information->collective_mn_region_id == 37) {
                $p->all_city_id = 28;
                $p->save();
            }
            elseif ($p->collective_information->collective_mn_region_id == 50) {
                $p->all_city_id = 29;
                $p->save();
            }
            elseif ($p->collective_information->collective_mn_region_id == 52) {
                $p->all_city_id = 30;
                $p->save();
            }
            elseif ($p->collective_information->collective_mn_region_id == 68) {
                $p->all_city_id = 31;
                $p->save();
            }
            elseif ($p->collective_information->collective_mn_region_id == 70) {
                $p->all_city_id = 32;
                $p->save();
            }
            elseif ($p->collective_information->collective_mn_region_id == 8) {
                $p->all_city_id = 33;
                $p->save();
            }
            elseif ($p->collective_information->collective_mn_region_id == 27) {
                $p->all_city_id = 34;
                $p->save();
            }
            elseif ($p->collective_information->collective_mn_region_id == 54) {
                $p->all_city_id = 35;
                $p->save();
            }
            elseif ($p->collective_information->collective_mn_region_id == 55) {
                $p->all_city_id = 36;
                $p->save();
            }
            elseif ($p->collective_information->collective_mn_region_id == 56) {
                $p->all_city_id = 37;
                $p->save();
            }
            elseif ($p->collective_information->collective_mn_region_id == 81) {
                $p->all_city_id = 38;
                $p->save();
            }
            elseif ($p->collective_information->collective_mn_region_id == 57) {
                $p->all_city_id = 39;
                $p->save();
            }
            elseif ($p->collective_information->collective_mn_region_id == 5) {
                $p->all_city_id = 40;
                $p->save();
            }
            elseif ($p->collective_information->collective_mn_region_id == 80) {
                $p->all_city_id = 41;
                $p->save();
            }
            elseif ($p->collective_information->collective_mn_region_id == 85) {
                $p->all_city_id = 42;
                $p->save();
            }
            elseif ($p->collective_information->collective_mn_region_id == 34) {
                $p->all_city_id = 43;
                $p->save();
            }
            elseif ($p->collective_information->collective_mn_region_id == 45) {
                $p->all_city_id = 44;
                $p->save();
            }
            elseif ($p->collective_information->collective_mn_region_id == 82) {
                $p->all_city_id = 45;
                $p->save();
            }
            elseif ($p->collective_information->collective_mn_region_id == 23) {
                $p->all_city_id = 46;
                $p->save();
            }
            elseif ($p->collective_information->collective_mn_region_id == 41) {
                $p->all_city_id = 47;
                $p->save();
            }
            elseif ($p->collective_information->collective_mn_region_id == 63) {
                $p->all_city_id = 48;
                $p->save();
            }
            elseif ($p->collective_information->collective_mn_region_id == 64) {
                $p->all_city_id = 49;
                $p->save();
            }
            elseif ($p->collective_information->collective_mn_region_id == 73) {
                $p->all_city_id = 50;
                $p->save();
            }
            elseif ($p->collective_information->collective_mn_region_id == 83) {
                $p->all_city_id = 51;
                $p->save();
            }
            elseif ($p->collective_information->collective_mn_region_id == 22) {
                $p->all_city_id = 52;
                $p->save();
            }
            elseif ($p->collective_information->collective_mn_region_id == 46) {
                $p->all_city_id = 53;
                $p->save();
            }
            elseif ($p->collective_information->collective_mn_region_id == 61) {
                $p->all_city_id = 54;
                $p->save();
            }
            elseif ($p->collective_information->collective_mn_region_id == 48) {
                $p->all_city_id = 55;
                $p->save();
            }
            elseif ($p->collective_information->collective_mn_region_id == 26) {
                $p->all_city_id = 56;
                $p->save();
            }
            elseif ($p->collective_information->collective_mn_region_id == 43) {
                $p->all_city_id = 57;
                $p->save();
            }
            elseif ($p->collective_information->collective_mn_region_id == 51) {
                $p->all_city_id = 58;
                $p->save();
            }
            elseif ($p->collective_information->collective_mn_region_id == 53) {
                $p->all_city_id = 59;
                $p->save();
            }
            elseif ($p->collective_information->collective_mn_region_id == 84) {
                $p->all_city_id = 60;
                $p->save();
            }
            elseif ($p->collective_information->collective_mn_region_id == 76) {
                $p->all_city_id = 61;
                $p->save();
            }
            elseif ($p->collective_information->collective_mn_region_id == 25) {
                $p->all_city_id = 62;
                $p->save();
            }
            elseif ($p->collective_information->collective_mn_region_id == 36) {
                $p->all_city_id = 63;
                $p->save();
            }
            elseif ($p->collective_information->collective_mn_region_id == 60) {
                $p->all_city_id = 64;
                $p->save();
            }
            elseif ($p->collective_information->collective_mn_region_id == 65) {
                $p->all_city_id = 65;
                $p->save();
            }
            elseif ($p->collective_information->collective_mn_region_id == 59) {
                $p->all_city_id = 66;
                $p->save();
            }
            elseif ($p->collective_information->collective_mn_region_id == 9) {
                $p->all_city_id = 67;
                $p->save();
            }
            elseif ($p->collective_information->collective_mn_region_id == 28) {
                $p->all_city_id = 68;
                $p->save();
            }
            elseif ($p->collective_information->collective_mn_region_id == 44) {
                $p->all_city_id = 69;
                $p->save();
            }
            elseif ($p->collective_information->collective_mn_region_id == 62) {
                $p->all_city_id = 70;
                $p->save();
            }
            elseif ($p->collective_information->collective_mn_region_id == 67) {
                $p->all_city_id = 71;
                $p->save();
            }
            elseif ($p->collective_information->collective_mn_region_id == 71) {
                $p->all_city_id = 72;
                $p->save();
            }
            elseif ($p->collective_information->collective_mn_region_id == 75) {
                $p->all_city_id = 73;
                $p->save();
            }





        }
*/


        $kol = CollectiveDirector::where('all_city_id', null)->where('collective_mn_region_old_id', '!=',null)->get();

     foreach ($kol as $p) {


         if ($p->collective_mn_region_old_id == 84) {
             $p->all_city_id = 60;
             $p->save();
         }
         elseif ($p->collective_mn_region_old_id == 77) {
             $p->all_city_id = 21;
             $p->save();
         }
         elseif ($p->collective_mn_region_old_id == 53) {
             $p->all_city_id = 59;
             $p->save();
         }
         elseif ($p->collective_mn_region_old_id == 51) {
             $p->all_city_id = 58;
             $p->save();
         }
         elseif ($p->collective_mn_region_old_id == 43) {
             $p->all_city_id = 57;
             $p->save();
         }
         elseif ($p->collective_mn_region_old_id == 40) {
             $p->all_city_id = 20;
             $p->save();
         }
         elseif ($p->collective_mn_region_old_id == 39) {
             $p->all_city_id = 19;
             $p->save();
         }
         elseif ($p->collective_mn_region_old_id == 30) {
             $p->all_city_id = 18;
             $p->save();
         }
         elseif ($p->collective_mn_region_old_id == 26) {
             $p->all_city_id = 56;
             $p->save();
         }
         elseif ($p->collective_mn_region_old_id == 4) {
             $p->all_city_id = 16;
             $p->save();
         }










     }

    }

    public function ContactDistrictUser()
    {
        //$personal = PersonalUserCardInformation::where('economic_district_id', null)->get();
        $personal = CollectiveDirector::where('economic_district_id', null)->get();
        foreach ($personal as $p) {
            if ($p->all_city_id >= 2 && $p->all_city_id <= 4) {
                $p->economic_district_id = 2;
                $p->save();
            }
            elseif ($p->all_city_id == 1) {
                $p->economic_district_id = 1;
                $p->save();
            }
            elseif ($p->all_city_id >= 5 && $p->all_city_id <= 8){
                $p->economic_district_id = 3;
                $p->save();
            }
            elseif ($p->all_city_id >= 9 && $p->all_city_id <= 14){
                $p->economic_district_id = 4;
                $p->save();
            }
            elseif ($p->all_city_id >= 15 && $p->all_city_id <= 22){
                $p->economic_district_id = 5;
                $p->save();
            }
            elseif ($p->all_city_id >= 23 && $p->all_city_id <= 27){
                $p->economic_district_id = 6;
                $p->save();
            }
            elseif ($p->all_city_id >= 28 && $p->all_city_id <= 32){
                $p->economic_district_id = 7;
                $p->save();
            }
            elseif ($p->all_city_id >= 33 && $p->all_city_id <= 38){
                $p->economic_district_id = 8;
                $p->save();
            }
            elseif ($p->all_city_id >= 39 && $p->all_city_id <= 45){
                $p->economic_district_id = 9;
                $p->save();
            }
            elseif ($p->all_city_id >= 46 && $p->all_city_id <= 49){
                $p->economic_district_id = 10;
                $p->save();
            }
            elseif ($p->all_city_id >= 50 && $p->all_city_id <= 55){
                $p->economic_district_id = 11;
                $p->save();
            }
            elseif ($p->all_city_id >= 56 && $p->all_city_id <= 60){
                $p->economic_district_id = 12;
                $p->save();
            }
            elseif ($p->all_city_id >= 61 && $p->all_city_id <= 65){
                $p->economic_district_id = 13;
                $p->save();
            }
            elseif ($p->all_city_id >= 66 && $p->all_city_id <= 73){
                $p->economic_district_id = 14;
                $p->save();
            }
        }
    }

    public function personalSpecialCategories()
    {
        $personal_special_categories = PersonalSpecialCategory::all();

        foreach ($personal_special_categories as $psc){
            $puci = PersonalUserCardInformation::whereHas('personal_user_form_information', function ($q) use($psc) {
                $q->where('nomination_id', '=', $psc->nomination_id)->where('art_type',$psc->art_type);
            })->where('age_category', $psc->age_category)->get();

            foreach ($puci as $pi){
                $pi->psc_id = $psc->id;
                $pi->save();
            }
        }

    }
    public function personalAwardsCreate()
    {
        return 99;
        $personal_special_categories = PersonalUserCardInformation::orderByDesc('id')->get();


        foreach ($personal_special_categories as $k => $psc){
            if(count($psc->personal_user_awards) > 0){
                break;
            }
            else{
                $x = new Award();
                $x->personal_user_id = $psc->personal_user_id;
                $x->awards_name = "Yoxdur";
                $x->save();
            }
        }


    }
    public function collectiveAwardsCreate()
    {

        $collective_special_categories = CollectiveDirector::all();


        foreach ($collective_special_categories as $k => $csc){
            if(count($csc->collective_awards_info) > 0){
                continue;
            }
            else{
                $x = new CollectiveAward();
                $x->collective_id = $csc->collective_id;
                $x->awards_name = "Yoxdur";
                $x->save();
            }
        }


    }
}
