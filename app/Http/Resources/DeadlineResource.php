<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DeadlineResource extends JsonResource
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
            "id" => $this->id ?? null,
            "time_start" => $this->time_start ?? null,
            "time_end" => $this->time_end ?? null,
            "password" => $this->password ?? null,
            "questions" => $this->questions ?? null,
            "max_time_working" => $this->max_time_working ?? null,
            "max_working" => $this->max_working ?? null,
        ];
    }
}
