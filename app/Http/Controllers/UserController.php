<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends BaseController
{
    public function getTeacher()
    {

        // $data = DB::table('users')->paginate(3);

        // DB::table('users')->insert([
        //     '23423' => '123'
        // ]);

        // DB::table('users')->where('id',4)->update([
        //     'name' => 'lam'
        // ]);
        // DB::table('users')->updateOrInsert([
        //     'id' => $request->id
        // ], [
        //     'name' => 'lam'
        // ]);
        // DB::table('users')->where('id',4)->delete();

        // $data = DB::table('users')->join('role', 'users.role_id', '=', 'role.id')->get();

        // return response()->json($data);
    }

    public function detail(Request $req)
    {
        // fwewe
    }

    public function new(Request $request)
    {
        try {
            $data = DB::table('subjects')->insert([
                'user_code' => $request->user_code,
                'password' => $request->password,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'name' => $request->name,
                'status' => $request->status,
                'role_id' => $request->role_id,
                'class_id' => $request->class_id,
            ]);
            return response()->json(["msg" => "Thêm thành công!"]);
        } catch (Exception $e) {
            return response()->json($e, 500);
        }
    }
    public function list()
    {
        $data = DB::table('users')->join('classes', 'users.class_id', '=', 'classes.id')->join('role', 'users.role_id', '=', 'role.id')
            ->select('users.id', 'users.user_code', 'users.email', 'users.phone_number', 'users.name', 'users.status', 'users.role_id', 'users.class_id', 'users.created_at', 'users.updated_at', 'classes.class_name', 'role.role_name')->get();
        return response()->json($data);
    }
}
