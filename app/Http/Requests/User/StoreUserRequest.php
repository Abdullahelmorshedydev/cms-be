<?php

namespace App\Http\Requests\User;

use App\Enums\GenderEnum;
use App\Enums\StatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
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
            'name'            => ['required', 'string', 'min:3', 'max:100'],
            'email'           => ['required', 'email', 'min:3', 'max:100', 'unique:users,email'],
            'password'        => ['required', 'string', 'min:6', 'max:100', 'confirmed'],
            'role'            => ['nullable', 'exists:roles,name'],
            'is_admin'        => ['required', 'boolean'],
            'phone'           => ['required', 'string', 'min:10', 'max:20', 'unique:users,phone'],
            'country_code'    => ['required', 'string', 'min:2', 'max:5'],
            'address'         => ['required', 'array'],
            'address.country' => ['required', 'string', 'min:3', 'max:150'],
            'address.city'    => ['required', 'string', 'min:3', 'max:150'],
            'address.street'  => ['required', 'string', 'min:3', 'max:150'],
            'date_of_birth'   => ['required', 'date'],
            'gender'          => ['required', Rule::in(GenderEnum::cases())],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'            => __('custom.validation.name.required'),
            'name.string'              => __('custom.validation.name.string'),
            'name.min'                 => __('custom.validation.name.min'),
            'name.max'                 => __('custom.validation.name.max'),
            'email.required'           => __('custom.validation.email.required'),
            'email.email'              => __('custom.validation.email.email'),
            'email.min'                => __('custom.validation.email.min'),
            'email.max'                => __('custom.validation.email.max'),
            'email.unique'             => __('custom.validation.email.unique'),
            'password.required'        => __('custom.validation.password.required'),
            'password.string'          => __('custom.validation.password.string'),
            'password.min'             => __('custom.validation.password.min'),
            'password.max'             => __('custom.validation.password.max'),
            'role.exists'              => __('custom.validation.role.exists'),
            'is_admin.required'        => __('custom.validation.is_admin.required'),
            'is_admin.in'              => __('custom.validation.is_admin.in'),
            'phone.required'           => __('custom.validation.phone.required'),
            'phone.string'             => __('custom.validation.phone.string'),
            'phone.min'                => __('custom.validation.phone.min'),
            'phone.max'                => __('custom.validation.phone.max'),
            'phone.unique'             => __('custom.validation.phone.unique'),
            'country_code.required'    => __('custom.validation.country_code.required'),
            'country_code.string'      => __('custom.validation.country_code.string'),
            'country_code.min'         => __('custom.validation.country_code.min'),
            'country_code.max'         => __('custom.validation.country_code.max'),
            'date_of_birth.required'   => __('custom.validation.date_of_birth.required'),
            'date_of_birth.before'     => __('custom.validation.date_of_birth.before'),
            'gender.required'          => __('custom.validation.gender.required'),
            'gender.rule'              => __('custom.validation.gender.in'),
            'address.required'         => __('custom.validation.address.required'),
            'address.array'            => __('custom.validation.address.array'),
            'address.country.required' => __('custom.validation.country.required'),
            'address.country.string'   => __('custom.validation.country.string'),
            'address.country.min'      => __('custom.validation.country.min'),
            'address.country.max'      => __('custom.validation.country.max'),
            'address.city.required'    => __('custom.validation.city.required'),
            'address.city.string'      => __('custom.validation.city.string'),
            'address.city.min'         => __('custom.validation.city.min'),
            'address.city.max'         => __('custom.validation.city.max'),
            'address.street.required'  => __('custom.validation.street.required'),
            'address.street.string'    => __('custom.validation.street.string'),
            'address.street.min'       => __('custom.validation.street.min'),
            'address.street.max'       => __('custom.validation.street.max'),
        ];
    }
}
