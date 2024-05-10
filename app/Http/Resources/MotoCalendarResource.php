<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MotoCalendarResource extends JsonResource
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
            'status' => $this->status,
            'customer_note' => $this->user_note,
            'customer_name' => $this->user->name,
            'customer_id' => $this->user->id,
            'start' => $this->start_date,
            'end' => $this->end_date,
        ];
    }
}
