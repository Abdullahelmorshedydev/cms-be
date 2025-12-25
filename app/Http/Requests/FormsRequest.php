<?php

namespace App\Http\Requests;

use App\Enums\FormTypeEnum;
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
        if (!$this->input('type'))
            return [
                'type' => ['required', Rule::enum(FormTypeEnum::class)]
            ];
        return FormTypeEnum::getValidations($this->input('type'));
    }

    public function messages(): array
    {
        return [
            'first_name.required' => __('custom.validation.first_name.required'),
            'first_name.string' => __('custom.validation.first_name.string'),
            'first_name.min' => __('custom.validation.first_name.min'),
            'first_name.max' => __('custom.validation.first_name.max'),
            'last_name.required' => __('custom.validation.last_name.required'),
            'last_name.string' => __('custom.validation.last_name.string'),
            'last_name.min' => __('custom.validation.last_name.min'),
            'last_name.max' => __('custom.validation.last_name.max'),
            'phone.required' => __('custom.validation.phone.required'),
            'phone.string' => __('custom.validation.phone.string'),
            'phone.min' => __('custom.validation.phone.min'),
            'phone.max' => __('custom.validation.phone.max'),
            'country_code.required' => __('custom.validation.country_code.required'),
            'country_code.string' => __('custom.validation.country_code.string'),
            'country_code.min' => __('custom.validation.country_code.min'),
            'country_code.max' => __('custom.validation.country_code.max'),
            'email.required' => __('custom.validation.email.required'),
            'email.string' => __('custom.validation.email.string'),
            'email.email' => __('custom.validation.email.email'),
            'email.min' => __('custom.validation.email.min'),
            'email.max' => __('custom.validation.email.max'),
            'subject.required' => __('custom.validation.subject.required'),
            'subject.in' => __('custom.validation.subject.in'),
            'budget.required' => __('custom.validation.budget.required'),
            'budget.in' => __('custom.validation.budget.in'),
            'message.required' => __('custom.validation.message.required'),
            'message.string' => __('custom.validation.message.string'),
            'message.min' => __('custom.validation.message.min'),
            'message.max' => __('custom.validation.message.max'),
            'type.required' => __('custom.validation.type.required'),
            'type.enum' => __('custom.validation.type.in')
        ];
    }
}
