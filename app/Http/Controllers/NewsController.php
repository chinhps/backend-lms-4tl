<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NewsController extends Controller
{
    public function getAll(Request $request)
    {
        $limit = $request->limit ?? 3;
        $data = DB::table('news')->take($limit)->get();
        return response()->json($data);
    }
}
