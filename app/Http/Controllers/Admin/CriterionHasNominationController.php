<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Criterion;
use App\Models\CriterionNomination;
use App\Models\Judge;
use App\Models\JudgesList;
use App\Models\Nomination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CriterionHasNominationController extends Controller
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
        $data = CriterionNomination::paginate(50);
        $nominations = Nomination::orderBy('name','asc')->get();
        return view('backend.pages.criterion-has-nomination.index',compact('data','nominations'));
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
        $criterion =  Criterion::orderBy('name','asc')->get();
        $nominations = Nomination::orderBy('name','asc')->get();
        return view('backend.pages.criterion-has-nomination.create',compact('criterion','nominations'));
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
            'nomination_id' => 'required',
            'criterian_id' => 'required'
        ]);

        $data = new CriterionNomination();
        $data->nomination_id = $request->nomination_id;
        $data->criterion_id = $request->criterian_id;
        $data->save();
        return redirect()->route('backend.criterion-has-nomination.index')->with('success', 'Uğurla əlavə olundu');
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
        $criterion =  Criterion::orderBy('name','asc')->get();
        $nominations = Nomination::orderBy('name','asc')->get();
        $data = CriterionNomination::findOrFail($id);
        return view('backend.pages.criterion-has-nomination.edit',compact('criterion','nominations','data'));

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
        $data = CriterionNomination::findOrFail($id);
        $data->nomination_id = $request->nomination_id;
        $data->criterion_id = $request->criterian_id;
        $data->save();
        return redirect()->route('backend.criterion-has-nomination.index')->with('success', 'Uğurla yeniləndi');
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

        $data =  CriterionNomination::where('nomination_id',$request->nomination_id)->paginate(50);
        $data->appends(['nomination_id' => $request->nomination_id]);
        $nominations =  Nomination::orderBy('name','asc')->get();
        return view('backend.pages.criterion-has-nomination.index',compact('data','nominations'));
    }
}
