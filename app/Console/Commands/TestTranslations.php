<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestTranslations extends Command
{
    protected $signature = 'test:translations';
    protected $description = 'Test translations loading';

    public function handle()
    {
        $this->info('Testing translations...');
        
        $this->info("\nItalian translations:");
        $this->info('admin.vehicles.edit_vehicle: ' . __('admin.vehicles.edit_vehicle'));
        $this->info('admin.vehicles.make: ' . __('admin.vehicles.make'));
        $this->info('admin.vehicles.model: ' . __('admin.vehicles.model'));
        $this->info('admin.vehicles.select_make: ' . __('admin.vehicles.select_make'));
        
        $this->info("\nEnglish translations:");
        $this->info('admin.vehicles.edit_vehicle: ' . __('admin.vehicles.edit_vehicle', [], 'en'));
        $this->info('admin.vehicles.make: ' . __('admin.vehicles.make', [], 'en'));
        $this->info('admin.vehicles.model: ' . __('admin.vehicles.model', [], 'en'));
        $this->info('admin.vehicles.select_make: ' . __('admin.vehicles.select_make', [], 'en'));
    }
} 