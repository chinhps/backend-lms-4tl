<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class QuizWorkingResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this['data']['info_quiz']['id'],
            'id_point' => $this['data']['info_quiz']['id_point'],
            'name' => $this['data']['info_quiz']['name'],
            'slug' => $this['data']['info_quiz']['slug'],
            'time_working' => $this['data']['info_quiz']['deadlines']['max_time_working'],
            'max_working' => $this['data']['info_quiz']['deadlines']['max_working'],
            'questions' => QuestionResource::collection($this['data']['list_questions'])
        ];
    }
}
