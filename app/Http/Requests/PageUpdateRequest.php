<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PageUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('manage_pages');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|max:255',
            'slug' => 'nullable|max:255|unique:pages,slug,' . $this->page->id,
            'content' => 'nullable',
            'page_builder_content' => 'nullable|json', // Page Builder JSON content
            'use_page_builder' => 'nullable|boolean', // Flag for Page Builder mode
            'featured_image' => 'nullable|string',
            'template' => 'required',
            'meta_title' => 'nullable|max:255',
            'meta_description' => 'nullable|max:255',
            'meta_keywords' => 'nullable|max:255',
            'order' => 'nullable|integer',
            'parent_id' => 'nullable|exists:pages,id',
            'show_title' => 'nullable|boolean',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'title.required' => __('admin.pages.title_required'),
            'title.max' => __('admin.pages.title_max'),
            'slug.unique' => __('admin.pages.slug_unique'),
            'template.required' => __('admin.pages.template_required'),
            'parent_id.exists' => __('admin.pages.parent_invalid'),
        ];
    }
}
