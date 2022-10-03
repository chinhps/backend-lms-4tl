<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function ListUsers()
    {
        $data = User::get();
        return response()->json([
            "data" => $data
        ]);
    }
}
