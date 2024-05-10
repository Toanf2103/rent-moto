<?php

namespace App\Http\Requests\Admin;

use App\Enums\Order\OrderStatus;
use Illuminate\Foundation\Http\FormRequest;

class OrderUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => [
                'required_without_all:user_note,phone_number',
                'in:' . implode(',', OrderStatus::getValues())
            ],
            'user_note' => ['string'],
            'phone_number' => ['digits_between:10,12'],
        ];
    }
}
