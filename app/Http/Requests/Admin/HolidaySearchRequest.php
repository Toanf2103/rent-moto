<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class HolidaySearchRequest extends FormRequest
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
            'date' => ['date_format:' . config('define.holiday_format')],
            'start_date' => ['date_format:' . config('define.holiday_format')],
            'end_date' => ['date_format:' . config('define.holiday_format'), 'after:start_date'],
        ];
    }
}
