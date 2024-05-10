<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'customer_id' => $this->user_id,
            'customer_name' => $this->user->name,
            'status' => $this->status,
            'employee_confirm_id' => $this->employee_confirm_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'date_complete' => $this->date_complete,
            'user_note' => $this->user_note,
            'phone_number' => $this->phone_number,
            'reason_deny' => $this->reason_deny,
            'rent_package_percent' => $this->rent_package_percent,
            'rent_package_detail' => RentPackageDetailResource::make($this->rentPackageDetail),
            'details' => OrderDetailResource::collection($this->whenLoaded('orderDetails')),
            'transactions' => TransactionResource::collection($this->whenLoaded('transactions')),
            'motos' => MotoResource::collection($this->whenLoaded('motos')),
            'holidays' => OrderHolidayResource::collection($this->whenLoaded('holidays')),
        ];
    }
}
