<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Check if comments are enabled
        return settings('comments_enabled', true);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'commentable_type' => 'required|string|in:App\Models\Post,App\Models\Page',
            'commentable_id' => 'required|integer',
            'content' => 'required|string|min:3|max:5000',
            'parent_id' => 'nullable|exists:comments,id',
        ];

        // If user is not authenticated and guest comments are allowed
        if (!auth()->check()) {
            $guestCommentsAllowed = settings('comments_allow_guests', false);
            
            if (!$guestCommentsAllowed) {
                return []; // No validation - will fail on authorize
            }

            $rules['author_name'] = 'required|string|max:255';
            $rules['author_email'] = 'required|email|max:255';
            $rules['author_url'] = 'nullable|url|max:255';
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'commentable_type.required' => 'Tipo di contenuto mancante.',
            'commentable_id.required' => 'ID contenuto mancante.',
            'content.required' => 'Il contenuto del commento è obbligatorio.',
            'content.min' => 'Il commento deve essere di almeno 3 caratteri.',
            'content.max' => 'Il commento non può superare i 5000 caratteri.',
            'author_name.required' => 'Il nome è obbligatorio.',
            'author_email.required' => 'L\'email è obbligatoria.',
            'author_email.email' => 'Inserisci un\'email valida.',
        ];
    }
}
