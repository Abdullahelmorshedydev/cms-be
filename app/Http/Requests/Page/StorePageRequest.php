<?php

namespace App\Http\Requests\Page;

use App\Enums\ButtonTypeEnum;
use App\Enums\SectionTypeEnum;
use App\Enums\StatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class StorePageRequest extends FormRequest
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
        $rules = [
            'is_active' => ['required', 'integer', Rule::in(StatusEnum::values())],
        ];

        // Dynamic language-based validation
        $locales = LaravelLocalization::getSupportedLanguagesKeys();

        foreach ($locales as $locale) {
            // Page name
            $rules["name.{$locale}"] = ['required', 'string', 'max:255', Rule::unique('pages', "name->{$locale}")];

            // Page meta fields
            $rules["meta_title.{$locale}"] = ['nullable', 'string', 'max:255'];
            $rules["meta_description.{$locale}"] = ['nullable', 'string', 'max:255'];
            $rules["meta_keywords.{$locale}"] = ['nullable', 'string', 'max:255'];
        }

        // Sections
        $rules['sections'] = ['nullable', 'array'];

        foreach ($locales as $locale) {
            // Section fields
            $rules["sections.*.name.{$locale}"] = ['required', 'string', 'max:255'];
            $rules["sections.*.meta_title.{$locale}"] = ['nullable', 'string', 'max:255'];
            $rules["sections.*.meta_description.{$locale}"] = ['nullable', 'string', 'max:255'];
            $rules["sections.*.meta_keywords.{$locale}"] = ['nullable', 'string', 'max:255'];
            $rules["sections.*.button_url.{$locale}"] = ['nullable', 'string', 'max:255'];
            $rules["sections.*.button_text.{$locale}"] = ['nullable', 'string', 'max:255'];
            $rules["sections.*.content.title.{$locale}"] = ['nullable', 'string', 'max:255'];
            $rules["sections.*.content.subtitle.{$locale}"] = ['nullable', 'string', 'max:255'];
            $rules["sections.*.content.description.{$locale}"] = ['nullable', 'string'];

            // Subsection fields
            $rules["sections.*.subsections.*.name.{$locale}"] = ['required', 'string', 'max:255'];
            $rules["sections.*.subsections.*.meta_title.{$locale}"] = ['nullable', 'string', 'max:255'];
            $rules["sections.*.subsections.*.meta_description.{$locale}"] = ['nullable', 'string', 'max:255'];
            $rules["sections.*.subsections.*.meta_keywords.{$locale}"] = ['nullable', 'string', 'max:255'];
            $rules["sections.*.subsections.*.button_url.{$locale}"] = ['nullable', 'string', 'max:255'];
            $rules["sections.*.subsections.*.button_text.{$locale}"] = ['nullable', 'string', 'max:255'];
            $rules["sections.*.subsections.*.content.title.{$locale}"] = ['nullable', 'string', 'max:255'];
            $rules["sections.*.subsections.*.content.subtitle.{$locale}"] = ['nullable', 'string', 'max:255'];
            $rules["sections.*.subsections.*.content.description.{$locale}"] = ['nullable', 'string'];
        }

        // Non-language specific section rules
        $rules = array_merge($rules, [
            'sections.*.button_type' => ['nullable', Rule::in(ButtonTypeEnum::values())],
            'sections.*.is_active' => ['required', 'integer', Rule::in(StatusEnum::values())],
            'sections.*.type' => ['required', 'integer', Rule::in(SectionTypeEnum::values())],

            // Media
            'sections.*.image.desktop' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'sections.*.image.mobile' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'sections.*.video.desktop' => ['nullable', 'file', 'mimetypes:video/mp4,video/webm', 'max:10240'],
            'sections.*.video.mobile' => ['nullable', 'file', 'mimetypes:video/mp4,video/webm', 'max:10240'],
            'sections.*.video.poster.desktop' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'sections.*.video.poster.mobile' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'sections.*.file' => ['nullable', 'file', 'max:5120'],
            'sections.*.icon' => ['nullable', 'file', 'mimes:svg,png,jpg,jpeg,webp', 'max:1024'],
            'sections.*.gallery.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],

            // Subsections
            'sections.*.subsections' => ['nullable', 'array'],
            'sections.*.subsections.*.button_type' => ['nullable', Rule::in(ButtonTypeEnum::values())],
            'sections.*.subsections.*.is_active' => ['required', 'integer', Rule::in(StatusEnum::values())],
            'sections.*.subsections.*.type' => ['required', 'integer', Rule::in(SectionTypeEnum::values())],
            'sections.*.subsections.*.image.desktop' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'sections.*.subsections.*.image.mobile' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'sections.*.subsections.*.video.desktop' => ['nullable', 'file', 'mimetypes:video/mp4,video/webm', 'max:10240'],
            'sections.*.subsections.*.video.mobile' => ['nullable', 'file', 'mimetypes:video/mp4,video/webm', 'max:10240'],
            'sections.*.subsections.*.video.poster.desktop' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'sections.*.subsections.*.video.poster.mobile' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'sections.*.subsections.*.file' => ['nullable', 'file', 'max:5120'],
            'sections.*.subsections.*.icon' => ['nullable', 'file', 'mimes:svg,png,jpg,jpeg,webp', 'max:1024'],
            'sections.*.subsections.*.gallery.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        return $rules;
    }

    public function messages(): array
    {
        $messages = [];
        $locales = LaravelLocalization::getSupportedLanguagesKeys();

        foreach ($locales as $locale) {
            // Page messages
            $messages["name.{$locale}.required"] = __('custom.validation.name_' . $locale . '.required');
            $messages["name.{$locale}.unique"] = __('custom.validation.name_' . $locale . '.unique');

            // Section messages
            $messages["sections.*.name.{$locale}.required"] = __('custom.validation.name_' . $locale . '.required');
        }

        $messages['is_active.required'] = __('custom.validation.is_active.required');
        $messages['is_active.in'] = __('custom.validation.is_active.in');

        return $messages;
    }
}
