<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClassesController extends Controller
{
    public function list()
    {
        $data = DB::table('classes')->orderBy('id', 'asc')->paginate(10);
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
    public function new(Request $request)
    {
        try {
            $data = DB::table('classes')->insert([
                'class_name' => $request->class_name,
            ]);
            return response()->json(["msg" => "Thêm thành công!"]);
        } catch (Exception $e) {
            return response()->json($e, 500);
        }
    }
    

    public function getOne(Request $request)
    {
        $data = DB::table('classes')->where('id', $request->id)->first();
        return response()->json($data);
    }

    public function put(Request $request)
    {
        try {
            DB::table('classes')->where('id', $request->id)->update([
                'class_name' => $request->class_name
            ]);
            return response()->json(["msg" => "Sửa thành công id $request->id!"]);
        } catch (Exception $e) {
            return response()->json($e, 500);
        }
    }
    public function listFull()
    {
        $data = DB::table('classes')->get();
        return response()->json($data);
    }
}
