<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JudgesList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Imports\JudgesListImport;
use Excel;

class JudgesListController extends Controller
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
        $data = JudgesList::orderBy('name')->paginate(100);
        return view('backend.pages.judges-list.index',compact('data'));
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
        return view('backend.pages.judges-list.create');
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
            'name' => 'required'
        ],[
            'name.required' => "Münsifin adı,soyadı mütləqdir"
        ]);

        $data = new JudgesList();
        $data->name = $request->name;
        $data->save();
        return redirect()->route('backend.judges-list.index')->with('success', 'Uğurla əlavə olundu');
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
        $data = JudgesList::findOrFail($id);
        return view('backend.pages.judges-list.edit',compact('data'));

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
        $data = JudgesList::findOrFail($id);
        $data->name = $request->name;
        $data->save();
        return redirect()->route('backend.judges-list.index')->with('success', 'Uğurla yeniləndi');
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
        $search = $request->name;
        $data = JudgesList::select("id", "name")
            ->where('name', 'LIKE', "%".$search."%")
            ->paginate(50);
        return view('backend.pages.judges-list.index',compact('data'));
    }
    public function excelStore(Request $request){
        if (is_null($this->user) || !$this->user->can('delete users')) {
            abort(403, 'İcazəniz yoxdur !!!');

        }
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);
        Excel::import(new JudgesListImport,$request->file);
        return back()->with('success','Məlumatlar import edildi');
    }
}
