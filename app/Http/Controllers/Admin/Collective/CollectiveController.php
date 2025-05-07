<?php

namespace App\Http\Controllers\Admin\Collective;

use App\Exports\CollectiveExport;
use App\Exports\PersonalExport;
use App\Http\Controllers\Controller;
use App\Models\Collective;
use App\Models\User;
use App\Services\ExcelExportServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class CollectiveController extends Controller
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
            $nominations = Cache::get('collectiveNominations');
            $cities = Cache::get('AllCity');
            $regions = Cache::get('MNRegion');
            $user_precinct = null;
            if ($this->user->getRoleNames()[0] == 'superadmin' || $this->user->getRoleNames()[0] == 'developer' || $this->user->getRoleNames()[0] == 'content manager') {
                $data = DB::table('collective_directors')
                    ->leftJoin('collectives', 'collectives.id', '=', 'collective_directors.collective_id')
                 //   ->where('collective_directors.date', null)
                    ->orderByDesc('score')
                    ->orderBy('date')
                    ->orderBy('time');
            } else {
                $user_precinct = $this->user->user_precinct->id;
                $data = DB::table('collective_directors')
                    ->leftJoin('collectives', 'collectives.id', '=', 'collective_directors.collective_id')
                    ->where('collective_directors.precinct_id', $user_precinct)
                    ->orderByDesc('score')
                    ->orderBy('date')
                    ->orderBy('time');
            }

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
            if ($request->get("age_category")) {
                $data->where("collectives.age_category", $request->get("age_category"));
            }
       /*     if ($request->get("date") == null) {
                $data->where("collective_directors.date", null);
            }*/
            /*        if ($request->get("test")) {
                        $data->where("collectives.test", $request->get("test"));
                    }*/
            $count = $data->count();
            $data = $data->paginate(25)->appends($request->query());

            return view('backend.pages.firstStep.all-collective-users', compact('data', 'nominations', 'cities', 'regions', 'count','nominations_data','regions_data','cities_data','user_precinct'));

        } catch (\Exception $e) {

            return $e->getMessage();
        }


    }

    public function exportExcel(Request $request)
    {
        try {
            if ($this->user->getRoleNames()[0] == 'superadmin' || $this->user->getRoleNames()[0] == 'developer' || $this->user->getRoleNames()[0] == 'content manager') {
                $user_precinct = null;
                $data = $this->excelExportServices->getCollectiveData($request, $user_precinct);
            } else {
                $user_precinct = $this->user->user_precinct->id;
                $data = $this->excelExportServices->getCollectiveData($request, $user_precinct);
            }

            return Excel::download(new CollectiveExport($data), 'Kollektiv.xlsx');

        } catch (\Exception $e) {

            return $e->getMessage();
        }
    }

    public function detail($id)
    {

       /* try {*/

            $awards = DB::table('collective_awards')->where('collective_id', $id)->get();

            if ($this->user->getRoleNames()[0] == 'superadmin' || $this->user->getRoleNames()[0] == 'developer' || $this->user->getRoleNames()[0] == 'content manager') {
                $collective = DB::table('collectives')
                    ->where('collectives.id', $id)
                    ->first();

                $teenagers = DB::table('collective_teenagers')
                    ->where('collective_id', $id);

                $teenagers = $teenagers->get();
                $teenagers_count = $teenagers->count();

                $director = DB::table('collective_directors')
                    ->where('collective_id', $id)
                    ->first();

                return view('backend.pages.firstStep.collective-user-detail', compact('collective', 'teenagers', 'director', 'awards', 'teenagers_count'));

            } else {
                $user_precinct = $this->user->user_precinct->id;

                $director = DB::table('collective_directors')
                    ->where('collective_id', $id)
                    ->where('collective_directors.precinct_id', $user_precinct)
                    ->first();

                if (empty($director)) {
                    return redirect()->route('backend.collective.users.list');
                }

                $collective = DB::table('collectives')
                    ->where('collectives.id', $id)
                    ->first();

                $teenagers = DB::table('collective_teenagers')
                    ->where('collective_id', $id);

                $teenagers = $teenagers->get();
                $teenagers_count = $teenagers->count();

                $judges = DB::table('judges')->where('nomination_id', $collective->collective_nomination_id)->where('precinct_id', $user_precinct)->get();
                $criteria = DB::table('criterion_nominations')->where('nomination_id', $collective->collective_nomination_id)->get();

                return view('backend.pages.firstStep.collective-user-detail', compact('collective', 'teenagers', 'director', 'awards', 'teenagers_count','judges','criteria'));

            }



 /*       } catch (\Exception $e) {

            return $e->getMessage();
        }*/

    }
    /*   public function detail($id)
       {

                  $dateOfBirth = '2008-01-11';
                   $endDate = '2025-03-31';
                   $diff = date_diff(date_create($dateOfBirth), date_create($endDate))->format('%y');
                   if ($diff >= 18 || $diff <= 5) {
                       return response(['message' => 'Yaşınız uyğun deyil!'.$diff], 422);
                   }
                   else{
                       return response(['message' => 'Ela!'.$diff], 200);
                   }

           $collective = DB::table('collectives')
               ->where('collectives.id', $id)
               ->first();

           $teenagers = DB::table('collective_teenagers')
               ->where('collective_id', $id)
               ->get(); // Bütün yeniyetmələri massiv kimi çəkirik

           $director = DB::table('collective_directors')
               ->where('collective_id', $id)
               ->first(); // Tək bir direktor gəlməlidir


               $collective->teenagers = $teenagers;
                $collective->director = $director;


           return view('backend.pages.firstStep.personal-user-detail', compact('collective'));
       }*/
}
