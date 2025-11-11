<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Core\ModuleManager;

class UpdateIntegrationConfigRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Puoi aggiungere qui la logica di autorizzazione se serve
        return true;
    }

    public function rules(): array
    {
        $name = $this->route('name');
        $module = app(ModuleManager::class)->get($name);
        $fields = $module ? $module->getConfigFields() : [];
        $rules = [];

        foreach ($fields as $field => $options) {
            $fieldRules = [];
            if (!empty($options['required'])) {
                $fieldRules[] = 'required';
            } else {
                $fieldRules[] = 'nullable';
            }
            switch ($options['type']) {
                case 'text':
                case 'password':
                    $fieldRules[] = 'string';
                    break;
                case 'select':
                    $fieldRules[] = 'string';
                    if (!empty($options['options']) && is_array($options['options'])) {
                        $fieldRules[] = 'in:' . implode(',', array_keys($options['options']));
                    }
                    break;
                case 'checkbox':
                    $fieldRules[] = 'boolean';
                    break;
            }
            $rules["config.$field"] = $fieldRules;
        }
        return $rules;
    }

    public function messages(): array
    {
        return [
            // Qui puoi aggiungere messaggi custom se vuoi
        ];
    }
} 