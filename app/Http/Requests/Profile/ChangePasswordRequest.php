<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
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
            'password'                  => ['required', 'string', 'min:6'],
            'new_password'              => ['required', 'string', 'min:6', 'max:150', 'confirmed'],
            'new_password_confirmation' => ['required', 'string', 'min:6'],
        ];
    }

    public function messages(): array
    {
        return [
            'password.required'                  => __('custom.validation.password.required'),
            'password.string'                    => __('custom.validation.password.string'),
            'password.min'                       => __('custom.validation.password.min'),
            'new_password.required'              => __('custom.validation.new_password.required'),
            'new_password.string'                => __('custom.validation.new_password.string'),
            'new_password.min'                   => __('custom.validation.new_password.min'),
            'new_password.max'                   => __('custom.validation.new_password.max'),
            'new_password.confirmed'             => __('custom.validation.new_password.confirmed'),
            'new_password_confirmation.required' => __('custom.validation.new_password_confirmation.required'),
            'new_password_confirmation.string'   => __('custom.validation.new_password_confirmation.string'),
            'new_password_confirmation.min'      => __('custom.validation.new_password_confirmation.min'),
        ];
    }
}
