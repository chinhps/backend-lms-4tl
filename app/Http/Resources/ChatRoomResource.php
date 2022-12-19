<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChatRoomResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $lastMess = $this->messages->first();
        return [
            "id" => $this->course_id,
            "slug" => $this->course->slug ?? NULL,
            "last_message" => (isset($lastMess->message_type)) ?
                ($lastMess->message_type == 1 ?
                    $lastMess->message
                    : json_decode($lastMess->message, true)['message'] ?? null)
                : null,

            "created_at"  => $lastMess->created_at ?? null,
            "name" => $this->course->subject->name . ' - ' .  $this->course->class_code
        ];
    }
}
