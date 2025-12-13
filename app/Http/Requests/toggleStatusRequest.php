<?php

namespace App\Http\Requests;

use App\Enums\StatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class toggleStatusRequest extends FormRequest
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
            'is_active' => ['required', Rule::in(StatusEnum::values())],
        ];
    }

    public function messages(): array
    {
        return [
            'is_active.required' => __('custom.validation.is_active.required'),
            'is_active.rule'     => __('custom.validation.is_active.rule'),
        ];
    }
}
