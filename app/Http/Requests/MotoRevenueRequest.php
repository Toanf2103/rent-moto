<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MotoRevenueRequest extends FormRequest
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
            'year' => ['digits:4', 'required_with:month'],
            'month' => ['integer', 'between:1,12'],
            'moto_type' => ['exists:moto_types,id']
        ];
    }
}
