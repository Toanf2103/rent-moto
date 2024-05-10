<?php

namespace App\Http\Requests\Admin;

use App\Enums\MotoType\MotoTypeStatus;
use Illuminate\Foundation\Http\FormRequest;

class MotoTypeUpdateRequest extends FormRequest
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
        $id = $this->route('motoType')->id;
        return [
            'name' => ['string', 'min:5', 'max:49', 'unique:moto_types,name,' . $id],
            'status' => [
                'required_without_all:name',
                'in:' . implode(',', MotoTypeStatus::getValues()),
            ]
        ];
    }
}
