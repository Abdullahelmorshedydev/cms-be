<?php

namespace App\Http\Requests\Service;

use App\Enums\StatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateServiceRequest extends FormRequest
{
    /**
     * Determine if the Service is authorized to make this request.
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
            'name.en' => ['required', 'string', 'min:3', 'max:100'],
            'name.ar' => ['required', 'string', 'min:3', 'max:100'],
            'slug' => ['required', 'string', 'min:3', 'max:100', Rule::unique('services', 'slug')->ignore($this->service, 'slug')],
            'status' => ['required', Rule::in(StatusEnum::values())],
            'short_description' => ['nullable', 'array'],
            'short_description.en' => ['nullable', 'string'],
            'short_description.ar' => ['nullable', 'string'],
            'description' => ['nullable', 'array'],
            'description.en' => ['nullable', 'string'],
            'description.ar' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimetypes:image/png,image/jpg,image/jpeg,image/webp', 'mimes:png,jpg,jpeg,webp', 'max:2048']
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
            'slug.required' => __('custom.validation.slug.required'),
            'slug.string' => __('custom.validation.slug.string'),
            'slug.min' => __('custom.validation.slug.min'),
            'slug.max' => __('custom.validation.slug.max'),
            'slug.unique' => __('custom.validation.slug.unique'),
            'status.required' => __('custom.validation.status.required'),
            'status.in' => __('custom.validation.status.in'),
            'short_description.array' => __('custom.validation.short_description.array'),
            'short_description.en.string' => __('custom.validation.short_description_en.string'),
            'short_description.ar.string' => __('custom.validation.short_description_ar.string'),
            'description.array' => __('custom.validation.description.array'),
            'description.en.string' => __('custom.validation.description_en.string'),
            'description.ar.string' => __('custom.validation.description_ar.string'),
            'image.image' => __('custom.validation.image.image'),
            'image.mimetypes' => __('custom.validation.image.mimetypes'),
            'image.mimes' => __('custom.validation.image.mimes'),
            'image.max' => __('custom.validation.image.max')
        ];
    }
}
