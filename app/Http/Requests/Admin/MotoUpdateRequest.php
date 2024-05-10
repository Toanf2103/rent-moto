<?php

namespace App\Http\Requests\Admin;

use App\Enums\Moto\MotoStatus;
use App\Rules\LicensePlateRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class MotoUpdateRequest extends FormRequest
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
        $this->mergeIfMissing(['images' => []]);
        $this->mergeIfMissing(['images_delete_id' => []]);
        $id = $this->route('moto')->id;
        return [
            'name' => ['string', 'min:5', 'max:100'],
            'license_plate' => [
                'string',
                'unique:motos,license_plate,' . $id,
                new LicensePlateRule()
            ],
            'status' => [
                'in:' . implode(',', MotoStatus::getValues())
            ],
            'price' => [
                'numeric',
                'min:' . config('define.price.min'),
                'max:' . config('define.price.max')
            ],
            'moto_type_id' => ['exists:moto_types,id'],
            'description' => ['string'],
            'images_delete_id' => [
                'array',
            ],
            'images_delete_id.*' => [
                'exists:images,id,imageable_id,' . $id
            ],
            'images' => ['array', 'max:' . config('define.images.moto.max')],
            'images.*' => ['max:1024', 'image'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                $validated = $this->validated();
                $updateImageCount = count($validated['images']);
                $deleteImageCount = count($validated['images_delete_id']);
                $currentImageCount = $this->route('moto')->images->count();
                $countImg = $currentImageCount - $deleteImageCount + $updateImageCount;
                //Check total image of photos than maximum of image moto
                if ($countImg > config('define.images.moto.max')) {
                    $validator->errors()->add(
                        'images',
                        __('alert.moto.images.max')
                    );
                }
            }
        ];
    }
}
