<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name' => ['required', 'string', 'min:5', 'max:30'],
            'email' => [
                'required', 'email', 'min:5', 'max:30', 'unique:users,email'
            ],
            'password' => [
                'required', 'min:6', 'max:30'
            ],
            'phone_number' => [
                'digits_between:10,12', 'unique:users,phone_number'
            ]
        ];
    }
}
