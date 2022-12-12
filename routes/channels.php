<?php

use App\Models\CourseJoined;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('room.{idRoom}', function ($user, $idRoom) {
    return true;
});
// Broadcast::channel('room.{id}', function ($user, $id) {
//     // $data = CourseJoined::where('user_id', $user->id)->where('course_id', $id)->first();
//     return true;//($data) ? true : false;
// });
// Broadcast::routes(['middleware' => ['auth:sanctum']]);
