<?php

namespace App\Http\Controllers\Admin\Personal;

use App\Exports\PersonalExport;
use App\Http\Controllers\Controller;
use App\Services\ExcelExportServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class PersonalController extends Controller
{

    private ExcelExportServices $excelExportServices;

    public function __construct(ExcelExportServices $excelExportServices)
    {
        $this->excelExportServices = $excelExportServices;
    }

    public function index(Request $request)
    {
        try {
            $nominations = Cache::get('personalNominations');
            $cities = Cache::get('AllCity');
            $regions = Cache::get('MNRegion');

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
            if ($request->get("patronymic")) {
                $data->where('personal_users.patronymic', 'like', '%' . $request->get("patronymic") . '%');
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
            $count = $data->count();
            $data = $data->paginate(25)->appends($request->query());
            return view('backend.pages.firstStep.all-personal-users', compact('data', 'count', 'nominations', 'cities', 'regions'));

        } catch (\Exception $e) {

            return $e->getMessage();
        }


    }

    public function detail($id)
    {
        try {
            $data = DB::table('personal_users')
                ->leftJoin('personal_user_card_information', 'personal_user_card_information.personal_user_id', '=', 'personal_users.id')
                ->leftJoin('personal_user_parents', 'personal_user_parents.personal_user_id', '=', 'personal_users.id')
                ->where('personal_users.id', $id)
                ->first() ?? null;

            $awards = DB::table('personal_awards')->where('personal_user_id', $id)->select('id', 'awards_name')->get();

            return view('backend.pages.firstStep.personal-user-detail', compact('data', 'awards'));

        } catch (\Exception $e) {

            return $e->getMessage();
        }

    }

    public function exportExcel(Request $request)
    {
        try {
            $data = $this->excelExportServices->getPersonalData($request);
            return Excel::download(new PersonalExport($data), 'Fərdi iştirakçılar.xlsx');

        } catch (\Exception $e) {

            return $e->getMessage();
        }
    }
}
