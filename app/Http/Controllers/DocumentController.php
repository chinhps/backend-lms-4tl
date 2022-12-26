<?php

namespace App\Http\Controllers;

use App\Http\Resources\DocumentResource;
use App\Models\Course;
use App\Models\Document;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentController extends Controller
{

    public function download($file)
    {
        return Storage::disk('s3')->response('documents/' . $file);
    }

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

            $dataUpsert = [
                'name' => $nameDocument,
                'group_id' => $group_id,
                'description' => ($description != 'null') ? $description : null,
                'public' => 1,
                'slug' => Str::slug($nameDocument . Str::random(8)),
            ];

            if ($request->hasfile('files')) {
                foreach ($request->file('files') as $file) {
                    $name = time() . rand(1, 100) . Str::random(6) . '.' . $file->extension();
                    $file->storeAs('documents/', $name, 's3');
                    $dataUpsert['link'] = $name;
                }
            }

            $course = Course::with('documents', 'subject.documents')->where('slug', $slugCourse)->first();
            if ($rangeDocument == 'subjects') {
                $data_course = $course->subject->documents()->updateOrCreate([
                    'id' => $id ?? null
                ], $dataUpsert);
            } else {
                $data_course = $course->documents()->updateOrCreate([
                    'id' => $id ?? null
                ], $dataUpsert);
            }
            return BaseResponse::ResWithStatus(!$data_course->wasRecentlyCreated && $data_course->wasChanged() ? "Sửa thành công!" : 'Tạo mới tài liệu thành công!', 200);
        } catch (\Exception $err) {
            return $err;
            return BaseResponse::ResWithStatus('Có lỗi xảy ra!', 500);
        }
    }
}
