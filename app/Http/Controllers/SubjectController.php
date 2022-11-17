<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubjectController extends Controller
{
    public function new(Request $request)
    {
        try {
            $data = DB::table('subjects')->insert([
                'subject_id' => $request->subject_id,
                'major_id' => $request->major_id,
                'code' => $request->code,
                'name' => $request->name,
                'status' => $request->status
            ]);
            return response()->json(["msg" => "Thêm thành công!"]);
        } catch (Exception $e) {
            return response()->json($e, 500);
        }
    }

    public function getOne(Request $request)
    {
        $data = DB::table('subjects')->where('subject_id', $request->id)->first();
        return response()->json($data);
    }

    public function put(Request $request)
    {
        try {
            DB::table('subjects')->where('subject_id', $request->id)->update([
                'major_id' => $request->major_id,
                'code' => $request->code,
                'name' => $request->name,
                'status' => $request->status
            ]);
            return response()->json(["msg" => "Sửa thành công id $request->id!"]);
        } catch (Exception $e) {
            return response()->json($e, 500);
        }
    }

    public function delete(Request $request)
    {
        try {
            DB::table('courses')->where('subject_id', $request->id)->delete();
            return response()->json(["msg" => "Xóa thành công id $request->id!"]);
        } catch (Exception $e) {
            return response()->json($e, 500);
        }
    }
    public function list()
    {
        $data = DB::table('subjects')->get();
        return response()->json($data);
    }
}
