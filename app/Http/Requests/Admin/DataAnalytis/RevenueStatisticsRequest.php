<?php

namespace App\Http\Requests\Admin\DataAnalytis;

use Illuminate\Foundation\Http\FormRequest;

class RevenueStatisticsRequest extends FormRequest
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
                'date_format:' . config('define.date_format'), 'before_or_equal:end_date'
            ],
            'end_date' => [
                'date_format:' . config('define.date_format'), 'before_or_equal:now'
            ],
            'customer_id' => ['exists:users,id'],
            'rent_package_id' => ['exists:rent_packages,id'],
        ];
    }
}
