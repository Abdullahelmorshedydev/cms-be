<?php

namespace App\Http\Requests\Blog;

use App\Enums\StatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class UpdateBlogRequest extends FormRequest
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
        $blogSlug = $this->route('blog'); // Get the blog slug from route parameter

        $rules = [
            'is_active'    => ['required', 'integer', Rule::in(StatusEnum::values())],
            'created_by'   => ['nullable', 'integer', 'exists:users,id'],
            'published_at' => ['nullable', 'date'],
            'image'        => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ];

        // Dynamic language-based validation
        $locales = LaravelLocalization::getSupportedLanguagesKeys();

        foreach ($locales as $locale) {
            // Blog title - unique except for current blog
            $rules["title.{$locale}"] = [
                'required',
                'string',
                'max:255',
                Rule::unique('blogs', "title->{$locale}")
                    ->ignore($blogSlug, 'slug')
            ];

            // Blog content
            $rules["content.{$locale}"] = ['required', 'string'];

            // Blog excerpt
            $rules["excerpt.{$locale}"] = ['nullable', 'string', 'max:500'];

            // Meta fields
            $rules["meta_title.{$locale}"] = ['nullable', 'string', 'max:255'];
            $rules["meta_description.{$locale}"] = ['nullable', 'string', 'max:255'];
            $rules["meta_keywords.{$locale}"] = ['nullable', 'string', 'max:255'];
        }

        return $rules;
    }

    public function messages(): array
    {
        $messages = [];
        $locales = LaravelLocalization::getSupportedLanguagesKeys();

        foreach ($locales as $locale) {
            $messages["title.{$locale}.required"] = __('custom.validation.title_' . $locale . '.required');
            $messages["title.{$locale}.unique"] = __('custom.validation.title_' . $locale . '.unique');
            $messages["content.{$locale}.required"] = __('custom.validation.content_' . $locale . '.required');
        }

        $messages['is_active.required'] = __('custom.validation.is_active.required');
        $messages['is_active.in'] = __('custom.validation.is_active.in');
        $messages['created_by.exists'] = __('custom.validation.created_by.exists');

        return $messages;
    }
}

