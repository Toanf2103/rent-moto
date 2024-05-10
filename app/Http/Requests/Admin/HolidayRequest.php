<?php

namespace App\Http\Requests\Admin;

use App\Enums\Holiday\HolidayStatus;
use Illuminate\Foundation\Http\FormRequest;

class HolidayRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:50'],
            'date' => [
                'required',
                'date_format:' . config('define.holiday_format'),
                'unique:holidays,date,NULL,NULL,deleted_at,NULL'
            ],
            'status' => [
                'in:' . implode(',', HolidayStatus::getValues())
            ],
            //precent below 100 is a discount
            //precent above 100 is a increase
            'precent' => [
                'required',
                'numeric',
                'min:10',
                'max:1000'
            ]
        ];
    }
}
