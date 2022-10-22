<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BaseResponse extends Controller
{
    public static function ResWithStatus($msg = '', $status = 200, ...$data)
    {
        return response()->json([
            "status" => $status,
            "msg" => $msg,
            // "data" => $data
        ]);
    }
}
