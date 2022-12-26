<?php

namespace App\Http\Controllers;

use App\Events\MessageEvent;
use App\Http\Resources\ChatResource;
use App\Http\Resources\ChatRoomResource;
use App\Http\Resources\MessageResource;
use App\Models\Course;
use App\Models\CourseJoined;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ChatController extends Controller
{
    public function view_image($fileName)
    {
        return Storage::disk('s3')->response('image-chat/' . $fileName);
    }
    public function list_message($slug)
    {
        $messages = Course::with('messages.user', 'subject', 'course_joined')->where('slug', $slug)->first();
        return new ChatResource($messages);
    }
    public function send(Request $request)
    {
        $slug = $request->input('slug');
        $message = $request->input('message');

        $dataSave = [
            'user_id' => Auth::id(),
            'course_id' => Course::where('slug', $slug)->value('id'),
            'message' => $message,
            'message_type' => 1
        ];

        if ($request->hasfile('image')) {
            $file = $request->file('image');
            $name = time() . rand(1, 500) . '.' . $file->extension();
            $file->storeAs('image-chat/', $name, 's3');

            $dataSave['message'] = json_encode([
                'message' => $message,
                'link' => $name
            ]);
            $dataSave['message_type'] = 2;
        }

        Message::create($dataSave);

        MessageEvent::dispatch(
            $slug,
            Auth::id(),
            Auth::user()->name,
            $dataSave['message_type'] == 1 ? $dataSave['message'] : json_decode($dataSave['message'], true),
            $dataSave['message_type']
        );
        return response()->json([
            'status' => 1,
        ]);
    }
    public function my_room(Request $request)
    {

        // $data = CourseJoined::has('course')->with(['course.messages' => function ($query) {
        //     $query->orderBy('id', 'desc')->first();
        // }])->where('user_id', Auth::id())->get();
        $data = CourseJoined::has('course')->with(['course.subject', 'messages' => function ($query) {
            $query->orderBy('id', 'desc');
        }])->where('user_id', Auth::id())->get();
        // return $data;

        return ChatRoomResource::collection($data);
    }
}
