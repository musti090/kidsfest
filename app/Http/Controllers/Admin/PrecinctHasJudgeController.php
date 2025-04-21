<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Judge;
use App\Models\JudgesList;
use App\Models\Nomination;
use App\Models\Precinct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrecinctHasJudgeController extends Controller
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
        $data = Judge::orderBy('precinct_id')->orderBy('nomination_id')->paginate(700);
        $precincts = Precinct::orderBy('place_name', 'asc')->get();
        return view('backend.pages.precinctHasJudges.index', compact('data', 'precincts'));
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
        $precincts = Precinct::orderBy('place_name', 'asc')->get();
        $nominations = Nomination::orderBy('name', 'asc')->get();
        $judges = JudgesList::orderBy('name', 'asc')->get();
        return view('backend.pages.precinctHasJudges.create', compact('precincts', 'nominations', 'judges'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
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
            'judge_id' => 'required'
        ]);
        if (count($request->judge_id) != 3) {
            return redirect()->back();
        }

        foreach ($request->judge_id as $judge_id) {
            $data = new Judge();
            $data->precinct_id = $request->precinct_id;
            $data->nomination_id = $request->nomination_id;
            $data->judge_id = $judge_id;
            $data->save();
        }

        return redirect()->route('backend.precincts-has-judges.index')->with('success', 'Uğurla əlavə olundu');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (is_null($this->user) || !$this->user->can('delete users')) {
            abort(403, 'İcazəniz yoxdur !!!');
        }
        $precincts = Precinct::orderBy('place_name', 'asc')->get();
        $nominations = Nomination::orderBy('name', 'asc')->get();
        $judges = JudgesList::orderBy('name', 'asc')->get();
        $data = Judge::findOrFail($id);
        return view('backend.pages.precinctHasJudges.edit', compact('precincts', 'judges', 'nominations', 'data'));

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
        $data = Judge::findOrFail($id);
        $data->precinct_id = $request->precinct_id;
        $data->nomination_id = $request->nomination_id;
        $data->judge_id = $request->judge_id;
        $data->save();
        return redirect()->route('backend.precincts-has-judges.index')->with('success', 'Uğurla yeniləndi');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
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
        if ($request->precinct_id == null) {
            return redirect()->back();
        }

        $data = Judge::where('precinct_id', $request->precinct_id)->paginate(50);
        $data->appends(['precinct_id' => $request->precinct_id]);
        $precincts = Precinct::orderBy('place_name', 'asc')->get();
        return view('backend.pages.precinctHasJudges.index', compact('data', 'precincts'));
    }
}
