<?php

namespace App\Http\Controllers\Admin\Test\Personal;

use App\Http\Controllers\Controller;
use App\Models\Nomination;
use App\Models\PersonalUserCardInformation;
use App\Models\Precinct;
use App\Models\PrecinctsHasNomination;
use App\Models\User;
use App\Services\ExcelExportServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PersonalChangeController extends Controller
{
    /**
     * @var user
     */
    private $user;


    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
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
            $precinct_data = null;

            $data = DB::table('personal_users')
                ->leftJoin('personal_user_card_information', 'personal_user_card_information.personal_user_id', '=', 'personal_users.id')
                ->leftJoin('personal_user_parents', 'personal_user_parents.personal_user_id', '=', 'personal_users.id')
                ->where('personal_user_card_information.date', '=', null)
              //  ->where('personal_user_card_information.updated_at', '<', '2025-04-24 00:00:00')
               // ->where('precinct_id', 51)
                //  ->orWhere('precinct_id' , 52)
                //  ->orWhere('precinct_id' , 53)
                //   ->orWhere('precinct_id' , 54)
                ->orderBy('personal_user_card_information.precinct_id')
                ->orderBy('date')
                ->orderBy('time');


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
            if ($request->get("test")) {
                // $data->where("personal_users.test", $request->get("test"));
                $data->where('personal_users.registration_address', 'like', '%' . $request->get("test") . '%');

            }
            $count = $data->count();
            // return $data->take(3)->get();
            $data = $data->paginate(700)->appends($request->query());

            return view('backend.pages.test.all-personal-users', compact('data', 'count', 'nominations', 'cities', 'regions', 'nominations_data', 'regions_data', 'cities_data'));

        } catch (\Exception $e) {

            return $e->getMessage();
        }


    }

    public function edit($id)
    {
        if (is_null($this->user) || !$this->user->can('delete users')) {
            abort(403, 'İcazəniz yoxdur !!!');
        }
        $precincts = Precinct::orderBy('id')->get();
        $nominations = Nomination::all();
        $data = PersonalUserCardInformation::where('personal_user_id', $id)->first();
        return view('backend.pages.test.personal-edit', compact('precincts', 'nominations', 'data'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (is_null($this->user) || !$this->user->can('delete users')) {
            abort(403, 'İcazəniz yoxdur !!!');

        }


        $data = PersonalUserCardInformation::where('id', $id)->first();
        /*    if($data->date!= null || $data->time!= null || $data->precinct_id != null) {
                return redirect()->back();
            }*/
        $data->precinct_id = $request->precinct_id;
        $data->date = $request->date;
        $data->time = $request->time;
        $data->save();
        return redirect()->route('backend.personal-changes.index')->with('success', 'Uğurla yeniləndi');
    }

}
