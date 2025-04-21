<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CollectiveDirector;
use App\Models\PersonalUser;
use App\Models\PersonalUserCardInformation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class EvaluateController extends Controller
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

    public function evaluatePersonal(Request $request)
    {

        $user_data = PersonalUserCardInformation::where('personal_user_id', $request->id)->firstOrFail();
        $user_precinct = $this->user->user_precinct->id;
        if ($user_precinct == $user_data->precinct_id) {
            if ($request->has('gelmeyib')) {
                $user_data->is_absent = 1;
                $user_data->save();
                return redirect()->back()->with('success', 'Məlumat təsdiqləndi !');
            }
            $request->validate([
                'n11' => 'required|numeric|min:1|max:10',
                'n12' => 'required|numeric|min:1|max:10',
                'n13' => 'required|numeric|min:1|max:10',
                'n14' => 'required|numeric|min:1|max:10',
                'n15' => 'required|numeric|min:1|max:10',
                'n21' => 'required|numeric|min:1|max:10',
                'n22' => 'required|numeric|min:1|max:10',
                'n23' => 'required|numeric|min:1|max:10',
                'n24' => 'required|numeric|min:1|max:10',
                'n25' => 'required|numeric|min:1|max:10',
                'n31' => 'required|numeric|min:1|max:10',
                'n32' => 'required|numeric|min:1|max:10',
                'n33' => 'required|numeric|min:1|max:10',
                'n34' => 'required|numeric|min:1|max:10',
                'n35' => 'required|numeric|min:1|max:10'
            ]);


            if ($request->toplam > 50 || $request->toplam < 5 || $request->toplam == 0) {
                return redirect()->back()->with('error', 'Hesablama  düzgün aparılmayıb !');
            }
            $nomination = $request->nomination_id;
            $type = 1; // Ferdi

            for ($j = 1; $j <= 3; $j++) {
                for ($i = 1; $i <= 5; $i++) {
                    DB::table('evaluations')->insert([
                        'score' => $request->{'n' . $j . $i},
                        'nomination_id' => $nomination,
                        'precinct_id' => $user_data->precinct_id,
                        'personal_id' => $user_data->personal_user_id,
                        'type' => $type,
                        'judge_id' => $request->{'judge' . $j . $i},
                        'criterion_id' => $request->{'criteria' . $j . $i},
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }

            $user_data->score = $request->toplam;
            $user_data->note = $request->note;
            $user_data->save();
            return redirect()->back()->with('success', 'İştirakçının balı əlavə olundu!');
        }

        return redirect()->back()->with('error', 'Məlumatlar düzgün daxil edilməyib !');


    }
    public function evaluateCollective(Request $request)
    {

        $user_data = CollectiveDirector::where('collective_id', $request->id)->firstOrFail();
        $user_precinct = $this->user->user_precinct->id;
        if ($user_precinct == $user_data->precinct_id) {
            if ($request->has('gelmeyib')) {
                $user_data->is_absent = 1;
                $user_data->save();
                return redirect()->back()->with('success', 'Məlumat təsdiqləndi !');
            }
            $request->validate([
                'n11' => 'required|numeric|min:1|max:10',
                'n12' => 'required|numeric|min:1|max:10',
                'n13' => 'required|numeric|min:1|max:10',
                'n14' => 'required|numeric|min:1|max:10',
                'n15' => 'required|numeric|min:1|max:10',
                'n21' => 'required|numeric|min:1|max:10',
                'n22' => 'required|numeric|min:1|max:10',
                'n23' => 'required|numeric|min:1|max:10',
                'n24' => 'required|numeric|min:1|max:10',
                'n25' => 'required|numeric|min:1|max:10',
                'n31' => 'required|numeric|min:1|max:10',
                'n32' => 'required|numeric|min:1|max:10',
                'n33' => 'required|numeric|min:1|max:10',
                'n34' => 'required|numeric|min:1|max:10',
                'n35' => 'required|numeric|min:1|max:10'
            ]);


            if ($request->toplam > 50 || $request->toplam < 5 || $request->toplam == 0) {
                return redirect()->back()->with('error', 'Hesablama  düzgün aparılmayıb !');
            }
            $nomination = $request->nomination_id;
            $type = 2; // Kollektiv

            for ($j = 1; $j <= 3; $j++) {
                for ($i = 1; $i <= 5; $i++) {
                    DB::table('evaluations')->insert([
                        'score' => $request->{'n' . $j . $i},
                        'nomination_id' => $nomination,
                        'precinct_id' => $user_data->precinct_id,
                        'collective_id' => $user_data->collective_id,
                        'type' => $type,
                        'judge_id' => $request->{'judge' . $j . $i},
                        'criterion_id' => $request->{'criteria' . $j . $i},
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }

            $user_data->score = $request->toplam;
            $user_data->note = $request->note;
            $user_data->save();
            return redirect()->back()->with('success', 'Kollektivin balı əlavə olundu!');
        }

        return redirect()->back()->with('error', 'Məlumatlar düzgün daxil edilməyib !');


    }
    public function judgesEvaluate(Request $request)
    {
        try {
            $precincts = DB::table('precincts')->pluck('place_name', 'id');
            $judges = DB::table('judges_list')->orderBy('name')->pluck('name', 'id');
            $nominations = DB::table('nominations')->pluck('name', 'id');
            $criteria = DB::table('criteria')->pluck('name', 'id');

            if ($this->user->getRoleNames()[0] == 'superadmin' || $this->user->getRoleNames()[0] == 'developer' || $this->user->getRoleNames()[0] == 'content manager') {
                $data = DB::table('evaluations');
            } else {
                $user_precinct = $this->user->user_precinct->id;
                $data = DB::table('evaluations')
                    ->where('precinct_id', $user_precinct);
            }

            if ($request->get("precinct_id")) {
                $data->where("precinct_id", $request->get("precinct_id"));
            }
            if ($request->get("nomination_id")) {
                $data->where("nomination_id", $request->get("nomination_id"));
            }
            if ($request->get("criterion_id")) {
                $data->where("criterion_id", $request->get("criterion_id"));
            }
            if ($request->get("judge_id")) {
                $data->where("judge_id", $request->get("judge_id"));
            }
            if ($request->get("type")) {
                $data->where("type", $request->get("type"));
            }
            if ($request->get("score")) {
                $data->where("score", $request->get("score"));
            }

            $count = $data->count();
            $data = $data->paginate(25)->appends($request->query());

            return view('backend.pages.firstStep.judges-evaluate', compact('data', 'count', 'nominations', 'precincts', 'judges','criteria'));

        } catch (\Exception $e) {

            return $e->getMessage();
        }
    }
}
