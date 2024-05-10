<?php

namespace App\Http\Requests\Admin;

use App\Enums\User\UserStatus;
use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
        $id = $this->route('onlyUser')->id;
        return [
            'name' => ['min:5', 'max:30', 'required_without_all:phone_number,status'],
            'phone_number' => [
                'digits_between:10,12', 'unique:users,phone_number,' . $id
            ],
            'status' => [
                'in:' . implode(',', UserStatus::getValues())
            ],
            'address' => [
                'min:5', 'string'
            ]
        ];
    }
}
