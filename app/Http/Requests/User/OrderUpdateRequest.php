<?php

namespace App\Http\Requests\User;

use App\Enums\Order\OrderStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'status' => ['string', Rule::in([OrderStatus::CANCEL])],
            'user_note' => ['string', 'min:5', 'max:255'],
            'phone_numeber' => ['digits_between:10,12']
        ];
    }
}
