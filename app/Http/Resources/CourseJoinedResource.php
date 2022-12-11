<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CourseJoinedResource extends JsonResource
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
            "user_code" => $this->user->user_code,
            "name" => $this->user->name,
            "email" => $this->user->email,
            "role_name" => $this->user->role->role_name,
            "role_code" => $this->user->role->role_code,
        ];
    }
}
