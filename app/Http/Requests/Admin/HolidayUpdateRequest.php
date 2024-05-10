<?php

namespace App\Http\Requests\Admin;

use App\Enums\Holiday\HolidayStatus;
use Illuminate\Foundation\Http\FormRequest;

class HolidayUpdateRequest extends FormRequest
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
        $id = $this->route('holiday')->id;
        return [
            'name' => ['string', 'max:50'],
            'date' => [
                'date_format:' . config('define.holiday_format'),
                "unique:holidays,date,{$id},id,deleted_at,NULL"
            ],
            'status' => [
                'in:' . implode(',', HolidayStatus::getValues())
            ],
            'precent' => [
                'numeric',
                'min:10',
                'max:1000'
            ]
        ];
    }
}
