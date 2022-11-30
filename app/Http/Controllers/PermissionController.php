<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermissionController extends Controller
{
    public function list()
    {
        $data = DB::table('permissions')->orderBy('id', 'desc')->paginate(10);
        return response()->json($data);
    }

    public function delete(Request $request)
    {
        try {
            DB::table('permissions')->where('id', $request->id)->delete();
            return response()->json(["msg" => "Xóa thành công id $request->id!"]);
        } catch (Exception $e) {
            return response()->json($e, 500);
        }
    }
    public function new(Request $request)
    {
        try {
            $data = DB::table('permissions')->insert([
                'ps_code' => $request->ps_code,
                'ps_name' => $request->ps_name,
                'ps_group' => $request->ps_group,

            ]);
            return response()->json(["msg" => "Thêm thành công!"]);
        } catch (Exception $e) {
            return response()->json($e, 500);
        }
    }

    public function getOne(Request $request)
    {
        $data = DB::table('permissions')->where('id', $request->id)->first();
        return response()->json($data);
    }

    public function put(Request $request)
    {
        try {
            DB::table('permissions')->where('id', $request->id)->update([
                'ps_code' => $request->ps_code,
                'ps_name' => $request->ps_name,
                'ps_group' => $request->ps_group,
            ]);
            return response()->json(["msg" => "Sửa thành công id $request->id!"]);
        } catch (Exception $e) {
            return response()->json($e, 500);
        }
    }
}
