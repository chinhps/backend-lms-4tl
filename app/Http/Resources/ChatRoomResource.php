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
        return [
            "id" => $this->course_id,
            "slug" => $this->course->slug ?? NULL,
            "last_message" => $this->course->messages,
            "name" => $this->course->subject->name . ' - ' .  $this->course->class_code
        ];
    }
}
