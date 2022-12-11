<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PointSubmitResource extends JsonResource
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
            "name" => $this->user->name,
            "user_code" => $this->user->user_code,
            "user_id" => $this->user->id,
            "name_submit" => $this->pointsubmitable->name ?? "Đã bị xóa",
            "content" => json_decode($this->content,true),
            "count_submit" => ($this->pointsubmitable_type == 'quizs') ?
                $this->total : count(json_decode($this->content, true)),
            "point" => $this->point,
            "description" => $this->note ?? null,
            "status" => $this->status,
            "status_name" => $this->status == 1 ? "Đã làm xong" : "Đang làm",
            "note" => $this->point < 5 ? "Failed" : "Passed",
        ];
    }
}
