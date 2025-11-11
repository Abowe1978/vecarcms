<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MediaUploadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Se necessario, puoi aggiungere qui la logica di autorizzazione
        // Per esempio: return $this->user()->can('upload_media');
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'file' => 'required|image|max:10240', // Max 10MB
            'source' => 'nullable|string'
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
            'file.required' => 'You must select a file to upload.',
            'file.image' => 'The uploaded file must be an image.',
            'file.max' => 'The image size must not exceed 10MB.',
        ];
    }
} 