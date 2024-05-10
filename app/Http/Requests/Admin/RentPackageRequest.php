<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class RentPackageRequest extends FormRequest
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
            'name' => ['required', 'unique:rent_packages,name'],
            'details' => ['required', 'array', 'min:1'],
            'details.*.percent' => ['required', 'numeric', 'min:10', 'max:100'],
            'details.*.rent_days_max' => [
                'required',
                'distinct',
                'numeric',
                'min:1',
            ],
        ];
    }
}
