<?php

namespace App\Http\Requests\ServiceCategory;

use Illuminate\Foundation\Http\FormRequest;

class UpdateServiceCategoryRequest extends FormRequest
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
            'name' => ['required', 'array'],
            'name.en' => ['required', 'string', 'min:3', 'max:255'],
            'name.ar' => ['required', 'string', 'min:3', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('custom.validation.name.required'),
            'name.array' => __('custom.validation.name.array'),
            'name.en.required' => __('custom.validation.name_en.required'),
            'name.en.string' => __('custom.validation.name_en.string'),
            'name.en.min' => __('custom.validation.name_en.min'),
            'name.en.max' => __('custom.validation.name_en.max'),
            'name.ar.required' => __('custom.validation.name_ar.required'),
            'name.ar.string' => __('custom.validation.name_ar.string'),
            'name.ar.min' => __('custom.validation.name_ar.min'),
            'name.ar.max' => __('custom.validation.name_ar.max'),
        ];
    }

}