<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name'                  => ['required', 'string', 'min:3', 'max:150'],
            'email'                 => ['required', 'email', 'min:5', 'max:150', 'unique:users'],
            'phone'                 => ['required', 'min:10', 'max:15'],
            'country_code'          => ['required', 'min:2', 'max:5'],
            'password'              => ['required', 'min:8', 'max:100', 'confirmed'],
            'password_confirmation' => ['required', 'min:8', 'max:100'],
        ];
    }

    public function messages()
    {
        return [
            'name.required'                  => __('custom.validation.name.required'),
            'name.min'                       => __('custom.validation.name.min'),
            'name.max'                       => __('custom.validation.name.max'),
            'email.required'                 => __('custom.validation.email.required'),
            'email.email'                    => __('custom.validation.email.email'),
            'email.min'                      => __('custom.validation.email.min'),
            'email.max'                      => __('custom.validation.email.max'),
            'email.unique'                   => __('custom.validation.email.unique'),
            'phone.required'                 => __('custom.validation.phone.required'),
            'phone.min'                      => __('custom.validation.phone.min'),
            'phone.max'                      => __('custom.validation.phone.max'),
            'country_code.required'          => __('custom.validation.country_code.required'),
            'country_code.min'               => __('custom.validation.country_code.min'),
            'country_code.max'               => __('custom.validation.country_code.max'),
            'password.required'              => __('custom.validation.password.required'),
            'password.min'                   => __('custom.validation.password.min'),
            'password.max'                   => __('custom.validation.password.max'),
            'password.confirmed'             => __('custom.validation.password.confirmed'),
            'password_confirmation.required' => __('custom.validation.password_confirmation.required'),
            'password_confirmation.min'      => __('custom.validation.password_confirmation.min'),
            'password_confirmation.max'      => __('custom.validation.password_confirmation.max'),
        ];
    }
}
