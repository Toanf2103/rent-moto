<?php

namespace App\Http\Requests\Admin;

use App\Enums\Moto\MotoStatus;
use App\Rules\LicensePlateRule;
use Illuminate\Foundation\Http\FormRequest;

class MotoRequest extends FormRequest
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
            'name' => ['required', 'string', 'min:5', 'max:100'],
            'license_plate' => [
                'required', 'string',
                'unique:motos,license_plate',
                new LicensePlateRule()
            ],
            'status' => [
                'nullable',
                'in:' . implode(',', MotoStatus::getValues())
            ],
            'price' => [
                'required', 'numeric',
                'min:' . config('define.price.min'),
                'max:' . config('define.price.max')
            ],
            'moto_type_id' => ['required', 'exists:moto_types,id'],
            'description' => ['nullable', 'string'],
            'images' => ['required', 'array', 'max:5'],
            'images.*' => ['max:1024', 'image']
        ];
    }
}
