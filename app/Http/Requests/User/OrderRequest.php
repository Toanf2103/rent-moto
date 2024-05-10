<?php

namespace App\Http\Requests\User;

use App\Rules\DateDifferenceRule;
use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
            'start_date' => [
                'required', 'date_format:' . config('define.date_format'),
                'after_or_equal:now'
            ],
            'end_date' => [
                'required', 'date_format:' . config('define.date_format'),
                'after_or_equal:start_date',
                new DateDifferenceRule('start_date', config('define.max_date_rent'))
            ],
            'user_note' => ['string'],
            'phone_number' => ['required', 'digits_between:10,12'],
            'motos' => ['required', 'array'],
            'motos.*' => ['exists:motos,id', 'distinct']
        ];
    }
}
