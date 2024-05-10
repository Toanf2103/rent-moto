<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MotoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'license_plate' => $this->license_plate,
            'status' => $this->status,
            'price' => $this->price,
            'moto_type' => MotoTypeResource::make($this->whenLoaded('motoType')),
            'description' => $this->description,
            'images' => ImageResource::collection($this->whenLoaded('images')),
            'calendar' => MotoCalendarResource::collection($this->whenLoaded('orders')),
            'revenue' => $this->whenHas('revenue')
        ];
    }
}
