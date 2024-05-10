<?php

namespace App\Http\Requests\Admin\DataAnalytis;

use Illuminate\Foundation\Http\FormRequest;

class YearlyRevenueStatisticsRequest extends FormRequest
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
            'years' => ['array', 'min:1'],
            'years.*' => ['date_format:Y'],
            'customer_id' => ['exists:users,id'],
            'rent_package_id' => ['exists:rent_packages,id'],
        ];
    }
}
