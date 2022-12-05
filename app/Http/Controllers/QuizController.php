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
    public function getQuizCourse(Request $request)
    {
        $slug_course = $request->input('slug_course');
        $slug_quiz = $request->input('slug_quiz');
        $course = Course::where('slug',$slug_course)->first();

        $list_question = QuestionBank::where('subject_id',$course->subject_id)->take(10)->get();

        return  QuestionResource::collection($list_question);
    }
}