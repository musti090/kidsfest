<?php

namespace App\Http\Controllers\Admin\Test\Personal;

use App\Http\Controllers\Controller;
use App\Models\PersonalUserCardInformation;
use App\Models\Precinct;
use App\Models\PrecinctsHasNomination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DateInterval;
use DatePeriod;
use DateTime;
class FirstStepTestPersonalController extends Controller
{
    public function ContactCityRegion()
    {
        // Fərdi
        /*     $personal = PersonalUserCardInformation::with('personal_user_form_information')->where('city_id', null)->get();
                foreach ($personal as $p) {
                    if ($p->personal_user_form_information->mn_region_id >= 10 && $p->personal_user_form_information->mn_region_id <= 21) {
                        $p->city_id = 1;
                        $p->save();
                    } elseif ($p->personal_user_form_information->mn_region_id == 69) {
                        $p->city_id = 2;
                        $p->save();
                    } elseif ($p->personal_user_form_information->mn_region_id >= 1 && $p->personal_user_form_information->mn_region_id <= 2) {
                        $p->city_id = 3;
                        $p->save();
                    } elseif ($p->personal_user_form_information->mn_region_id == 38) {
                        $p->city_id = 4;
                        $p->save();
                    } elseif ($p->personal_user_form_information->mn_region_id == 7) {
                        $p->city_id = 5;
                        $p->save();
                    } elseif ($p->personal_user_form_information->mn_region_id == 42) {
                        $p->city_id = 6;
                        $p->save();
                    } elseif ($p->personal_user_form_information->mn_region_id == 49) {
                        $p->city_id = 7;
                        $p->save();
                    } elseif ($p->personal_user_form_information->mn_region_id == 72) {
                        $p->city_id = 8;
                        $p->save();
                    } elseif ($p->personal_user_form_information->mn_region_id == 32) {
                        $p->city_id = 9;
                        $p->save();
                    } elseif ($p->personal_user_form_information->mn_region_id == 66) {
                        $p->city_id = 10;
                        $p->save();
                    } elseif ($p->personal_user_form_information->mn_region_id == 29) {
                        $p->city_id = 11;
                        $p->save();
                    } elseif ($p->personal_user_form_information->mn_region_id == 58) {
                        $p->city_id = 12;
                        $p->save();
                    } elseif ($p->personal_user_form_information->mn_region_id == 33) {
                        $p->city_id = 13;
                        $p->save();
                    } elseif ($p->personal_user_form_information->mn_region_id == 35) {
                        $p->city_id = 14;
                        $p->save();
                    } elseif ($p->personal_user_form_information->mn_region_id == 3) {
                        $p->city_id = 15;
                        $p->save();
                    } elseif ($p->personal_user_form_information->mn_region_id == 4) {
                        $p->city_id = 16;
                        $p->save();
                    } elseif ($p->personal_user_form_information->mn_region_id == 24) {
                        $p->city_id = 17;
                        $p->save();
                    } elseif ($p->personal_user_form_information->mn_region_id == 30) {
                        $p->city_id = 18;
                        $p->save();
                    } elseif ($p->personal_user_form_information->mn_region_id == 39) {
                        $p->city_id = 19;
                        $p->save();
                    } elseif ($p->personal_user_form_information->mn_region_id == 40) {
                        $p->city_id = 20;
                        $p->save();
                    } elseif ($p->personal_user_form_information->mn_region_id == 77) {
                        $p->city_id = 21;
                        $p->save();
                    } elseif ($p->personal_user_form_information->mn_region_id == 78) {
                        $p->city_id = 22;
                        $p->save();
                    } elseif ($p->personal_user_form_information->mn_region_id == 6) {
                        $p->city_id = 23;
                        $p->save();
                    } elseif ($p->personal_user_form_information->mn_region_id == 31) {
                        $p->city_id = 24;
                        $p->save();
                    } elseif ($p->personal_user_form_information->mn_region_id == 47) {
                        $p->city_id = 25;
                        $p->save();
                    } elseif ($p->personal_user_form_information->mn_region_id == 74) {
                        $p->city_id = 26;
                        $p->save();
                    } elseif ($p->personal_user_form_information->mn_region_id == 79) {
                        $p->city_id = 27;
                        $p->save();
                    } elseif ($p->personal_user_form_information->mn_region_id == 37) {
                        $p->city_id = 28;
                        $p->save();
                    } elseif ($p->personal_user_form_information->mn_region_id == 50) {
                        $p->city_id = 29;
                        $p->save();
                    } elseif ($p->personal_user_form_information->mn_region_id == 52) {
                        $p->city_id = 30;
                        $p->save();
                    } elseif ($p->personal_user_form_information->mn_region_id == 68) {
                        $p->city_id = 31;
                        $p->save();
                    } elseif ($p->personal_user_form_information->mn_region_id == 70) {
                        $p->city_id = 32;
                        $p->save();
                    } elseif ($p->personal_user_form_information->mn_region_id == 8) {
                        $p->city_id = 33;
                        $p->save();
                    } elseif ($p->personal_user_form_information->mn_region_id == 27) {
                        $p->city_id = 34;
                        $p->save();
                    } elseif ($p->personal_user_form_information->mn_region_id == 54) {
                        $p->city_id = 35;
                        $p->save();
                    } elseif ($p->personal_user_form_information->mn_region_id == 55) {
                        $p->city_id = 36;
                        $p->save();
                    } elseif ($p->personal_user_form_information->mn_region_id == 56) {
                        $p->city_id = 37;
                        $p->save();
                    } elseif ($p->personal_user_form_information->mn_region_id == 81) {
                        $p->city_id = 38;
                        $p->save();
                    } elseif ($p->personal_user_form_information->mn_region_id == 57) {
                        $p->city_id = 39;
                        $p->save();
                    } elseif ($p->personal_user_form_information->mn_region_id == 5) {
                        $p->city_id = 40;
                        $p->save();
                    } elseif ($p->personal_user_form_information->mn_region_id == 80) {
                        $p->city_id = 41;
                        $p->save();
                    } elseif ($p->personal_user_form_information->mn_region_id == 85) {
                        $p->city_id = 42;
                        $p->save();
                    } elseif ($p->personal_user_form_information->mn_region_id == 34) {
                        $p->city_id = 43;
                        $p->save();
                    } elseif ($p->personal_user_form_information->mn_region_id == 45) {
                        $p->city_id = 44;
                        $p->save();
                    } elseif ($p->personal_user_form_information->mn_region_id == 82) {
                        $p->city_id = 45;
                        $p->save();
                    } elseif ($p->personal_user_form_information->mn_region_id == 23) {
                        $p->city_id = 46;
                        $p->save();
                    } elseif ($p->personal_user_form_information->mn_region_id == 41) {
                        $p->city_id = 47;
                        $p->save();
                    } elseif ($p->personal_user_form_information->mn_region_id == 63) {
                        $p->city_id = 48;
                        $p->save();
                    } elseif ($p->personal_user_form_information->mn_region_id == 64) {
                        $p->city_id = 49;
                        $p->save();
                    } elseif ($p->personal_user_form_information->mn_region_id == 73) {
                        $p->city_id = 50;
                        $p->save();
                    } elseif ($p->personal_user_form_information->mn_region_id == 83) {
                        $p->city_id = 51;
                        $p->save();
                    } elseif ($p->personal_user_form_information->mn_region_id == 22) {
                        $p->city_id = 52;
                        $p->save();
                    } elseif ($p->personal_user_form_information->mn_region_id == 46) {
                        $p->city_id = 53;
                        $p->save();
                    } elseif ($p->personal_user_form_information->mn_region_id == 61) {
                        $p->city_id = 54;
                        $p->save();
                    } elseif ($p->personal_user_form_information->mn_region_id == 48) {
                        $p->city_id = 55;
                        $p->save();
                    } elseif ($p->personal_user_form_information->mn_region_id == 26) {
                        $p->city_id = 56;
                        $p->save();
                    } elseif ($p->personal_user_form_information->mn_region_id == 43) {
                        $p->city_id = 57;
                        $p->save();
                    } elseif ($p->personal_user_form_information->mn_region_id == 51) {
                        $p->city_id = 58;
                        $p->save();
                    } elseif ($p->personal_user_form_information->mn_region_id == 53) {
                        $p->city_id = 59;
                        $p->save();
                    } elseif ($p->personal_user_form_information->mn_region_id == 84) {
                        $p->city_id = 60;
                        $p->save();
                    } elseif ($p->personal_user_form_information->mn_region_id == 76) {
                        $p->city_id = 61;
                        $p->save();
                    } elseif ($p->personal_user_form_information->mn_region_id == 25) {
                        $p->city_id = 62;
                        $p->save();
                    } elseif ($p->personal_user_form_information->mn_region_id == 36) {
                        $p->city_id = 63;
                        $p->save();
                    } elseif ($p->personal_user_form_information->mn_region_id == 60) {
                        $p->city_id = 64;
                        $p->save();
                    } elseif ($p->personal_user_form_information->mn_region_id == 65) {
                        $p->city_id = 65;
                        $p->save();
                    } elseif ($p->personal_user_form_information->mn_region_id == 59) {
                        $p->city_id = 66;
                        $p->save();
                    } elseif ($p->personal_user_form_information->mn_region_id == 9) {
                        $p->city_id = 67;
                        $p->save();
                    } elseif ($p->personal_user_form_information->mn_region_id == 28) {
                        $p->city_id = 68;
                        $p->save();
                    } elseif ($p->personal_user_form_information->mn_region_id == 44) {
                        $p->city_id = 69;
                        $p->save();
                    } elseif ($p->personal_user_form_information->mn_region_id == 62) {
                        $p->city_id = 70;
                        $p->save();
                    } elseif ($p->personal_user_form_information->mn_region_id == 67) {
                        $p->city_id = 71;
                        $p->save();
                    } elseif ($p->personal_user_form_information->mn_region_id == 71) {
                        $p->city_id = 72;
                        $p->save();
                    } elseif ($p->personal_user_form_information->mn_region_id == 75) {
                        $p->city_id = 73;
                        $p->save();
                    } elseif ($p->personal_user_form_information->mn_region_id == 86) {
                        $p->city_id = 74;
                        $p->save();
                    }

                }*/

        // Yoxlayiriq
/*        $all = DB::table('all_cities')->select('id')->get();
        foreach ($all as  $item) {
            $all_data = DB::table('personal_user_card_information')
                ->leftJoin('personal_users', 'personal_user_card_information.personal_user_id', '=', 'personal_users.id')
                ->where('city_id',$item->id)
                ->get();
            $all_cities = DB::table('all_cities')->pluck('city_name', 'id');
            $all_regions = DB::table('m_n_regions')->pluck('name', 'id');

            foreach ($all_data as $key =>$data) {
                echo ($key + 1)." ".$all_cities[$data->city_id]."------------".$all_regions[$data->mn_region_id]."<br>";
            }
        }*/


    }

