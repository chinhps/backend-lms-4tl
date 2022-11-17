<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MajorController extends Controller
{
    public function list()
    {
        $data = DB::table('majors')->where('status', 1)->get();
        return response()->json($data);
    }
}
