<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
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
            'email'    => ['required', 'email', 'exists:users,email'],
            'password' => ['required', 'min:8', 'max:255', 'same:confirm-password']
        ];
    }

    public function messages()
    {
        return [
            'token.required'    => __('custom.validation.token.required'),
            'token.string'      => __('custom.validation.token.string'),
            'token.exists'      => __('custom.auth.token_expired'),
            'password.required' => __('custom.validation.password.required'),
            'password.min'      => __('custom.validation.password.min'),
            'password.max'      => __('custom.validation.password.max'),
            'password.same'     => __('custom.validation.password.same')
        ];
    }
}
