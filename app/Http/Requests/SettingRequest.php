<?php

namespace App\Http\Requests;

use App\Enums\SettingTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SettingRequest extends FormRequest
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
        $rules = [];
        $rules['type'] = ['required', Rule::in(SettingTypeEnum::cases())];
        if ($this->type == SettingTypeEnum::TEXT->value) {
            $rules['value']       = ['required', 'array'];
            $rules['value']['en'] = ['required', 'string', 'min:3', 'max:150'];
            $rules['value']['ar'] = ['required', 'string', 'min:3', 'max:150'];
        } elseif ($this->type == SettingTypeEnum::IMAGE->value) {
            $rules['image'] = ['required', 'image', 'mimetypes:image/png,image/jpg,image/jpeg,image/webp', 'mimes:png,jpg,jpeg,webp', 'max:2048'];
        }
        return $rules;
    }

    public function messages(): array
    {
        return [
            'value.required' => __('custom.validation.value.required'),
            'value.array'    => __('custom.validation.value.array'),

            'value.en.required' => __('custom.validation.value_en.required'),
            'value.en.string'   => __('custom.validation.value_en.string'),
            'value.en.min'      => __('custom.validation.value_en.min'),
            'value.en.max'      => __('custom.validation.value_en.max'),

            'value.ar.required' => __('custom.validation.value_ar.required'),
            'value.ar.string'   => __('custom.validation.value_ar.string'),
            'value.ar.min'      => __('custom.validation.value_ar.min'),
            'value.ar.max'      => __('custom.validation.value_ar.max'),

            'image.image'     => __('custom.validation.image.image'),
            'image.mimes'     => __('custom.validation.image.mimes'),
            'image.mimetypes' => __('custom.validation.image.mimetypes'),
            'image.max'       => __('custom.validation.image.max'),
        ];
    }
}
