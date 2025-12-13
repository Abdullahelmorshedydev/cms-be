<?php

namespace App\Http\Requests\Profile;

use App\Enums\GenderEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStudentProfileRequest extends FormRequest
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
        $user = auth()->user();
        return [
            'name' => ['required', 'string', 'min:3', 'max:150'],
            'email' => ['required', 'string', 'email', 'unique:users,email,' . $user->id],
            'phone' => ['required', 'string', 'min:10', 'max:20', 'unique:users,phone,' . $user->id],
            'country_code' => ['required', 'string', 'min:2', 'max:5'],
            'gender' => ['nullable', Rule::in(array_column(GenderEnum::cases(), 'value'))],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'address' => ['nullable', 'array'],
            'address.country' => ['required_with:address', 'string', 'min:2', 'max:150'],
            'address.city' => ['required_with:address', 'string', 'min:2', 'max:150'],
            'address.street' => ['nullable', 'string', 'min:2', 'max:150'],
            'bio' => ['nullable', 'string', 'max:500'],
            'student_id' => ['nullable', 'string', 'max:50'],
            'grade' => ['nullable', 'string', 'max:50'],
            'class' => ['nullable', 'string', 'max:50'],
            'academic_year' => ['nullable', 'string', 'max:50'],
            'image' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'mimetypes:image/png,image/jpg,image/jpeg,image/webp', 'max:5000'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('custom.validation.name.required'),
            'name.string' => __('custom.validation.name.string'),
            'name.min' => __('custom.validation.name.min'),
            'name.max' => __('custom.validation.name.max'),
            'email.required' => __('custom.validation.email.required'),
            'email.email' => __('custom.validation.email.email'),
            'email.unique' => __('custom.validation.email.unique'),
            'phone.required' => __('custom.validation.phone.required'),
            'phone.string' => __('custom.validation.phone.string'),
            'phone.min' => __('custom.validation.phone.min'),
            'phone.max' => __('custom.validation.phone.max'),
            'phone.unique' => __('custom.validation.phone.unique'),
            'country_code.required' => __('custom.validation.country_code.required'),
            'country_code.string' => __('custom.validation.country_code.string'),
            'country_code.min' => __('custom.validation.country_code.min'),
            'country_code.max' => __('custom.validation.country_code.max'),
            'gender.in' => __('custom.validation.gender.invalid'),
            'date_of_birth.date' => __('custom.validation.date_of_birth.date'),
            'date_of_birth.before' => __('custom.validation.date_of_birth.before'),
            'address.array' => __('custom.validation.address.array'),
            'address.country.required_with' => __('custom.validation.address.country.required'),
            'address.city.required_with' => __('custom.validation.address.city.required'),
            'bio.max' => __('custom.validation.bio.max'),
            'student_id.max' => __('custom.validation.student_id.max'),
            'grade.max' => __('custom.validation.grade.max'),
            'class.max' => __('custom.validation.class.max'),
            'academic_year.max' => __('custom.validation.academic_year.max'),
            'image.image' => __('custom.validation.image.image'),
            'image.mimes' => __('custom.validation.image.mimes'),
            'image.max' => __('custom.validation.image.max'),
        ];
    }
}
