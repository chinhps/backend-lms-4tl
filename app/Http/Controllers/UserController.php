<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends BaseController
{
   public function list()
   {
      $data = DB::table('users')->join('classes', 'users.class_id', '=', 'classes.id')->join('role', 'users.role_id', '=', 'role.id')
      ->select('users.id', 'users.user_code', 'users.email','users.phone_number','users.name','users.status', 'users.role_id', 'users.class_id', 'users.created_at','users.updated_at','classes.class_name', 'role.role_name')->get();
      return response()->json($data);
   }
}
