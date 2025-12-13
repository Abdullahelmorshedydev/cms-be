<?php

namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'guard_name' => request()->ajax() || request()->expectsJson() ? 'sanctum' : 'web',
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:100', Rule::unique('roles', 'name')->ignore($this->role, 'id')],
            'display_name' => ['required', 'array'],
            'display_name.*' => ['required', 'string', 'min:3', 'max:100'],
            'guard_name' => ['required', 'string', 'in:web,sanctum'],
            'permissions' => ['required', 'array'],
            'permissions.*' => ['required', 'exists:permissions,name'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'             => __('custom.validation.name.required'),
            'name.unique'               => __('custom.validation.name.unique'),
            'name.min'                  => __('custom.validation.name.min'),
            'name.max'                  => __('custom.validation.name.max'),
            'display_name.required'     => __('custom.validation.display_name.required'),
            'display_name.array'        => __('custom.validation.display_name.array'),
            'display_name.en.required'  => __('custom.validation.display_name_en.required'),
            'display_name.en.max'       => __('custom.validation.display_name_en.max'),
            'display_name.en.min'       => __('custom.validation.display_name_en.min'),
            'display_name.ar.required'  => __('custom.validation.display_name_ar.required'),
            'display_name.ar.max'       => __('custom.validation.display_name_ar.max'),
            'display_name.ar.min'       => __('custom.validation.display_name_ar.min'),
            'permissions.required'      => __('custom.validation.permissions.required'),
            'permissions.array'         => __('custom.validation.permissions.array'),
            'permissions.*.required'    => __('custom.validation.permission.required'),
            'permissions.*.exists'      => __('custom.validation.permission.exists'),
        ];
    }
}
