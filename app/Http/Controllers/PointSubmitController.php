<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PointSubmitController extends Controller
{
    public function list()
    {
        $data = DB::table('point_submit')->join('users','point_submit.user_id', '=', 'users.id')->join('courses', 'point_submit.course_id','=','courses.id')
        ->orderBy('id', 'desc')->selectRaw('point_submit.id, users.name as user_name, courses.class_code, courses.name as course_name, point_submit.content, point_submit.point, point_submit.status, point_submit.pointSubmitable_type')->paginate(10);
        return response()->json($data);
    }

    public function delete(Request $request)
    {
        try {
            DB::table('point_submit')->where('id', $request->id)->delete();
            return response()->json(["msg" => "Xóa thành công id $request->id!"]);
        } catch (Exception $e) {
            return response()->json($e, 500);
        }
    }
    public function new(Request $request)
    {
        try {
            $data = DB::table('point_submit')->insert([
                'name' => $request->name

            ]);
            return response()->json(["msg" => "Thêm thành công!"]);
        } catch (Exception $e) {
            return response()->json($e, 500);
        }
    }

    public function getOne(Request $request)
    {
        $data = DB::table('point_submit')->where('id', $request->id)->first();
        return response()->json($data);
    }

    public function put(Request $request)
    {
        try {
            DB::table('point_submit')->where('id', $request->id)->update([
                'name' => $request->name
            ]);
            return response()->json(["msg" => "Sửa thành công id $request->id!"]);
        } catch (Exception $e) {
            return response()->json($e, 500);
        }
    }
    public function listFull()
    {
        $data = DB::table('point_submit')->get();
        return response()->json($data);
    }
}
