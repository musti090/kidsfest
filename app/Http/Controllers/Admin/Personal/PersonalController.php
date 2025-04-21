<?php

namespace App\Http\Controllers\Admin\Personal;

use App\Exports\PersonalExport;
use App\Exports\PersonalNumbersExport;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ExcelExportServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;

class PersonalController extends Controller
{
    /**
     * @var user
     */
    private $user;

    private ExcelExportServices $excelExportServices;

    public function __construct(ExcelExportServices $excelExportServices)
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
        $this->excelExportServices = $excelExportServices;
    }

    public function index(Request $request)
    {

        try {
            $cities_data = DB::table('all_cities')->pluck('city_name', 'id');
            $regions_data = DB::table('m_n_regions')->pluck('name', 'id');
            $nominations_data = DB::table('nominations')->pluck('name', 'id');
            $nominations = Cache::get('personalNominations');
            $cities = Cache::get('AllCity');
            $regions = Cache::get('MNRegion');
            $user_precinct = null;
            if ($this->user->getRoleNames()[0] == 'superadmin' || $this->user->getRoleNames()[0] == 'developer' || $this->user->getRoleNames()[0] == 'content manager') {
                $data = DB::table('personal_users')
                    ->leftJoin('personal_user_card_information', 'personal_user_card_information.personal_user_id', '=', 'personal_users.id')
                    ->leftJoin('personal_user_parents', 'personal_user_parents.personal_user_id', '=', 'personal_users.id')
                    ->orderByDesc('score')
                    ->orderBy('date')
                    ->orderBy('time');
            } else {
                $user_precinct = $this->user->user_precinct->id;
                $data = DB::table('personal_users')
                    ->leftJoin('personal_user_card_information', 'personal_user_card_information.personal_user_id', '=', 'personal_users.id')
                    ->leftJoin('personal_user_parents', 'personal_user_parents.personal_user_id', '=', 'personal_users.id')
                    ->where('personal_user_card_information.precinct_id', $user_precinct)
                    ->orderByDesc('score')
                    ->orderBy('date')
                    ->orderBy('time');
            }

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
            if ($request->get("age_category")) {
                $data->where("personal_user_card_information.age_category", $request->get("age_category"));
            }
            if ($request->get("age")) {
                $data->where("personal_user_card_information.age", $request->get("age"));
            }
       /*     if ($request->get("test")) {
                // $data->where("personal_users.test", $request->get("test"));
                $data->where('personal_users.registration_address', 'like', '%' . $request->get("test") . '%');

            }*/
            $count = $data->count();
            $data = $data->paginate(25)->appends($request->query());

            return view('backend.pages.firstStep.all-personal-users', compact('data', 'count', 'nominations', 'cities', 'regions', 'nominations_data', 'regions_data', 'cities_data','user_precinct'));

        } catch (\Exception $e) {

            return $e->getMessage();
        }


    }

    public function detail($id)
    {
        try {
            $awards = DB::table('personal_awards')->where('personal_user_id', $id)->select('id', 'awards_name')->get();

            if ($this->user->getRoleNames()[0] == 'superadmin' || $this->user->getRoleNames()[0] == 'developer' || $this->user->getRoleNames()[0] == 'content manager') {
                $data = DB::table('personal_users')
                    ->leftJoin('personal_user_card_information', 'personal_user_card_information.personal_user_id', '=', 'personal_users.id')
                    ->leftJoin('personal_user_parents', 'personal_user_parents.personal_user_id', '=', 'personal_users.id')
                    ->where('personal_users.id', $id)
                    ->first();

                return view('backend.pages.firstStep.personal-user-detail', compact('data', 'awards'));

            } else {
                $user_precinct = $this->user->user_precinct->id;
                $data = DB::table('personal_users')
                    ->leftJoin('personal_user_card_information', 'personal_user_card_information.personal_user_id', '=', 'personal_users.id')
                    ->leftJoin('personal_user_parents', 'personal_user_parents.personal_user_id', '=', 'personal_users.id')
                    ->where('personal_users.id', $id)
                    ->where('personal_user_card_information.precinct_id', $user_precinct)
                    ->first();

                if (empty($data)) {
                    return redirect()->route('backend.personal.users.list');
                }
                $judges = DB::table('judges')->where('nomination_id', $data->nomination_id)->where('precinct_id', $user_precinct)->get();
                $criteria = DB::table('criterion_nominations')->where('nomination_id', $data->nomination_id)->get();

                /*        $judge_list = [];
                        $criteria_list = [];

                        foreach ($criteria as $criterion) {
                            $criteria_list[] = DB::table('criteria')->where('id', $criterion->criterion_id)->first()->name;
                        }
                        foreach ($judges as $judge) {
                            $judge_list[] = DB::table('first_step_judges_list')->where('id', $judge->judge_id)->first()->name;
                        }*/
                return view('backend.pages.firstStep.personal-user-detail', compact('data', 'awards', 'judges', 'criteria'));

            }

        } catch (\Exception $e) {

            return $e->getMessage();
        }

    }

    public function exportExcel(Request $request)
    {
        try {
            if ($this->user->getRoleNames()[0] == 'superadmin' || $this->user->getRoleNames()[0] == 'developer' || $this->user->getRoleNames()[0] == 'content manager') {
                $user_precinct = null;
                $data = $this->excelExportServices->getPersonalData($request, $user_precinct);
            } else {
                $user_precinct = $this->user->user_precinct->id;
                $data = $this->excelExportServices->getPersonalData($request, $user_precinct);
            }
            $cities = DB::table('all_cities')->pluck('city_name', 'id');
            $regions = DB::table('m_n_regions')->pluck('name', 'id');
            $nominations = DB::table('nominations')->pluck('name', 'id');
            $education_schools = DB::table('education_schools')->pluck('school_type', 'id');
            $education_school_names = DB::table('education_school_names')->pluck('name', 'id');
            $education_new_school_names = DB::table('education_school_new_names')->pluck('name', 'id');
            //  $awards = DB::table('personal_awards')->pluck('awards_name', 'id');
            return Excel::download(new PersonalExport($data, $cities, $regions, $nominations, $education_schools, $education_school_names, $education_new_school_names), 'Fərdi iştirakçılar.xlsx');
        } catch (\Exception $e) {

            return $e->getMessage();
        }
    }

    public function numbersExport(Request $request)
    {
        try {
            if ($this->user->getRoleNames()[0] == 'superadmin' || $this->user->getRoleNames()[0] == 'developer' || $this->user->getRoleNames()[0] == 'content manager') {
                $user_precinct = null;
                $data = $this->excelExportServices->getPersonalData($request, $user_precinct);
            } else {
                $user_precinct = $this->user->first_step_user_precinct->id;
                $data = $this->excelExportServices->getPersonalData($request, $user_precinct);
            }
            return Excel::download(new PersonalNumbersExport($data), 'Fərdi iştirakçılar.xlsx');
        } catch (\Exception $e) {

            return $e->getMessage();
        }
    }
}
