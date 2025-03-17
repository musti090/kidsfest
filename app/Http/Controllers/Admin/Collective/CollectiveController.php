<?php

namespace App\Http\Controllers\Admin\Collective;

use App\Exports\CollectiveExport;
use App\Exports\PersonalExport;
use App\Http\Controllers\Controller;
use App\Models\Collective;
use App\Services\ExcelExportServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class CollectiveController extends Controller
{
    private ExcelExportServices $excelExportServices;

    public function __construct(ExcelExportServices $excelExportServices)
    {
        $this->excelExportServices = $excelExportServices;
    }
    public function index(Request $request)
    {
        try {
            $nominations = Cache::get('collectiveNominations');
            $cities = Cache::get('AllCity');
            $regions = Cache::get('MNRegion');

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
                $data->where('collective_directors.director_patronymic', 'like', '%' .  $request->get("director_patronymic") . '%');
            }
            if ($request->get("collective_created_date")) {
                $data->where("collectives.collective_created_date", $request->get("collective_created_date"));
            }
            if ($request->get("collective_name")) {
                $data->where('collectives.collective_name', 'like', '%' .  $request->get("collective_name") . '%');
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
            $count = $data->count();
            $data = $data->paginate(25)->appends($request->query());

            return view('backend.pages.firstStep.all-collective-users', compact('data','nominations','cities','regions','count'));

        } catch (\Exception $e) {

            return $e->getMessage();
        }

    }

    public function exportExcel(Request $request)
    {
        try {
            $data = $this->excelExportServices->getCollectiveData($request);
            return Excel::download(new CollectiveExport($data), 'Kollektiv.xlsx');

        } catch (\Exception $e) {

            return $e->getMessage();
        }
    }

    public function detail($id)
    {

/*

        $collective = Collective::all();

        foreach ($collective as $c) {
            echo "<b>".$c->id."</b><br>";

            $teenagers = DB::table('collective_teenagers')->where('collective_id',$c->id)->get();
            foreach ($teenagers as $t) {
                echo $t->id."<br>";
            }
           echo "<hr>";
        }


        exit;*/

        try {
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

            $awards = DB::table('collective_awards')->where('collective_id', $id)->get();

            return view('backend.pages.firstStep.collective-user-detail', compact('collective', 'teenagers', 'director', 'awards', 'teenagers_count'));

        } catch (\Exception $e) {

            return $e->getMessage();
        }

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
