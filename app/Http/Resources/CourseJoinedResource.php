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
            "user_code" => $this->user->user_code ?? "Không xác định",
            "name" => $this->user->name ?? "Không xác định",
            "email" => $this->user->email ?? "Không xác định",
            "role_name" => $this->user->role->role_name ?? "Không xác định",
            "role_code" => $this->user->role->role_code ?? "Không xác định",
        ];
    }
}
