<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class FixModuleRoutes extends Command
{
    protected $signature = 'routes:fix-modules';
    protected $description = 'Fix syntax errors in module route files';

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

        $content = File::get($routesPath);
        
        // Fix the syntax errors by correcting the middleware placement
        $patterns = [
            // Fix: Route::get('/add')->middleware('permission:blog_create'), [Controller::class, 'add']
            '/Route::(get|post|put|patch|delete)\s*\(\s*[\'"]([^\'"]*)[\'"]\s*\)\s*->middleware\([\'"]([^\'"]*)[\'"]\)\s*,\s*\[([^\]]*)\]/' => 'Route::$1(\'$2\', [$4])->middleware(\'permission:$3\')',
            
            // Fix: Route::get('/edit/{id}')->middleware('permission:blog_update'))->middleware('permission:blog_update'), [Controller::class, 'edit']
            '/Route::(get|post|put|patch|delete)\s*\(\s*[\'"]([^\'"]*)[\'"]\s*\)\s*->middleware\([\'"]([^\'"]*)[\'"]\)\s*\)\s*->middleware\([\'"]([^\'"]*)[\'"]\)\s*,\s*\[([^\]]*)\]/' => 'Route::$1(\'$2\', [$5])->middleware(\'permission:$3\')',
        ];

        foreach ($patterns as $pattern => $replacement) {
            $content = preg_replace($pattern, $replacement, $content);
        }

        File::put($routesPath, $content);
        $this->line("  ✓ Fixed routes for {$moduleName}");
    }
}
