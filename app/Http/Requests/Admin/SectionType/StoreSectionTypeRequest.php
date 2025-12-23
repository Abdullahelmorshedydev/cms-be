<?php

namespace App\Http\Requests\Admin\SectionType;

use App\Enums\SectionFieldEnum;
use App\Enums\SectionTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSectionTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::in(SectionTypeEnum::values())],
            'description' => ['nullable', 'string'],
            'fields' => ['required', 'array'],
            'fields.*' => ['required', 'string', Rule::in(SectionFieldEnum::values())],
            'image' => ['nullable', 'image', 'max:2048'],
        ];
    }
}
