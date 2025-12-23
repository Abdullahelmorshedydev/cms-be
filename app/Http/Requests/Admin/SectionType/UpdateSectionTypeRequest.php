<?php

namespace App\Http\Requests\Admin\SectionType;

use App\Enums\SectionFieldEnum;
use App\Enums\SectionTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSectionTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255', Rule::in(SectionTypeEnum::values())],
            'description' => ['nullable', 'string'],
            'fields' => ['sometimes', 'array'],
            'fields.*' => ['required', 'string', Rule::in(SectionFieldEnum::values())],
            'image' => ['nullable', 'image', 'max:2048'],
        ];
    }
}
