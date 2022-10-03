<?php

namespace App\Http\Controllers;

use App\Models\Labs;
use Illuminate\Http\Request;

class LabController extends Controller
{
    public function list()
    {
        $data = Labs::get();
        return response()->json([
            "data" => $data
        ]);
    }
}
