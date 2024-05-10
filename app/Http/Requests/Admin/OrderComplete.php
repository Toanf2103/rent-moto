<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class OrderComplete extends FormRequest
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
            'incurred_cost' => [
                'numeric', 'min:' . config('define.price.min'),
                'max:' . config('define.price.max')
            ],
            'incurred_description' => [
                'string',
                'required_with:incurred_cost',
                'max:255'
            ],

            'discount_cost' => [
                'numeric', 'min:' . config('define.price.min'),
                'max:' . config('define.price.max')
            ],
            'discount_description' => [
                'string',
                'required_with:discount_cost',
                'max:255'
            ],
        ];
    }
}
