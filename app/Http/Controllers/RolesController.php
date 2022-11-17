<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RolesController extends Controller
{
    public function list()
    {
        $data = DB::table('role')->get();
        return response()->json($data);
    }
}
