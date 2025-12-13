<?php

namespace App\Http\Requests;

use App\Enums\ContactSubjectEnum;
use App\Enums\FormTypeEnum;
use App\Enums\StatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FormsRequest extends FormRequest
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
            'name'         => ['required', 'string', 'min:6', 'max:150'],
            'phone'        => ['required', 'string', 'min:6', 'max:20'],
            'country_code' => ['required', 'string', 'min:2', 'max:5'],
            'email'        => ['required', 'string', 'email', 'max:255'],
            'subject'      => ['required', Rule::in(ContactSubjectEnum::values())],
            'message'      => ['required', 'string', 'min:6', 'max:1000'],
            'type'         => ['required', Rule::in(FormTypeEnum::values())],
            'is_active'    => ['required', Rule::in(StatusEnum::values())],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'         => __('custom.validation.name.required'),
            'name.string'           => __('custom.validation.name.string'),
            'name.min'              => __('custom.validation.name.min'),
            'name.max'              => __('custom.validation.name.max'),
            'phone.required'        => __('custom.validation.phone.required'),
            'phone.string'          => __('custom.validation.phone.string'),
            'phone.min'             => __('custom.validation.phone.min'),
            'phone.max'             => __('custom.validation.phone.max'),
            'country_code.required' => __('custom.validation.country_code.required'),
            'country_code.string'   => __('custom.validation.country_code.string'),
            'country_code.min'      => __('custom.validation.country_code.min'),
            'country_code.max'      => __('custom.validation.country_code.max'),
            'email.required'        => __('custom.validation.email.required'),
            'email.string'          => __('custom.validation.email.string'),
            'email.email'           => __('custom.validation.email.email'),
            'email.min'             => __('custom.validation.email.min'),
            'email.max'             => __('custom.validation.email.max'),
            'subject.required'      => __('custom.validation.subject.required'),
            'subject.in'            => __('custom.validation.subject.in'),
            'message.required'      => __('custom.validation.message.required'),
            'message.string'        => __('custom.validation.message.string'),
            'message.min'           => __('custom.validation.message.min'),
            'message.max'           => __('custom.validation.message.max'),
            'type.required'         => __('custom.validation.type.required'),
            'type.in'               => __('custom.validation.type.in'),
            'is_active.required'    => __('custom.validation.is_active.required'),
            'is_active.in'          => __('custom.validation.is_active.in'),
        ];
    }
}