    public function menteqeIstirakci()
    {

        $phasnom = PrecinctsHasNomination::all();
        foreach ($phasnom as $p) {
            $n_id = $p->nomination_id;
            $x = PersonalUserCardInformation::whereHas('personal_user_form_information', function ($q) use ($n_id) {
                $q->where('nomination_id', '=', $n_id);
            })->where('precinct_id', null)->where('city_id', $p->city_id)->get();
            foreach ($x as $y) {
                $y->precinct_id = $p->precinct_id;
                $y->save();
            }
        }


    }

    public function menteqeVaxt()
    {

        $phasnom = DB::table('precincts_has_nominations')->where('nomination_id', '<', 20)->get();


        $saatlar = ['10:00', '11:00', '12:00', '13:00'];
        //  $p = PrecinctsHasNomination::find(20);
        foreach ($phasnom as $p) {

            $n_id = $p->nomination_id;

            $x = PersonalUserCardInformation::whereHas('personal_user_form_information', function ($q) use ($n_id) {
                $q->where('nomination_id', '=', $n_id);
            })->orderBy('age')->where('precinct_id', $p->precinct_id)->get();
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
            if ($value->format('Y-m-d') == '2025-05-09' || $value->format('Y-m-d') == '2025-05-28' || $value->format('Y-m-d') == '2025-06-06' || $value->format('Y-m-d') == '2025-06-07' || $value->format('Y-m-d') == '2025-06-15' || $value->format('Y-m-d') == '2025-06-26') continue;
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
}
