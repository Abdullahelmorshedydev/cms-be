<?php

namespace App\Http\Requests\FormEmail;

use App\Enums\FormTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateFormEmailRequest extends FormRequest
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
        $validFormTypes = array_column(FormTypeEnum::cases(), 'value');

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('form_emails', 'email')->ignore($this->form_email, 'id')],
            'form_types' => ['required', 'array', 'min:1'],
            'form_types.*' => ['required', 'integer', Rule::in($validFormTypes)],
            'is_active' => ['required', 'in:0,1'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => __('custom.validation.name_required'),
            'email.required' => __('custom.validation.email_required'),
            'email.email' => __('custom.validation.email_invalid'),
            'email.unique' => __('custom.validation.email_exists'),
            'form_types.required' => __('custom.validation.form_types_required'),
            'form_types.min' => __('custom.validation.form_types_min'),
        ];
    }
}

