<?php

namespace App\Http\Controllers;

use App\Http\Resources\DeadlineResource;
use App\Models\Deadline;
use App\Models\Lab;
use App\Models\Quiz;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DeadLineController extends Controller
{
    public function getOne($type, $slug)
    {
        ($type == 'quiz') ?
            $data = Quiz::where('slug', $slug)->first()
            :
            $data = Lab::where('slug', $slug)->first();

        if (!$data) {
            return BaseResponse::ResWithStatus('Không tìm thấy bài này!', 404);
        }
        return new DeadlineResource($data->deadlines);
    }
    public function upsert(Request $request)
    {
        $id = $request->input('id') ?? null;
        $time_start = $request->input('time_start');
        $time_end = $request->input('time_end');
        $password = $request->input('password');
        $questions = $request->input('questions');
        $max_time_working = $request->input('max_time_working');
        $max_working = $request->input('max_working');

        $type = $request->input('type');
        $slug = $request->input('slug');
        // try {

        $dataUpsert = [
            'time_start' => $time_start,
            'time_end' => $time_end,
            'password' => ($password != 'null') ? $password : null,
            'questions' => $questions,
            'max_time_working' => $max_time_working,
            'max_working' => $max_working
        ];

        if ($type == 'quiz') {
            Quiz::where('slug', $slug)->first()->deadlines()->updateOrCreate([
                'id' => $id
            ], $dataUpsert);
        } else {
            Lab::where('slug', $slug)->first()->deadlines()->updateOrCreate([
                'id' => $id
            ], $dataUpsert);
        }

        return BaseResponse::ResWithStatus($id ? "Sửa thành công!" : 'Tạo mới Lab thành công! Cần cấu hình để có thể làm bài', 200);
        // } catch (\Exception $err) {
        // return BaseResponse::ResWithStatus($id ? "Có lỗi khi sửa!" : 'Có lỗi xảy ra khi tạo mới!', 500);
        // }
    }
}
