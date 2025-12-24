<?php

namespace App\Http\Requests\Admin;

use App\Enums\StatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class UpdateOrCreatePageRequest extends FormRequest
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
     */
    public function rules(): array
    {
        $page = $this->route('page');
        $pageId = $page?->id ?? null;
        $rules = [];

        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $rules[] = [
                "name.{$localeCode}" => [
                    'required',
                    'string',
                    'max:255',
                    $pageId ? Rule::unique('pages', "name->{$localeCode}")->ignore($pageId) : Rule::unique('pages', "name->{$localeCode}")
                ]
            ];
        }

        $rules[] = [
            'status' => ['nullable', Rule::in(StatusEnum::values())]
        ];

        return $rules;
    }
}
