<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ExcelExportServices
{
    public function getPersonalData($request)
    {


        $data = DB::table('personal_users')
            ->leftJoin('personal_user_card_information', 'personal_user_card_information.personal_user_id', '=', 'personal_users.id')
            ->leftJoin('personal_user_parents', 'personal_user_parents.personal_user_id', '=', 'personal_users.id');

        if ($request->get("name")) {
            $data->where("personal_users.name", $request->get("name"));
        }
        if ($request->get("surname")) {
            $data->where("personal_users.surname", $request->get("surname"));
        }
        if ($request->get("patronymic")) {
            $data->where('personal_users.patronymic', 'like', '%' . $request->get("patronymic") . '%');
        }
        if ($request->get("birth_date")) {
            $data->where("personal_users.birth_date", $request->get("birth_date"));
        }
        if ($request->get("gender")) {
            $data->where("personal_users.gender", $request->get("gender"));
        }
        if ($request->get("UIN")) {
            $data->where("personal_user_card_information.UIN", $request->get("UIN"));
        }
        if ($request->get("fin_code")) {
            $data->where("personal_user_card_information.fin_code", $request->get("fin_code"));
        }
        if ($request->get("nomination_id")) {
            $data->where("personal_users.nomination_id", $request->get("nomination_id"));
        }
        if ($request->get("mn_region_id")) {
            $data->where("personal_users.mn_region_id", $request->get("mn_region_id"));
        }
        if ($request->get("all_city_id")) {
            $data->where("personal_user_card_information.all_city_id", $request->get("all_city_id"));
        }
        if ($request->get("test")) {
            $data->where("personal_users.test", $request->get("test"));
        }
        /*    if ($request->get("school_type_id")) {
                $data->where("personal_users.school_type_id", $request->get("school_type_id"));
            }
            if ($request->get("school_id")) {
                $data->where("personal_users.school_id", $request->get("school_id"));
            }*/
        if ($request->all() == []) {
            $data->take(0);
        }

        return $data->get();
    }


    public function getCollectiveData($request)
    {
        $data = DB::table('collectives')
            ->leftJoin('collective_directors', 'collective_directors.collective_id', '=', 'collectives.id');
        if ($request->get("UIN")) {
            $data->where("collective_directors.UIN", $request->get("UIN"));
        }
        if ($request->get("director_fin_code")) {
            $data->where("collective_directors.director_fin_code", $request->get("director_fin_code"));
        }
        if ($request->get("director_name")) {
            $data->where("collective_directors.director_name", $request->get("director_name"));
        }
        if ($request->get("director_surname")) {
            $data->where("collective_directors.director_surname", $request->get("director_surname"));
        }
        if ($request->get("director_patronymic")) {
            $data->where('collective_directors.director_patronymic', 'like', '%' . $request->get("director_patronymic") . '%');
        }
        if ($request->get("collective_created_date")) {
            $data->where("collectives.collective_created_date", $request->get("collective_created_date"));
        }
        if ($request->get("collective_name")) {
            $data->where('collectives.collective_name', 'like', '%' . $request->get("collective_name") . '%');
        }
        if ($request->get("collective_nomination_id")) {
            $data->where("collectives.collective_nomination_id", $request->get("collective_nomination_id"));
        }
        if ($request->get("collective_mn_region_id")) {
            $data->where("collectives.collective_mn_region_id", $request->get("collective_mn_region_id"));
        }
        if ($request->get("collective_city_id")) {
            $data->where("collectives.collective_city_id", $request->get("collective_city_id"));
        }
        if ($request->get("test")) {
            $data->where("collectives.test", $request->get("test"));
        }
        if ($request->all() == []) {
            $data->take(0);
        }
        return $data->get();
    }

}
