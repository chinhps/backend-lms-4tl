<?php

namespace App\Http\Controllers;

use App\Models\LogsQuiz;
use App\Models\QuestionBank;
use App\Models\Quiz;
use Illuminate\Http\Request;

class QuizController extends Controller
{

    public $idUser;

    public function __construct()
    {
        $this->idUser = 3;
    }

    public function saveQuizSubject(Request $request)
    {

    }

    public function getQuizSubject(Request $request)
    {
        $level = $request->input('level');
        $id_subject = $request->input('id_subject');

        #Lưu thời gian làm bài
        
            # kiểm tra xem đã từng làm bài chưa
            $id_quiz = Quiz::where('subject_id',$id_subject)
            ->where('level',$level)
            ->value('id');

            $logQuiz = LogsQuiz::where('user_id',$this->idUser)
            ->where('quiz_id',$id_quiz);

        # lấy thông tin bài quiz
        $data = QuestionBank::select('id', 'question', 'answers')
            ->where('subject_id', $id_subject)
            ->where('level', $level)
            ->inRandomOrder()
            ->take(10)
            ->get();

        foreach ($data as $key => $val) {
            $formatAnswer = json_decode($val['answers'], true);

            foreach ($formatAnswer as $keyAs => $answer) {
                unset($answer['id']);
                unset($answer['isCorrect']);
                $formatAnswer[$keyAs] = $answer;
            }

            $data[$key]['answers'] = $formatAnswer;
            unset($formatAnswer);
        }

        return response()->json([
            "msg" => 200,
            "id_subject" => $id_subject,
            "level" => $request->level,
            "question" => $data
        ]);
    }
}
