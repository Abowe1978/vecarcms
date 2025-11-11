<?php

namespace App\Console\Commands;

use App\Models\MenuItem;
use Illuminate\Console\Command;

class CleanOrphanedMenuItems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'menu:clean-orphaned';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove menu items with NULL menu_id';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('=== Checking for Menu Items with NULL menu_id ===');
        
        $nullItems = MenuItem::whereNull('menu_id')->get();
        
        $this->info("Found {$nullItems->count()} items with NULL menu_id:");
        
        foreach ($nullItems as $item) {
            $this->line("- ID: {$item->id} | Title: {$item->title}");
        }
        
        if ($nullItems->count() > 0) {
            $this->newLine();
            
            if ($this->confirm('Do you want to delete these orphaned items?', true)) {
                foreach ($nullItems as $item) {
                    $item->forceDelete();
                    $this->info("✓ Deleted item ID: {$item->id}");
                }
                
                $this->newLine();
                $this->info('✅ All orphaned items removed!');
            } else {
                $this->warn('Cancelled.');
            }
        } else {
            $this->info('✅ No orphaned items found!');
        }
        
        return Command::SUCCESS;
    }
}

