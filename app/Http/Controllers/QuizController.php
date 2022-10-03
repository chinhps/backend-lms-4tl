<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function listQuiz(Request $request)
    {
        
        return response()->json([
            "name" => $request->name,
            "msg" => "day la quiz "
        ]);
    }

    public function Quiz(Request $request)
    {
        return response()->json([
            "quiz1" => $request->quiz1,
            "quiz2" => $request->quiz2,
            "msg" => "day la quiz "
        ]);
    }
}
