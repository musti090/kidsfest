<?php

namespace App\Http\Controllers\Admin\Test\Collective;

use App\Http\Controllers\Controller;
use App\Models\CollectiveDirector;
use App\Models\Nomination;
use App\Models\PersonalUserCardInformation;
use App\Models\Precinct;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CollectiveChangeController extends Controller
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
            $nominations = Cache::get('collectiveNominations');
            $cities = Cache::get('AllCity');
            $regions = Cache::get('MNRegion');

            $data = DB::table('collective_directors')
                ->leftJoin('collectives', 'collectives.id', '=', 'collective_directors.collective_id')
                ->where('collective_directors.date', '=', null)
                ->orderBy('date')
            //    ->where('precinct_id' , 51)
/*                ->orWhere('precinct_id' , 52)
                ->orWhere('precinct_id' , 53)
                ->orWhere('precinct_id' , 54)*/
                ->orderBy('time');
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
            /*        if ($request->get("test")) {
                        $data->where("collectives.test", $request->get("test"));
                    }*/
            $count = $data->count();
            $data = $data->paginate(50)->appends($request->query());

            return view('backend.pages.test.all-collective-users', compact('data', 'nominations', 'cities', 'regions', 'count'));

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
        $data = CollectiveDirector::where('collective_id',$id)->first();
        return view('backend.pages.test.collective-edit', compact('precincts', 'data'));

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


        $data = CollectiveDirector::where('id', $id)->first();
/*        if($data->date!= null || $data->time!= null || $data->precinct_id != null) {
            return redirect()->back();
        }*/
        $data->precinct_id = $request->precinct_id;
        $data->date = $request->date;
        $data->time = $request->time;
        $data->save();
        return redirect()->route('backend.collective-changes.index')->with('success', 'Uğurla yeniləndi');
    }
}
