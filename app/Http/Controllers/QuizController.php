<?php

namespace App\Http\Controllers;

use App\Http\Resources\QuestionResource;
use App\Http\Resources\QuizWorkingResource;
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
        if ($quiz->deadlines->password != null && $password != $quiz->deadlines->password) {
            return BaseResponse::ResWithStatus("Mật khẩu sai không thể làm bài!", 403);
        }
        
        $course = Course::where('slug', $slug_course)->first();
        $list_question = QuestionBank::where('subject_id', $course->subject_id)->take(10)->get();
        
        return new QuizWorkingResource([
            "data" => [
                'info_quiz' => $quiz,
                'list_questions' => $list_question
            ]
        ]);
    }
}
