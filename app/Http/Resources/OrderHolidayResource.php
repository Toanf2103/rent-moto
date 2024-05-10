<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderHolidayResource extends JsonResource
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
            'order_id' => $this->order_id,
            'holiday_id' => $this->holiday_id,
            'precent' => $this->precent,
            'holiday' => HolidayResource::make($this->whenLoaded('holiday'))
        ];
    }
}
