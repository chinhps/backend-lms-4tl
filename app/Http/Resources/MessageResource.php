<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
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
            "user_id" => $this->user_id,
            "message" => ($this->message_type == 1) ? $this->message : json_decode($this->message, true),
            "name" => $this->user->name,
            "avatar" => $this->user->avatar,
            "message_type" => $this->message_type,
            "created_at" => $this->created_at,
        ];
    }
}
