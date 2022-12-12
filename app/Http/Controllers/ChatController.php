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

class ChatController extends Controller
{
    public function list_message($slug)
    {
        $messages = Course::with('messages.user', 'subject', 'course_joined')->where('slug', $slug)->first();
        return new ChatResource($messages);
    }
    public function send(Request $request)
    {
        $slug = $request->input('slug');
        $message = $request->input('message');

        Message::create([
            'user_id' => Auth::id(),
            'course_id' => Course::where('slug', $slug)->value('id'),
            'message' => $message,
            'message_type' => 1
        ]);

        MessageEvent::dispatch($slug, Auth::id(),Auth::user()->name, $message);
        return response()->json([
            'status' => 1,
        ]);
    }
    public function my_room(Request $request)
    {
        $data = CourseJoined::has('course')->with(['course', 'course.messages' => function ($query) {
            $query->orderBy('id', 'desc')->first();
        }])->where('user_id', Auth::id())->get();
        return ChatRoomResource::collection($data);
    }
}
