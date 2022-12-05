<?php

namespace App\Http\Controllers;

use App\Http\Resources\QuestionResource;
use App\Models\Course;
use App\Models\QuestionBank;
use App\Models\Quiz;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{

    public function joinQuiz(Request $request)
    {
        $password = $request->input('password');
        $slug_quiz = $request->input('slug_quiz');
        $slug_course = $request->input('slug_course');

        $quiz = Quiz::with('deadlines')->where('slug', $slug_quiz)->first();
        if ($quiz->password != null && $password != $quiz->password) {
            return BaseResponse::ResWithStatus("Mật khẩu sai không thể làm bài!", 403);
        }

        return $quiz;
        
        $course = Course::where('slug', $slug_course)->first();
        $list_question = QuestionBank::where('subject_id', $course->subject_id)->take(10)->get();

        // if() {

        // }

        return  QuestionResource::collection($list_question);
    }
}
