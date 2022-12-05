<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class QuestionBankController extends BaseController
{
  public function list()
  {
    $data = DB::table('question_bank')->join('subjects', 'question_bank.subject_id', '=', 'subjects.id')->join('users', 'question_bank.user_id', '=', 'users.id')
      ->select('question_bank.id', 'question_bank.subject_id', 'question_bank.question', 'question_bank.answers', 'question_bank.level', 'question_bank.user_id', 'question_bank.created_at', 'subjects.name as subject_name', 'users.name as user_name')
      ->paginate(10);
    return response()->json($data);
  }
  public function new(Request $request)
  {
    try {
      $data = DB::table('question_bank')->insert([
        'subject_id' => $request->subject_id,
        'question' => $request->question,
        'answers' => $request->answers,
        'level' => $request->level,
        'user_id' => $request->user_id,


      ]);
      return response()->json(["msg" => "Thêm thành công!"]);
    } catch (Exception $e) {
      return response()->json($e, 500);
    }
  }

  public function getOne(Request $request)
  {
    $data = DB::table('question_bank')->where('id', $request->id)->first();
    return response()->json($data);
  }

  public function put(Request $request)
  {
    try {
      DB::table('question_bank')->where('id', $request->id)->update([
        'subject_id' => $request->subject_id,
        'question' => $request->question,
        'answers' => $request->answers,
        'level' => $request->level,
        'user_id' => $request->user_id,
      ]);
      return response()->json(["msg" => "Sửa thành công id $request->id!"]);
    } catch (Exception $e) {
      return response()->json($e, 500);
    }
  }

  public function delete(Request $request)
    {
        try {
            DB::table('question_bank')->where('id', $request->id)->delete();
            return response()->json(["msg" => "Xóa thành công id $request->id!"]);
        } catch (Exception $e) {
            return response()->json($e, 500);
        }
    }
}
