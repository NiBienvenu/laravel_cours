<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AccidentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'location' => $this->location,
            'date' => $this->date,
            'description' => $this->description,
            'damage_estimate' => $this->damage_estimate,
            'longitude' => $this->longitude,
            'latitude' => $this->latitude,
        ];
    }
}
