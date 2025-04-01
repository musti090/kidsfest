<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CedvelController extends Controller
{
    public function index()
    {
       $all = DB::table('criteria')->get();

       foreach ($all as $key => $item) {
           DB::table('criteria')->where('id',$item->id)->update([
               'id' => $key + 1,
               'created_at' => now(),
               'updated_at' => now()
           ]);
       }
    }
}
