<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UploadLabResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this['data']['info_lab']['id'],
            'id_point' => $this['data']['info_lab']['point_submit'][0]['id'] ?? $this['data']['info_lab']['id_point'],
            'name' => $this['data']['info_lab']['name'],
            'slug' => $this['data']['info_lab']['slug'],
            'description' => $this['data']['info_lab']['description'],
            'time_working' => $this['data']['info_lab']['deadlines']['max_time_working'],
            'max_working' => $this['data']['info_lab']['deadlines']['max_working'],
            'uploaded_lab' => json_decode($this['data']['info_lab']['point_submit']['content'] ?? [],true) ?? []
        ];
    }
}
