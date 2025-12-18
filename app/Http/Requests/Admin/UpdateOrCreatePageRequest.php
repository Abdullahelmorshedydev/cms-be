<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrCreatePageRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $page = $this->route('page');
        $pageId = $page?->id ?? null;
        
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                $pageId ? "unique:pages,name,{$pageId}" : 'unique:pages,name'
            ],
            'is_active' => ['nullable', 'boolean', 'in:0,1'],
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
