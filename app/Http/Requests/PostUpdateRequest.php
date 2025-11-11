<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PostUpdateRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('posts')->ignore($this->route('post'))],
            'content' => ['nullable', 'string'], // Made nullable - can use Page Builder instead
            'page_builder_content' => ['nullable', 'json'], // Page Builder JSON content
            'use_page_builder' => ['nullable', 'boolean'], // Flag for Page Builder mode
            'excerpt' => ['nullable', 'string'],
            'featured_image' => ['nullable', 'string'],
            'status' => ['required', 'in:draft,published'],
            'published_at' => ['nullable', 'date'],
            'categories' => ['sometimes', 'array'],
            'categories.*' => ['exists:categories,id'],
            'tags' => ['nullable', 'string'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
        ];
    }
} 