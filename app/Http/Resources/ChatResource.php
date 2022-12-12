<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
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
            "id" => $this->id,
            "name_course" => $this->subject->name . ' - ' .  $this->class_code,
            "members" => $this->course_joined()->count(),
            "messages" => MessageResource::collection($this->messages),
        ];
    }
}
