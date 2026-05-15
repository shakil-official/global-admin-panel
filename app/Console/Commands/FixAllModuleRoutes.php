<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class FixAllModuleRoutes extends Command
{
    protected $signature = 'routes:fix-all';
    protected $description = 'Fix all module route files with proper permission middleware';

    public function handle()
    {
        $modulesPath = base_path('modules');
        $modules = array_filter(scandir($modulesPath), function ($item) use ($modulesPath) {
            return $item !== '.' && $item !== '..' && is_dir($modulesPath . '/' . $item);
        });

        foreach ($modules as $module) {
            $this->info("Fixing routes for module: {$module}");
            $this->fixModuleRoutes($module);
        }

        $this->info('All module routes have been fixed!');
        return 0;
    }

    private function fixModuleRoutes($moduleName)
    {
        $modulePath = base_path("modules/{$moduleName}");
        $routesPath = $modulePath . '/routes/web.php';

        if (!File::exists($routesPath)) {
            $this->warn("No web.php routes file found for module: {$moduleName}");
            return;
        }

        $moduleLower = strtolower($moduleName);
        
        // Create the corrected route content
        $content = "<?php\n\nuse Illuminate\Support\Facades\Route;\nuse Modules\\{$moduleName}\\Http\Controllers\\{$moduleName}Controller;\n\n";
        $content .= "Route::middleware(['web','auth', 'verified'])->prefix('/{$moduleLower}')->group(function () {\n";
        $content .= "    Route::get('/', [{$moduleName}Controller::class, 'index'])->middleware('permission:{$moduleLower}_read')->name('{$moduleLower}.index');\n";
        $content .= "    Route::get('/add', [{$moduleName}Controller::class, 'add'])->middleware('permission:{$moduleLower}_create')->name('{$moduleLower}.add');\n";
        $content .= "    Route::post('/store', [{$moduleName}Controller::class, 'store'])->middleware('permission:{$moduleLower}_create')->name('{$moduleLower}.store');\n";
        $content .= "    Route::get('/list', [{$moduleName}Controller::class, 'dataTableList'])->middleware('permission:{$moduleLower}_read')->name('{$moduleLower}.list');\n";
        $content .= "    Route::get('/edit/{id}', [{$moduleName}Controller::class, 'edit'])->middleware('permission:{$moduleLower}_update')->name('{$moduleLower}.edit');\n";
        $content .= "    Route::post('/update/{id}', [{$moduleName}Controller::class, 'update'])->middleware('permission:{$moduleLower}_update')->name('{$moduleLower}.update');\n";
        $content .= "    Route::delete('/delete', [{$moduleName}Controller::class, 'delete'])->middleware('permission:{$moduleLower}_delete')->name('{$moduleLower}.delete');\n";
        $content .= "});\n";

        File::put($routesPath, $content);
        $this->line("  ✓ Fixed routes for {$moduleName}");
    }
}
