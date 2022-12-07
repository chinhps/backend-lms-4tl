<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BaseResponse extends Controller
{
    public static function ResWithStatus($msg = '', $status = 200)
    {
        return response()->json([
            "status" => $status,
            "msg" => $msg,
        ], $status);
    }

    public static function point($msg = '', $point, $status = 200)
    {
        return response()->json([
            "status" => $status,
            "msg" => $msg,
            "point" => $point
        ], $status);
    }
}
