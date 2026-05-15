<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class DisableModuleProviders extends Command
{
    protected $signature = 'modules:disable-providers';
    protected $description = 'Temporarily disable module service providers to run seeders';

    public function handle()
    {
        $modulesPath = base_path('modules');
        $modules = array_filter(scandir($modulesPath), function ($item) use ($modulesPath) {
            return $item !== '.' && $item !== '..' && is_dir($modulesPath . '/' . $item);
        });

        foreach ($modules as $module) {
            $providerPath = $modulesPath . "/{$module}/{$module}ServiceProvider.php";
            if (File::exists($providerPath)) {
                $backupPath = $providerPath . '.backup';
                File::copy($providerPath, $backupPath);
                File::delete($providerPath);
                $this->line("  ✓ Disabled {$module} provider");
            }
        }

        $this->info('All module providers have been disabled temporarily');
        return 0;
    }
}
