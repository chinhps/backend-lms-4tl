<?php

namespace App\Http\Controllers;

use App\Http\Resources\DocumentResource;
use App\Models\Course;
use App\Models\Document;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    public function delete($slug)
    {
        $data = Document::where('slug', $slug)->first()->delete();
        if ($data) {
            return BaseResponse::ResWithStatus('Xóa thành công!');
        }
        return BaseResponse::ResWithStatus('Không tìm thấy để xóa!', 404);
    }

    public function getOne($slug)
    {
        $data = Document::where('slug', $slug)->first();
        return new DocumentResource($data);
    }

    public function upsert(Request $request)
    {
        $id = $request->input('id') ?? null;
        $nameDocument = $request->input('nameDocument');
        $rangeDocument = $request->input('rangeDocument');
        $slugCourse = $request->input('slugCourse');
        $description = $request->input('description');

        ($rangeDocument == 'courses') ? $group_id = 2 : $group_id = 3;

        try {
            if ($request->hasfile('files')) {
                foreach ($request->file('files') as $file) {
                    $name = time() . rand(1, 100) . Str::random(6) . '.' . $file->extension();
                    $file->move(public_path('documents'), $name);
                    $files = env('APP_URL') . "/documents/$name";
                }
            }

            $dataUpsert = [
                'name' => $nameDocument,
                'group_id' => $group_id,
                'link' => $files,
                'description' => ($description != 'null') ? $description : null,
                'public' => 1,
                'slug' => Str::slug($nameDocument . Str::random(8)),
            ];

            $course = Course::with('documents', 'subject.documents')->where('slug', $slugCourse)->first();
            if ($rangeDocument == 'subjects') {
                $course->subject->documents()->updateOrCreate([
                    'id' => $id ?? null
                ], $dataUpsert);
            } else {
                $course->documents()->updateOrCreate([
                    'id' => $id ?? null
                ], $dataUpsert);
            }
            return BaseResponse::ResWithStatus($id ? "Sửa thành công!" : 'Tạo mới tài liệu thành công!', 200);
        } catch (\Exception $err) {
            return BaseResponse::ResWithStatus($id ? "Có lỗi khi sửa!" : 'Có lỗi xảy ra khi tạo mới!', 500);
        }
    }
}
