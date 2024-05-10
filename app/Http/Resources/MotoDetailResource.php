<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MotoDetailResource extends JsonResource
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
            'moto_type_id' => $this->moto_type_id,
            'description' => $this->description,
            'calendar_rent' => $this->orders,
            'images' => ImageResource::collection($this->images),
            'moto_type' => MotoTypeResource::make($this->motoType),
        ];
    }
}
