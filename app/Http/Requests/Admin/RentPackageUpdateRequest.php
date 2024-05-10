<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class RentPackageUpdateRequest extends FormRequest
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
        $id = $this->route('rentPackage')->id;
        return [
            'name' => ['required', 'unique:rent_packages,name,' . $id],
            'details' => ['array', 'min:1'],
            'details.*.percent' => ['required', 'numeric', 'min:10', 'max:100'],
            'details.*.rent_days_min' => ['required', 'numeric', 'min:1', 'max:' . config('define.max_date_rent')],
            'details.*.rent_days_max' => [
                'required',
                'numeric', 'min:1',
                'max:' . config('define.max_date_rent'),
                'gt:details.*.rent_days_min'
            ],
        ];
    }
}
