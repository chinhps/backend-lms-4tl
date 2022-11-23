<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClassesController extends Controller
{
    public function list()
    {
        $data = DB::table('classes')->orderBy('id', 'desc')->get();
        return response()->json($data);
    }
    public function delete(Request $request)
    {
        try {
            DB::table('classes')->where('id', $request->id)->delete();
            return response()->json(["msg" => "Xóa thành công id $request->id!"]);
        } catch (Exception $e) {
            return response()->json($e, 500);
        }
    }
}
