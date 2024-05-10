<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailResource extends JsonResource
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
            'moto' => MotoResource::make($this->moto),
            'price' => $this->price,
            'employee_receive_id' => $this->employee_receive_id,
            'employee_note' => $this->employee_note,
            'type_pay' => $this->type_pay,
            'total_pay' => $this->total_pay,
        ];
    }
}
