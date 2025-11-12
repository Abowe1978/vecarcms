<?php

namespace App\Widgets;

class NewsletterWidget extends AbstractWidget
{
    public function getName(): string
    {
        return 'Newsletter Signup';
    }

    public function getDescription(): string
    {
        return 'Embed a newsletter subscription form.';
    }

    public function getFormFields(): array
    {
        return [
            [
                'name' => 'heading',
                'label' => 'Heading',
                'type' => 'text',
                'default' => 'Stay in the loop',
            ],
            [
                'name' => 'description',
                'label' => 'Description',
                'type' => 'textarea',
                'default' => 'Subscribe to receive the latest news, updates and special offers directly in your inbox.',
                'rows' => 3,
            ],
            [
                'name' => 'placeholder',
                'label' => 'Email Placeholder',
                'type' => 'text',
                'default' => 'Enter your email address',
            ],
            [
                'name' => 'button_text',
                'label' => 'Button Text',
                'type' => 'text',
                'default' => 'Subscribe',
            ],
        ];
    }

    public function render(): string
    {
        $heading = $this->getTitle() ?? (string) $this->getSetting('heading', 'Stay in the loop');

        return view('components.widgets.newsletter', [
            'title' => $heading,
            'description' => (string) $this->getSetting('description', 'Subscribe to receive the latest news and updates.'),
            'placeholder' => (string) $this->getSetting('placeholder', 'Enter your email address'),
            'buttonText' => (string) $this->getSetting('button_text', 'Subscribe'),
        ])->render();
    }
}


