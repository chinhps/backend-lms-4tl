<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClassesController extends Controller
{
    public function list()
    {
        $data = DB::table('classes')->get();
        return response()->json($data);
    }
}
