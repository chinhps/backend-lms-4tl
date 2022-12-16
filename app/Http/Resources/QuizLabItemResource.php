<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class QuizLabItemResource extends JsonResource
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
            "name" => $this['name'],
            "level" => $this['level'],
            "slug" => $this['slug'],
            "student_worked" => $this['student_worked'],
            "count_student" => $this['count_student'],
            "max_working" => $this['deadlines']['max_working'] ?? 0,
            "count_submit" => (!isset($this['point_submit']['id'])) ? collect($this['point_submit'])->count() ?? 0 :
                count(json_decode($this['point_submit']['content'],true)),
            "deadlines" => [
                "time_end" => $this['deadlines']['time_end'] ?? null,
                "time_start" => $this['deadlines']['time_start'] ?? null
            ],
            "config" => $this['deadlines'] ? true : false,
            "password" => (isset($this['deadlines']) && $this['deadlines']['password'] != null && $this['deadlines'] != '') ? true : false
        ];
    }
}
