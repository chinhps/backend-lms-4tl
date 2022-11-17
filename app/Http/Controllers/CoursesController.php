<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CoursesController extends Controller
{
    public function list()
    {
        $data = DB::table('courses')->get();
        return response()->json($data);
    }
    public function new(Request $request)
    {
        try {
            $data = DB::table('courses')->insert([
                'subject_id' => $request->subject_id,
                'class_code' => $request->class_code,
                'name' => $request->name,
                'status' => $request->status
            ]);


            return response()->json(["msg" => "Thêm thành công!"]);
        } catch (Exception $e) {
            return response()->json($e, 500);
        }
    }
    public function getTeacher()
    {
        $data = DB::table('users')->where('role_id', 2)->get();
        return response()->json($data);
    }
    public function getById(Request $request)
    {
        $data = DB::table('courses')->where('id', $request->id)->first();
        return response()->json($data);
    }
    public function put(Request $request)
    {
        try {
            DB::table('courses')->where('id', $request->id)->update([
                'subject_id' => $request->subject_id,
                'class_code' => $request->class_code,
                'name' => $request->name,
                'status' => $request->status,
            ]);
            return response()->json(["msg" => "Sửa thành công id $request->id!"]);
        } catch (Exception $e) {
            return response()->json($e, 500);
        }
    }
    public function delete(Request $request)
    {
        try {
            DB::table('courses')->where('id', $request->id)->delete();
            return response()->json(["msg" => "Xóa thành công id $request->id!"]);
        } catch (Exception $e) {
            return response()->json($e, 500);
        }
    }
}
