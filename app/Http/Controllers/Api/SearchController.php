<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CollectiveDirector;
use App\Models\MNRegion;
use App\Models\Nomination;
use App\Models\PersonalUser;
use App\Models\PersonalUserCardInformation;
use App\Models\ThirdStepPrecinct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        try {
            if($request->has('type') && $request->type == "personal"){
                if($request->has('document') && $request->document == "fin_code"){
                    if($request->value != null){
                        $fin_code =  DB::table('personal_user_card_information')->select('UIN')->where('fin_code',strtoupper($request->value));
                      if($fin_code->exists()){
                            return response(['message' => $fin_code->first()->UIN]);
                        }
                    }
                    return response(['message' =>'Məlumat tapılmadı!']);
                 }
                elseif($request->has('document') && $request->document == "id_code"){
                    if($request->value != null){
                        $id_code =  DB::table('personal_user_card_information')->select('UIN')->where('UIN',$request->value);
                        if($id_code->exists()){
                            return response(['message' => $id_code->first()->UIN]);
                        }
                    }
                    return response(['message' =>'Məlumat tapılmadı!']);
                }
                else{
                    return response(['message' =>'Məlumat tapılmadı!']);
                }

            }
          /*  elseif($request->has('type') && $request->type == "collective"){
                if($request->has('document') && $request->document == "col_fin_code"){
                    if($request->value != null){
                        $col_fin_code =  CollectiveDirector::query()->where('parent_fin_code',$request->value);
                        if($col_fin_code->exists()){
                            return response(['message' => $col_fin_code->first()->UIN]);
                        }
                    }
                    return response(['message' =>'Məlumat tapılmadı!'],404);
                }
                elseif($request->has('document') && $request->document == "col_id_code"){
                    $col_id_code =  CollectiveDirector::query()->where('UIN',$request->value);
                    if($col_id_code->exists()){
                        return response(['message' => $col_id_code->first()->UIN]);
                    }
                    return response(['message' =>'Məlumat tapılmadı!'],404);
                }
                return response(['message' =>'Səhv sorğu !!!']);
            }*/
            else {
                return response(['message' =>'Məlumat tapılmadı!']);
            }
        }
        catch (\Exception $exception){
            $exception->getMessage();
        }
    }

    public function timePlace(Request $request)
    {

           //   $data = PersonalUserCardInformation::query()->where('fin_code','65AM6VG')->with('personal_user_form_information', 'personal_user_parent_information', 'personal_user_awards')->firstOrFail();
              $user_data = [];
        try {

            if($request->has('type') && $request->type == "personal"){
                if($request->has('document') && $request->document == "fin_code"){
                    if($request->value != null){
                        $fin_code =  PersonalUserCardInformation::query()->where('fin_code',$request->value);
                        if($fin_code->exists()){
                            $data = $fin_code->with('personal_user_form_information', 'personal_user_parent_information', 'personal_user_awards')->firstOrFail();
                            if($data->date == null || $data->time == null){
                                return response(['message' =>'Hörmətli iştirakçı, müraciətinizə görə təşəkkür edirik! Böyük sorğu sayını nəzərə alaraq sistemdə uyğunlaşdırma prosesi aparılır. Sizin vaxt və məkan məlumatlarınız qısa zaman ərzində təqdim olunacaqdır. Məlumatı bir neçə saatdan sonra yenidən yoxlamağınız xahiş olunur.']);
                            }
                            $user_data['ad_soyad'] = $data->card_name." ".$data->card_surname;
                            $user_data['kod'] = $data->UIN;
                            $user_data['rayon'] = $this->getRegionName($data->personal_user_form_information->mn_region_id);
                            $user_data['nominasiya'] = $this->getNominationName($data->personal_user_form_information->nomination_id);
                            $user_data['gun'] = $this->getDate($data->third_step_date);
                            $user_data['vaxt'] = $this->getTime($data->third_step_time);
                            $user_data['mekan'] = $this->getPlace($data->third_step_precinct_id);
                            $user_data['bal'] = $data->is_final_pass;
                          //  $user_data['nomre'] = $this->getNumber($data->precinct_id);
                            Log::info($data->UIN);
                            return response($user_data,200);
                        }
                    }
                    return response(['message' =>'Məlumat tapılmadı!']);
                }
                elseif($request->has('document') && $request->document == "birth_card"){
                    if($request->value != null){
                        $birth_card =  PersonalUserCardInformation::query()->where('birth_card',$request->value);
                        if($birth_card->exists()){
                            $data = $birth_card->with('personal_user_form_information', 'personal_user_parent_information', 'personal_user_awards')->firstOrFail();
                         /*   if($data->personal_user_form_information == null || $data->personal_user_parent_information == null){
                                return response(['message' =>'Məlumat tapılmadı!']);
                            }*/
                            if($data->date == null || $data->time == null){
                                return response(['message' =>'Hörmətli iştirakçı, müraciətinizə görə təşəkkür edirik! Böyük sorğu sayını nəzərə alaraq sistemdə uyğunlaşdırma prosesi aparılır. Sizin vaxt və məkan məlumatlarınız qısa zaman ərzində təqdim olunacaqdır. Məlumatı bir neçə saatdan sonra yenidən yoxlamağınız xahiş olunur.']);
                            }
                            $user_data['gun'] = $this->getDate($data->third_step_date);
                            $user_data['vaxt'] = $this->getTime($data->third_step_time);
                            $user_data['mekan'] = $this->getPlace($data->third_step_precinct_id);
                            $user_data['bal'] = $data->is_final_pass;
                            $user_data['ad_soyad'] = $data->personal_user_form_information->name." ".$data->personal_user_form_information->surname;
                            $user_data['kod'] = $data->UIN;
                            $user_data['rayon'] = $this->getRegionName($data->personal_user_form_information->mn_region_id);
                            $user_data['nominasiya'] = $this->getNominationName($data->personal_user_form_information->nomination_id);

                            //$user_data['nomre'] = $this->getNumber($data->precinct_id);
                            Log::info($data->UIN);
                            return response($user_data,200);
                        }

                    }

                    return response(['message' =>'Məlumat tapılmadı!']);
                }
                elseif($request->has('document') && $request->document == "id_code"){
                    $id_code =  PersonalUserCardInformation::query()->where('UIN',$request->value);
                    if($id_code->exists()){
                           $data = $id_code->with('personal_user_form_information', 'personal_user_parent_information', 'personal_user_awards')->firstOrFail();
                       /* if($data->personal_user_form_information == null || $data->personal_user_parent_information == null){
                            return response(['message' =>'Məlumat tapılmadı!']);
                        }*/
                        if($data->date == null || $data->time == null){
                            return response(['message' =>'Hörmətli iştirakçı, müraciətinizə görə təşəkkür edirik! Böyük sorğu sayını nəzərə alaraq sistemdə uyğunlaşdırma prosesi aparılır. Sizin vaxt və məkan məlumatlarınız qısa zaman ərzində təqdim olunacaqdır. Məlumatı bir neçə saatdan sonra yenidən yoxlamağınız xahiş olunur.']);
                        }
                        if($data->fin_code == null){
                            $user_data['ad_soyad'] = $data->personal_user_form_information->name." ".$data->personal_user_form_information->surname;
                        }
                        else{
                            $user_data['ad_soyad'] = $data->card_name." ".$data->card_surname;
                        }
                        $user_data['kod'] = $data->UIN;
                        $user_data['rayon'] = $this->getRegionName($data->personal_user_form_information->mn_region_id);
                        $user_data['nominasiya'] = $this->getNominationName($data->personal_user_form_information->nomination_id);
                        $user_data['gun'] = $this->getDate($data->third_step_date);
                        $user_data['vaxt'] = $this->getTime($data->third_step_time);
                        $user_data['mekan'] = $this->getPlace($data->third_step_precinct_id);
                        $user_data['bal'] = $data->is_final_pass;
                        Log::info($data->UIN);
                        return response($user_data,200);
                    }
                    return response(['message' =>'Məlumat tapılmadı!']);
                }
                return response(['message' =>'Səhv sorğu !!!']);
            }
            elseif($request->has('type') && $request->type == "collective"){
                if($request->has('document') && $request->document == "col_fin_code"){
                    if($request->value != null){
                        $col_fin_code =  CollectiveDirector::query()->where('parent_fin_code',$request->value);
                        if($col_fin_code->exists()){
                            $data = $col_fin_code->with('collective_information')->firstOrFail();
                            if($data->date == null || $data->time == null){
                                return response(['message' =>'Hörmətli iştirakçı, müraciətinizə görə təşəkkür edirik! Böyük sorğu sayını nəzərə alaraq sistemdə uyğunlaşdırma prosesi aparılır. Sizin vaxt və məkan məlumatlarınız qısa zaman ərzində təqdim olunacaqdır. Məlumatı bir neçə saatdan sonra yenidən yoxlamağınız xahiş olunur.']);
                            }
                            $user_data['ad_soyad'] = $data->parent_card_name." ".$data->parent_card_surname;
                            $user_data['kollektivin_adi'] = $data->collective_information->collective_name;
                            $user_data['kod'] = $data->UIN;
                            $user_data['rayon'] = $this->getRegionName($data->collective_information->collective_mn_region_id);
                            $user_data['nominasiya'] = $this->getNominationName($data->collective_information->collective_nomination_id);
                            $user_data['gun'] = $this->getDate($data->third_step_date);
                            $user_data['vaxt'] = $this->getTime($data->third_step_time);
                            $user_data['mekan'] = $this->getPlace($data->third_step_precinct_id);
                            $user_data['bal'] = $data->is_final_pass;
                           // $user_data['nomre'] = $this->getNumber($data->precinct_id);
                            Log::info($data->UIN);
                            return response($user_data,200);
                        }
                    }
                    return response(['message' =>'Məlumat tapılmadı!']);
                }
                elseif($request->has('document') && $request->document == "col_id_code"){
                    $col_id_code =  CollectiveDirector::query()->where('UIN',$request->value);
                    if($col_id_code->exists()){
                        $data = $col_id_code->with('collective_information')->firstOrFail();
                        if($data->date == null || $data->time == null){
                            return response(['message' =>'Hörmətli iştirakçı, müraciətinizə görə təşəkkür edirik! Böyük sorğu sayını nəzərə alaraq sistemdə uyğunlaşdırma prosesi aparılır. Sizin vaxt və məkan məlumatlarınız qısa zaman ərzində təqdim olunacaqdır. Məlumatı bir neçə saatdan sonra yenidən yoxlamağınız xahiş olunur.']);
                        }
                        $user_data['ad_soyad'] = $data->parent_card_name." ".$data->parent_card_surname;
                        $user_data['kollektivin_adi'] = $data->collective_information->collective_name;
                        $user_data['kod'] = $data->UIN;
                        $user_data['kod'] = $data->UIN;
                        $user_data['rayon'] = $this->getRegionName($data->collective_information->collective_mn_region_id);
                        $user_data['nominasiya'] = $this->getNominationName($data->collective_information->collective_nomination_id);
                        $user_data['gun'] = $this->getDate($data->third_step_date);
                        $user_data['vaxt'] = $this->getTime($data->third_step_time);
                        $user_data['mekan'] = $this->getPlace($data->third_step_precinct_id);
                        $user_data['bal'] = $data->is_final_pass;
                      //  $user_data['nomre'] = $this->getNumber($data->precinct_id);
                        Log::info($data->UIN);
                        return response($user_data,200);
                    }
                    return response(['message' =>'Məlumat tapılmadı!']);
                }
                return response(['message' =>'Səhv sorğu !!!']);
            }
            else {
                return response(['message' =>'Səhv sorğu !!!']);
            }

        }
        catch (\Exception $exception){
            $exception->getMessage();
        }
    }
    private function getRegionName($id){
        $region = MNRegion::select('id','name')->where('id',$id)->firstOrFail();
        return $region->name;
    }
    private function getNominationName($id){
        $region = Nomination::select('id','name')->where('id',$id)->firstOrFail();
        return $region->name;
    }
    private function getDate($date){
        if($date != null){
            $date = explode('-',$date);
            return $date[2].".".$date[1].".".$date[0];
        }
        return $date;
    }
    private function getTime($time){
        if($time != null){
            $time = explode(':',$time);
            return $time[0].":".$time[1];
        }
        return $time;
    }
    private function getPlace($id){
        if($id != null) {
            $place = ThirdStepPrecinct::select('id', 'place_name', 'place_address')->where('id', $id)->firstOrFail();
            $pa = [];
            $pa[0] = $place->place_name;
            $pa[1] = $place->place_address;
            return $pa;
        }
        return $id;
    }
    private function getNumber($id){
        if($id != null) {
            $place = Precinct::select('id', 'first_person_number')->where('id', $id)->firstOrFail();
            return $place->first_person_number;
        }
        return $id;
    }
/*   private function RemoveSpecialChar($str) {

        // Using str_replace() function
        // to replace the word
        $res = str_replace( array( '\'', '\r'), ' ', $str);

        // Returning the result
        return $res;
    }*/


}
