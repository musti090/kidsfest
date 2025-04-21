<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Collective;
use App\Models\CollectiveDirector;
use App\Models\MNRegion;
use App\Models\Nomination;
use App\Models\PersonalUser;
use App\Models\PersonalUserCardInformation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChangeInformation extends Controller
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

    public function personalUserGet($id,$mn_region_id,$nomination_id)
    {
        if (is_null($this->user) || !$this->user->can('delete users')) {
            abort(403, 'İcazəniz yoxdur !!!');
        }
        $user = PersonalUserCardInformation::with('personal_user_form_information')->where('id',$id)->firstOrFail();
        $regions = MNRegion::select('id','name')->get();
        $nominations = Nomination::select('id','name')->where('type',1)->get();
        return view('backend.pages.change.personal-user-edit',compact('nomination_id','user','mn_region_id','regions','nominations'));
    }
    public function secondSteppersonalUserGet($id,$mn_region_id,$nomination_id)
    {
        if (is_null($this->user) || !$this->user->can('delete users')) {
            abort(403, 'İcazəniz yoxdur !!!');
        }
        $user = PersonalUserCardInformation::with('personal_user_form_information')->where('id',$id)->firstOrFail();
        $regions = MNRegion::select('id','name')->get();
        $nominations = Nomination::select('id','name')->where('type',1)->get();
        return view('backend.pages.change.secondStep.personal-user-edit',compact('nomination_id','user','mn_region_id','regions','nominations'));
    }
    public function personalUserEdit($id,$mn_region_id,$nomination_id,Request $request)
    {
        if (is_null($this->user) || !$this->user->can('delete users')) {
            abort(403, 'İcazəniz yoxdur !!!');
        }
        $data = PersonalUserCardInformation::with('personal_user_form_information')->where('id',$id)->firstOrFail();
         if($request->has('nomination_id')){
             $data->personal_user_form_information->update([
                 'nomination_id' => $request->nomination_id
             ]);
         }
         else{
               if($data->mn_region_old_id == null){
                  $data->mn_region_old_id = $mn_region_id;
              }
              $data->save();
              $pd = PersonalUser::where('id',$data->personal_user_id)->firstOrFail();
              $pd->mn_region_id = $request->region_id;
              $pd->save();
         }

           return redirect()->route('backend.personal.users.information')->with('success', 'Uğurla  Yeniləndi');
    }

    public function personalUserEditDate(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('delete users')) {
            abort(403, 'İcazəniz yoxdur !!!');
        }

        $data = PersonalUserCardInformation::where('id',$request->personal_user_id)->firstOrFail();
        $data->date = $request->date;
        $data->time = $request->time;
        $data->save();

        return redirect()->back()->with('success', 'Uğurla  Yeniləndi');

    }
    public function personalUserEditDateSecond(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('delete users')) {
            abort(403, 'İcazəniz yoxdur !!!');
        }

        $data = PersonalUserCardInformation::where('id',$request->personal_user_id)->firstOrFail();
        $data->second_step_date = $request->date;
        $data->second_step_time = $request->time;
        $data->save();

        return redirect()->back()->with('success', 'Uğurla  Yeniləndi');

    }
    public function personalUserEditArtType(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('delete users')) {
            abort(403, 'İcazəniz yoxdur !!!');
        }
        $data = PersonalUserCardInformation::with('personal_user_form_information')->where('id',$request->user_id)->firstOrFail();
        $data->personal_user_form_information->update([
            'art_type' => $request->art_type
        ]);

        return redirect()->back()->with('success', 'Uğurla  Yeniləndi');

    }
    public function collectiveUserEditDate(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('delete users')) {
            abort(403, 'İcazəniz yoxdur !!!');
        }

        $data = CollectiveDirector::where('id',$request->personal_user_id)->firstOrFail();
        $data->date = $request->date;
        $data->time = $request->time;
        $data->save();

        return redirect()->back()->with('success', 'Uğurla  Yeniləndi');

    }

    public function collectiveUserGet($id,$mn_region_id,$nomination_id)
    {
        if (is_null($this->user) || !$this->user->can('delete users')) {
            abort(403, 'İcazəniz yoxdur !!!');
        }
        $user = CollectiveDirector::where('id',$id)->firstOrFail();
        $regions = MNRegion::select('id','name')->get();
        $nominations = Nomination::select('id','name')->where('type',2)->get();
        return view('backend.pages.change.collective-user-edit',compact('nomination_id','nominations','user','mn_region_id','regions'));
    }
    public function collectiveUserEdit($id,$mn_region_id,Request $request)
    {
        if (is_null($this->user) || !$this->user->can('delete users')) {
            abort(403, 'İcazəniz yoxdur !!!');
        }
        $data = CollectiveDirector::with('collective_information')->where('id',$id)->firstOrFail();
        if($request->has('nomination_id')){
            $data->collective_information->update([
                'collective_nomination_id' => $request->nomination_id
            ]);
        }
        else{
            if($data->mn_region_old_id == null){
                $data->collective_mn_region_old_id = $mn_region_id;
            }
            $data->save();
            $pd = Collective::where('id',$data->collective_id)->firstOrFail();
            $pd->collective_mn_region_id = $request->region_id;
            $pd->save();
        }
        return redirect()->route('backend.collective.users.information')->with('success', 'Uğurla  Yeniləndi');
    }
}
