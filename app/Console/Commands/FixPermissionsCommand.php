<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class FixPermissionsCommand extends Command
{
    protected $signature = 'permissions:fix';
    protected $description = 'Fix all permission issues by running seeders';

    public function handle()
    {
        $this->info('Fixing all permission issues...');
        
        // Clean permissions to match routes exactly
        $this->call('permissions:clean');
        
        $this->info('All permission issues have been fixed!');
        $this->info('Please clear your browser cache and refresh the page.');
        
        return Command::SUCCESS;
    }
}
