<?php

namespace App\Http\Requests\Profile;

use App\Enums\GenderEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAdminProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->isAdmin();
    }

    public function prepareForValidation()
    {
        $this->merge([
            'user_id' => auth()->id(),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $user = auth()->user();
        return [
            'user_id'      => ['required', 'exists:users,id'],
            'name'         => ['required', 'string', 'min:3', 'max:150'],
            'email'        => ['required', 'string', 'email', 'unique:users,email,' . $user->id],
            'phone'        => ['required', 'string', 'min:10', 'max:20', 'unique:users,phone,' . $user->id],
            'country_code' => ['required', 'string', 'min:2', 'max:5'],
            'gender'       => ['nullable', Rule::in(GenderEnum::values())],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'address'      => ['nullable', 'array'],
            'address.street' => ['nullable', 'string', 'max:255'],
            'address.city' => ['nullable', 'string', 'max:100'],
            'address.state' => ['nullable', 'string', 'max:100'],
            'address.country' => ['nullable', 'string', 'max:100'],
            'address.postal_code' => ['nullable', 'string', 'max:20'],
            'bio'          => ['nullable', 'string', 'min:3', 'max:500'],
            'job_title'    => ['nullable', 'string', 'min:3', 'max:150'],
            'image'        => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'mimetypes:image/png,image/jpg,image/jpeg,image/webp', 'max:5000'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'         => __('custom.validation.name.required'),
            'name.string'           => __('custom.validation.name.string'),
            'name.min'              => __('custom.validation.name.min'),
            'name.max'              => __('custom.validation.name.max'),
            'email.required'        => __('custom.validation.email.required'),
            'email.email'           => __('custom.validation.email.email'),
            'email.unique'          => __('custom.validation.email.unique'),
            'phone.required'        => __('custom.validation.phone.required'),
            'phone.string'          => __('custom.validation.phone.string'),
            'phone.min'             => __('custom.validation.phone.min'),
            'phone.max'             => __('custom.validation.phone.max'),
            'phone.unique'          => __('custom.validation.phone.unique'),
            'country_code.required' => __('custom.validation.country_code.required'),
            'country_code.string'   => __('custom.validation.country_code.string'),
            'country_code.min'      => __('custom.validation.country_code.min'),
            'country_code.max'      => __('custom.validation.country_code.max'),
            'image.image'           => __('custom.validation.image.image'),
            'image.mimes'           => __('custom.validation.image.mimes'),
            'image.max'             => __('custom.validation.image.max'),
            'bio.string'            => __('custom.validation.bio.string'),
            'bio.min'               => __('custom.validation.bio.min'),
            'bio.max'               => __('custom.validation.bio.max'),
            'job_title.string'      => __('custom.validation.job_title.string'),
            'job_title.min'         => __('custom.validation.job_title.min'),
            'job_title.max'         => __('custom.validation.job_title.max'),
            'gender.in'             => __('custom.validation.gender.invalid'),
            'date_of_birth.date'    => __('custom.validation.date_of_birth.date'),
            'date_of_birth.before'  => __('custom.validation.date_of_birth.before'),
        ];
    }
}
