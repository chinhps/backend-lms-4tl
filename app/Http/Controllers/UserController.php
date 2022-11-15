<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class UserController extends BaseController
{
   public function list(Request $reuqest,$id)
   {
        $data_input = $reuqest->all();
        // $data = DB::table('users')->paginate(3);
        $data = DB::table('users')->updateOrInsert([
            'id' => $id
        ],[
            'name' => "asdasd"
        ]);
        return response()->json($data,404);
   }
}
