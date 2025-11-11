<?php

namespace Themes\DwnTheme\Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;

class DWNThemeContactPageSeeder extends Seeder
{
    public function run(): void
    {
        $contactContent = trim(<<<'HTML'
<p>We're here to help you kickstart your next project. Fill in the form or reach us directly using the details below and we'll get back to you shortly.</p>
HTML);

        Page::updateOrCreate(
            ['slug' => 'contact'],
            [
                'title' => 'Contact',
                'content' => $contactContent,
                'template' => 'contact',
                'is_published' => true,
                'use_page_builder' => false,
                'meta_description' => 'Contact the VeCarCMS team for product enquiries, demos, or support.',
            ]
        );
    }
}

