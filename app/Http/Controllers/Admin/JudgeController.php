<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CollectiveDirector;
use App\Models\JudgesList;
use App\Models\Nomination;
use App\Models\PersonalUser;
use App\Models\PersonalUserCardInformation;
use App\Models\Precinct;
use App\Models\JudgesFile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class JudgeController extends Controller
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

    public function judges()
    {
        $user_precinct = $this->user->user_precinct->id;
        $nominations = Nomination::whereHas('nomination_has_precinct', function ($q) use ($user_precinct) {
            $q->where('precinct_id', '=', $user_precinct);
        })->get();
        return view('backend.pages.judges.index', compact('nominations'));
    }

    public function getJudges($id)
    {
        $user_precinct = $this->user->user_precinct->id;
        $judges =  DB::table('judges')->where('precinct_id', $user_precinct)->where('nomination_id', $id)->get();
        $mas = [];
            foreach ($judges as $k => $j){
               $mas[] = JudgesList::find($j->judge_id);
            }
      echo json_encode($mas);
    }

    public function getJudgeUsers(Request $request)
    {

        $request->validate([
            'nomination_id' => 'required',
            'judge_id' => 'required',
            'date_time' => 'required'
        ]);
        $x = explode("T", $request->date_time);
        $date = $x[0];
        $time = $x[1];
        $nomination_id = $request->nomination_id;
        $user_precinct = $this->user->user_precinct->id;
        if($nomination_id < 20){
            $data = PersonalUserCardInformation::with('personal_user_form_information', 'personal_user_parent_information')->whereHas('personal_user_form_information', function ($q) use ($nomination_id,$user_precinct,$date,$time) {
                $q->where('nomination_id', '=', $nomination_id)->where('precinct_id', $user_precinct)->where('date', $date)->where('time', $time);
            })->get();
        }
        else{
            $data = CollectiveDirector::with('collective_information')->whereHas('collective_information', function ($q) use ($nomination_id) {
                $q->where('collective_nomination_id', '=', $nomination_id);
            })->where('date', $date)->where('time', $time)->where('precinct_id', $user_precinct)->get();
        }



      /*  $judge = Judge::where('id', $request->judge_id)->firstOrFail();
        $judge_full_name = $judge->full_name;*/
        $judge = JudgesList::findOrFail($request->judge_id);
        $judge_full_name = $judge->name;
        $nomination = Nomination::with('nomination_has_criterion')->where('id', $nomination_id)->firstOrFail();
        $precinct = Precinct::where('id', $user_precinct)->firstOrFail();
        $precinct_name = $precinct->place_name;
        return view('backend.pages.judges.usersViaJudges', compact('date', 'time', 'precinct_name', 'nomination', 'data', 'judge', 'judge_full_name'));
    }

    public function judgesFiles()
    {
        $user_precinct = $this->user->user_precinct->id;
       // $nominations = Nomination::select('id', 'name')->where('type', 1)->get();
        $nominations = Nomination::whereHas('nomination_has_precinct', function ($q) use ($user_precinct) {
            $q->where('precinct_id', '=', $user_precinct);
        })->get();
        return view('backend.pages.judges.indexFiles', compact('nominations'));
    }

    public function storeJudgeFiles(Request $request)
    {
        $request->validate([
            'nomination_id' => 'required',
            'judge_id' => 'required',
            'date_time' => 'required',
            'file1' => 'required'

        ]);
        $x = explode("T", $request->date_time);
        $date = $x[0];
        $time = $x[1];
        $judgefiles = new JudgesFile();
        $judgefiles->nomination_id = $request->nomination_id;
        $judgefiles->judge_id = $request->judge_id;
        $judgefiles->precinct_id = $this->user->user_precinct->id;
        $judgefiles->date = $date;
        $judgefiles->time = $time;
        if ($request->hasFile('file1')) {
            $file = $request->file('file1');
            $fileName = Str::random(15) . time() .Str::random(15). "." . $file->getClientOriginalExtension();
            $path = 'uploads/judges/files/' . $request->judge_id ."/". $this->user->user_precinct->id."/" . $fileName;
            $databasePath = 'storage/' . $path;
            Storage::disk('public')->put($path, File::get($file));
            $judgefiles->file1 = $databasePath;
        }
        if ($request->hasFile('file2')) {
            $file = $request->file('file2');
            $fileName = Str::random(15) . time() .Str::random(15). "." . $file->getClientOriginalExtension();
            $path = 'uploads/judges/files/' . $request->judge_id ."/". $this->user->user_precinct->id."/" . $fileName;
            $databasePath = 'storage/' . $path;
            Storage::disk('public')->put($path, File::get($file));
            $judgefiles->file2 = $databasePath;
        }
        if ($request->hasFile('file3')) {
            $file = $request->file('file3');
            $fileName = Str::random(15) . time() .Str::random(15). "." . $file->getClientOriginalExtension();
            $path = 'uploads/judges/files/' . $request->judge_id ."/". $this->user->user_precinct->id."/" . $fileName;
            $databasePath = 'storage/' . $path;
            Storage::disk('public')->put($path, File::get($file));
            $judgefiles->file3 = $databasePath;
        }
        if ($request->hasFile('file4')) {
            $file = $request->file('file4');
            $fileName = Str::random(15) . time() .Str::random(15). "." . $file->getClientOriginalExtension();
            $path = 'uploads/judges/files/' . $request->judge_id ."/". $this->user->user_precinct->id."/" . $fileName;
            $databasePath = 'storage/' . $path;
            Storage::disk('public')->put($path, File::get($file));
            $judgefiles->file4 = $databasePath;
        }
        $judgefiles->save();
        return redirect()->route('backend.judges.files')->with('success', 'Uğurla əlavə olundu');


    }

    public function seeJudgesFiles(Request $request)
    {
        $precincts = Precinct::all();
        $nominations = Nomination::all();
        if ($this->user->getRoleNames()[0] == 'superadmin' || $this->user->getRoleNames()[0] == 'developer' || $this->user->getRoleNames()[0] == 'content manager') {
            if($request->has('nomination_id_group')){
                $data = JudgesFile::where('nomination_id',$request->nomination_id_group)->where('precinct_id',$request->precinct_id_group)->paginate(200);
            }
            else{
                $data = JudgesFile::orderByDesc('created_at')->paginate(200);
            }
        } else {
            $user_precinct = $this->user->user_precinct->id;
            $data = JudgesFile::where('precinct_id',$user_precinct)->orderByDesc('created_at')->paginate(200);
        }
        return view('backend.pages.judges.seeFiles', compact('data','precincts','nominations'));
    }

    public function seeJudgesFilesDetail($id)
    {
        if ($this->user->getRoleNames()[0] == 'superadmin' || $this->user->getRoleNames()[0] == 'developer' || $this->user->getRoleNames()[0] == 'content manager') {
            $data = JudgesFile::where('id',$id)->firstOrFail();
        } else {
            $user_precinct = $this->user->user_precinct->id;
            $data = JudgesFile::where('id',$id)->where('precinct_id',$user_precinct)->firstOrFail();
        }
        return view('backend.pages.judges.seeFilesDetail',compact('data'));
    }

    public function seeJudgesFilesDetailStore(Request $request,$id)
    {

        $judgefiles = JudgesFile::find($id);
        if ($request->hasFile('file1')) {
            $file = $request->file('file1');
            $fileName = Str::random(15) . time() .Str::random(15). "." . $file->getClientOriginalExtension();
            $path = 'uploads/judges/files/' . $request->judge_id ."/". $this->user->user_precinct->id."/" . $fileName;
            $databasePath = 'storage/' . $path;
            Storage::disk('public')->put($path, File::get($file));
            $judgefiles->file1 = $databasePath;
        }
        if ($request->hasFile('file2')) {
            $file = $request->file('file2');
            $fileName = Str::random(15) . time() .Str::random(15). "." . $file->getClientOriginalExtension();
            $path = 'uploads/judges/files/' . $request->judge_id ."/". $this->user->user_precinct->id."/" . $fileName;
            $databasePath = 'storage/' . $path;
            Storage::disk('public')->put($path, File::get($file));
            $judgefiles->file2 = $databasePath;
        }
        if ($request->hasFile('file3')) {
            $file = $request->file('file3');
            $fileName = Str::random(15) . time() .Str::random(15). "." . $file->getClientOriginalExtension();
            $path = 'uploads/judges/files/' . $request->judge_id ."/". $this->user->user_precinct->id."/" . $fileName;
            $databasePath = 'storage/' . $path;
            Storage::disk('public')->put($path, File::get($file));
            $judgefiles->file3 = $databasePath;
        }
        if ($request->hasFile('file4')) {
            $file = $request->file('file4');
            $fileName = Str::random(15) . time() .Str::random(15). "." . $file->getClientOriginalExtension();
            $path = 'uploads/judges/files/' . $request->judge_id ."/". $this->user->user_precinct->id."/" . $fileName;
            $databasePath = 'storage/' . $path;
            Storage::disk('public')->put($path, File::get($file));
            $judgefiles->file4 = $databasePath;
        }
        $judgefiles->save();
        return redirect()->back()->with('success', 'Uğurla yeniləndi');
    }

    public function delete($id)
    {
        $judge = JudgesFile::find($id);
        if ($judge->delete()) {
            return redirect()->route('backend.see.judges.files')->with('success', 'Uğurla silindi');
        }
        return redirect()->route('backend.see.judges.files')->with('error', 'Silmək mümkün olmadı !!!');
    }


}
