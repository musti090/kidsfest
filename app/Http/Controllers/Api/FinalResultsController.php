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
use Illuminate\Support\Facades\Log;

class FinalResultsController extends Controller
{
    public function index(Request $request)
    {
        $user_data = [];
        try {

            if($request->has('type') && $request->type == "personal"){
                if($request->has('document') && $request->document == "fin_code"){
                    if($request->value != null){
                        $fin_code =  PersonalUserCardInformation::query()->where('is_final_pass','=',1)->where('fin_code',$request->value);
                        if($fin_code->exists()){
                            $data = $fin_code->with('personal_user_form_information', 'personal_user_parent_information', 'personal_user_awards')->firstOrFail();
                            $user_data['ad_soyad'] = $data->card_name." ".$data->card_surname;
                            $user_data['yas_kateqoriyasi'] = $data->age_category;
                            $user_data['nominasiya'] = $this->getNominationName($data->personal_user_form_information->nomination_id);
                            $user_data['yer'] = $data->winner_place;
                            $user_data['bacariq_seviyyesi'] = $this->getSkill($data->personal_user_form_information->art_type);
                            $user_data['qalibi_yoxla'] = $data->is_winner;
                            Log::info($data->UIN);
                            return response($user_data,200);
                        }
                    }
                    return response(['message' =>'Məlumat tapılmadı!']);
                }
                elseif($request->has('document') && $request->document == "birth_card"){
                    if($request->value != null){
                        $birth_card =  PersonalUserCardInformation::query()->where('is_final_pass','=',1)->where('birth_card',$request->value);
                        if($birth_card->exists()){
                            $data = $birth_card->with('personal_user_form_information', 'personal_user_parent_information', 'personal_user_awards')->firstOrFail();
                            $user_data['ad_soyad'] = $data->personal_user_form_information->name." ".$data->personal_user_form_information->surname;
                            $user_data['yas_kateqoriyasi'] = $data->age_category;
                            $user_data['nominasiya'] = $this->getNominationName($data->personal_user_form_information->nomination_id);
                            $user_data['yer'] = $data->winner_place;
                            $user_data['bacariq_seviyyesi'] = $this->getSkill($data->personal_user_form_information->art_type);
                            $user_data['qalibi_yoxla'] = $data->is_winner;
                            Log::info($data->UIN);
                            return response($user_data,200);
                        }

                    }

                    return response(['message' =>'Məlumat tapılmadı!']);
                }
                elseif($request->has('document') && $request->document == "id_code"){
                    $id_code =  PersonalUserCardInformation::query()->where('is_final_pass','=',1)->where('UIN',$request->value);
                    if($id_code->exists()){
                        $data = $id_code->with('personal_user_form_information', 'personal_user_parent_information', 'personal_user_awards')->firstOrFail();

                        if($data->fin_code == null){
                            $user_data['ad_soyad'] = $data->personal_user_form_information->name." ".$data->personal_user_form_information->surname;
                        }
                        else{
                            $user_data['ad_soyad'] = $data->card_name." ".$data->card_surname;
                        }
                        $user_data['yas_kateqoriyasi'] = $data->age_category;
                        $user_data['nominasiya'] = $this->getNominationName($data->personal_user_form_information->nomination_id);
                        $user_data['yer'] = $data->winner_place;
                        $user_data['bacariq_seviyyesi'] = $this->getSkill($data->personal_user_form_information->art_type);
                        $user_data['qalibi_yoxla'] = $data->is_winner;
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
                        $col_fin_code =  CollectiveDirector::query()->where('is_final_pass','=',1)->where('parent_fin_code',$request->value);
                        if($col_fin_code->exists()){
                            $data = $col_fin_code->with('collective_information')->firstOrFail();
                          //  $user_data['ad_soyad'] = $data->parent_card_name." ".$data->parent_card_surname;
                            $user_data['kollektivin_adi'] = $data->collective_information->collective_name;
                            $user_data['yas_kateqoriyasi'] = $data->age_category;
                            $user_data['yer'] = $data->winner_place;
                            $user_data['qalibi_yoxla'] = $data->is_winner;
                            $user_data['nominasiya'] = $this->getNominationName($data->collective_information->collective_nomination_id);
                            Log::info($data->UIN);
                            return response($user_data,200);
                        }
                    }
                    return response(['message' =>'Məlumat tapılmadı!']);
                }
                elseif($request->has('document') && $request->document == "col_id_code"){
                    $col_id_code =  CollectiveDirector::query()->where('is_final_pass','=',1)->where('UIN',$request->value);
                    if($col_id_code->exists()){
                        $data = $col_id_code->with('collective_information')->firstOrFail();
                      //  $user_data['ad_soyad'] = $data->parent_card_name." ".$data->parent_card_surname;
                        $user_data['kollektivin_adi'] = $data->collective_information->collective_name;
                        $user_data['yas_kateqoriyasi'] = $data->age_category;
                        $user_data['yer'] = $data->winner_place;
                        $user_data['qalibi_yoxla'] = $data->is_winner;
                        $user_data['nominasiya'] = $this->getNominationName($data->collective_information->collective_nomination_id);
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

    private function getNominationName($id){
        $region = Nomination::select('id','name')->where('id',$id)->firstOrFail();
        return $region->name;
    }
    private function getSkill($id){
       if($id == 1){
           $skill = 'peşəkar';
       }
       else{
           $skill = 'həvəskar';
       }
        return $skill;
    }


}
