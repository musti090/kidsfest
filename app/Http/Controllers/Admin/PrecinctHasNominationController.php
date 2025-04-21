<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Nomination;
use App\Models\Precinct;
use App\Models\PrecinctsHasNomination;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PrecinctHasNominationController extends Controller
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
        if (is_null($this->user) || !$this->user->can('delete users')) {
            abort(403, 'İcazəniz yoxdur !!!');
        }
        $data = PrecinctsHasNomination::orderByDesc('city_id')->paginate(50);
        $precincts = Precinct::orderBy('place_name','asc')->get();
        return view('backend.pages.precinctHasNominations.index',compact('data','precincts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (is_null($this->user) || !$this->user->can('delete users')) {
            abort(403, 'İcazəniz yoxdur !!!');
        }
        $precincts =  Precinct::orderBy('place_name','asc')->get();
        $nominations = Nomination::all();
        return view('backend.pages.precinctHasNominations.create',compact('precincts','nominations'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('delete users')) {
            abort(403, 'İcazəniz yoxdur !!!');
        }
        $request->validate([
            'precinct_id' => 'required',
            'nomination_id' => 'required',
            'start_date' => 'required'
        ]);

       $data = new PrecinctsHasNomination();
       $data->precinct_id = $request->precinct_id;
       $data->nomination_id = $request->nomination_id;
       $data->start_date = $request->start_date;
       $data->end_date = $request->end_date ?? null;
       $data->save();
       return redirect()->route('backend.precincts-has-nominations.index')->with('success', 'Uğurla əlavə olundu');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (is_null($this->user) || !$this->user->can('delete users')) {
            abort(403, 'İcazəniz yoxdur !!!');
        }
        $precincts =  Precinct::orderBy('place_name','asc')->get();
        $nominations = Nomination::all();
        $data = PrecinctsHasNomination::findOrFail($id);
        return view('backend.pages.precinctHasNominations.edit',compact('precincts','nominations','data'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (is_null($this->user) || !$this->user->can('delete users')) {
            abort(403, 'İcazəniz yoxdur !!!');

        }
        $data = PrecinctsHasNomination::findOrFail($id);
        $data->precinct_id = $request->precinct_id;
        $data->nomination_id = $request->nomination_id;
        $data->start_date = $request->start_date;
        $data->end_date = $request->end_date ?? null;
        $data->save();
        return redirect('/my-admin/precincts-has-nominations-search?precinct_id='.$request->precinct_id)->with('success', 'Uğurla yeniləndi');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function search(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('delete users')) {
            abort(403, 'İcazəniz yoxdur !!!');

        }
        if($request->precinct_id == null){
            return redirect()->back();
        }

            $data =  PrecinctsHasNomination::where('precinct_id',$request->precinct_id)->paginate(50);
        $data->appends(['precinct_id' => $request->precinct_id]);


        $precincts =  Precinct::orderBy('place_name','asc')->get();
        return view('backend.pages.precinctHasNominations.index',compact('data','precincts'));
    }
}
