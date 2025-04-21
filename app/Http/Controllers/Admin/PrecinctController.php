<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Precinct;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrecinctController extends Controller
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
        $data = Precinct::with('sheher')->orderBy('city_id','asc')->paginate(50);
        return view('backend.pages.precincts.index', compact('data'));
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
        $data = City::all();
        return view('backend.pages.precincts.create', compact('data'));
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
            'place_name' => 'required',
            'place_address' => 'required',
            'city_id' => 'required',
          //  'first_person' => 'required',
            //'first_person_number' => 'required'
        ]);
        $data = new Precinct();
        $data->city_id = $request->city_id;
        $data->place_name = $request->place_name;
        $data->place_address = $request->place_address;
        $data->first_person = $request->first_person;
        $data->first_person_number = $request->first_person_number;
        $data->second_person = $request->second_person ?? null;
        $data->second_person_number = $request->second_person_number ?? null;
        $data->save();
        return redirect()->route('backend.precincts.index')->with('success', 'Uğurla əlavə olundu');
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
        $data = City::all();
        $precinct= Precinct::findOrFail($id);
        return view('backend.pages.precincts.edit', compact('data','precinct'));
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
        $data = Precinct::findOrFail($id);
        $data->city_id = $request->city_id;
        $data->place_name = $request->place_name;
        $data->place_address = $request->place_address;
        $data->first_person = $request->first_person ?? null;
        $data->first_person_number = $request->first_person_number ?? null;
        $data->second_person = $request->second_person ?? null;
        $data->second_person_number = $request->second_person_number ?? null;
        $data->save();
        return redirect()->route('backend.precincts.index')->with('success', 'Uğurla yeniləndi');
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
}
