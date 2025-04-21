<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CollectiveDirector;
use App\Models\Nomination;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

/**
 * Class WinnerUserController
 *
 * @package App\Http\Controllers
 * @quthor Mustafa Amirbayov <amirbayovmustafa@gmail.com>
 */
class WinnerUserController extends Controller
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

    public function personalUserWinners()
    {
        if ($this->user->getRoleNames()[0] == 'superadmin' || $this->user->getRoleNames()[0] == 'developer' || $this->user->getRoleNames()[0] == 'content manager') {

            return view('backend.pages.winners.personalUserWinners');
        }
        return redirect()->route('backend.dashboard.index');
    }

    public function collectiveUserWinners()
    {
        if ($this->user->getRoleNames()[0] == 'superadmin' || $this->user->getRoleNames()[0] == 'developer' || $this->user->getRoleNames()[0] == 'content manager') {
          return  $data1 = CollectiveDirector::whereHas('collective_information', function ($q) {
                $q->where('collective_nomination_id', '=', 21);
            })->where('is_final_pass','=',1)->where('age_category','6-9')->orderByDesc('third_step_score')->take(3)->get();

            $nominations = Nomination::where('type',2)->get();
            $data = [];
          /*  $data1 = [];
            $data2 = [];
            $data3 = [];*/
            foreach ($nominations as $n) {
                $nomination_id = $n->id;
                $data1 = CollectiveDirector::whereHas('collective_information', function ($q) use ($nomination_id) {
                    $q->where('collective_nomination_id', '=', $nomination_id);
                })->where('is_final_pass','=',1)->where('age_category','6-9')->orderByDesc('third_step_score')->take(3)->get();
                $data2 = CollectiveDirector::whereHas('collective_information', function ($q) use ($nomination_id) {
                    $q->where('collective_nomination_id', '=', $nomination_id);
                })->where('is_final_pass','=',1)->where('age_category','10-13')->orderByDesc('third_step_score')->take(3)->get();
                $data3 = CollectiveDirector::whereHas('collective_information', function ($q) use ($nomination_id) {
                    $q->where('collective_nomination_id', '=', $nomination_id);
                })->where('is_final_pass','=',1)->where('age_category','14-17')->orderByDesc('third_step_score')->take(3)->get();

            }


        return $data1;
          return 55;
            return view('backend.pages.winners.collectiveUserWinners');
        }
        return redirect()->route('backend.dashboard.index');
    }


}
