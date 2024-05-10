<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RentPackageDetailResource extends JsonResource
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
            'percent' => $this->percent,
            'percent' => $this->percent,
            'rent_days_max' => $this->rent_days_max,
            'rent_package' => RentPackageResource::make($this->whenLoaded('rentPackage'))
        ];
    }
}
