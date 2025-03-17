<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EducationSchool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class EducationSchoolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       // Cache::forget('EducationSchool');
        $data = Cache::rememberForever('EducationSchool', function () {
            return EducationSchool::query()
                ->select('id', 'school_type')
                ->get();
        });
        return response($data, 200);
    }

    public function getSchools(Request $request)
    {
            $data = DB::table('education_school_new_names')->select(
                'id',
                'name'
            )
                ->where('school_id', '=', $request->school_type)
                ->get();

        return response($data, 200);
    }
}
