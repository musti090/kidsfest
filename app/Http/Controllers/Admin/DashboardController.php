<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CollectiveDirector;
use App\Models\EducationSchoolName;
use App\Models\MNRegion;
use App\Models\AllCity;
use App\Models\EconomicDistrict;
use App\Models\PersonalUserCardInformation;
use App\Models\SpecialArtSchoolRegion;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\PersonalUser;
use App\Models\CollectiveTeenager;
use App\Models\Nomination;
use App\Models\Collective;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    /**
     * @var user
     */
    public $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if ($this->user->getRoleNames()[0] == 'superadmin' || $this->user->getRoleNames()[0] == 'developer') {
            $get_personal_users = DB::table('personal_users')->select('id', 'gender');
            $personal_users = $get_personal_users->count();
            $get_personal_users = $get_personal_users->get();
            $collectives = DB::table('collectives')->count();
            $get_collective_users = DB::table('collective_teenagers')->select('id', 'gender');
            $collective_users = $get_collective_users->count();
            $get_collective_users = $get_collective_users->get();
            $all = $personal_users + $collective_users;
            $personalMales = 0;
            $collectiveMales = 0;
            foreach ($get_personal_users as $pu) {
                if ($pu->gender == 'MALE') {
                    $personalMales = $personalMales + 1;
                }
            }
            foreach ($get_collective_users as $cu) {
                if ($cu->gender == 'MALE') {
                    $collectiveMales = $collectiveMales + 1;
                }
            }
            $personalFemales = $personal_users - $personalMales;
            $collectiveFemales = $collective_users - $collectiveMales;
        }
        return view('backend.pages.statistics.index', compact('personal_users', 'collectives', 'collective_users', 'all', 'personalMales', 'personalFemales', 'collectiveMales', 'collectiveFemales'));
    }

    public function cityStatistics()
    {
        $cities = Cache::get('AllCity');
        $city_count = [];
        foreach ($cities as $c):
            $city_count[] = [
                DB::table('personal_user_card_information')->where('all_city_id', $c->id)->count(),
                DB::table('collectives')->where('collective_city_id', $c->id)->count(),
                $c->city_name
            ];
        endforeach;
        return view('backend.pages.statistics.cityStatistics', compact('city_count'));

    }

    public function districtStatistics()
    {
        $district_count = [];
        $districts = Cache::get('MNRegion');

        foreach ($districts as $d):

            $district_count[] = [
                DB::table('personal_users')->where('mn_region_id', $d->id)->count(),
                DB::table('collectives')->where('collective_mn_region_id', $d->id)->count(),
                $d->name
            ];
        endforeach;
        return view('backend.pages.statistics.districtStatistics', compact('district_count'));

    }

    public function nominationStatistics()
    {

        $nomCount = [];
        $colnomCount = [];
        $personalNominations = Cache::get('personalNominations');
        foreach ($personalNominations as $nom):
            $nomCount[] = [
                DB::table('personal_users')->where('nomination_id', $nom->id)->count(),
                $nom->name
            ];
        endforeach;
        $collectiveNominations = Cache::get('collectiveNominations');
        foreach ($collectiveNominations as $nom):
            $colnomCount[] = [
                DB::table('collectives')->where('collective_nomination_id', $nom->id)->count(),
                $nom->name
            ];
        endforeach;

        return view('backend.pages.statistics.nominationStatistics', compact('colnomCount', 'nomCount'));
    }


    public function nominationDistrictStatistics()
    {
        // $regions = DB::table('m_n_regions')->paginate(10);
        $regions = Cache::get('MNRegion');
        $personalNominations = Cache::get('personalNominations');
        $collectiveNominations = Cache::get('collectiveNominations');


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


        return view('backend.pages.statistics.nominationDistrictStatistics', compact('regions', 'personalNominations', 'collectiveNominations'));


    }
}
