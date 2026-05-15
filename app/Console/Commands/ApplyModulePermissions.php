<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ApplyModulePermissions extends Command
{
    protected $signature = 'permissions:apply-modules';
    protected $description = 'Apply permission middleware to all module routes';

    public function handle()
    {
        $modulesPath = base_path('modules');
        $modules = array_filter(scandir($modulesPath), function ($item) use ($modulesPath) {
            return $item !== '.' && $item !== '..' && is_dir($modulesPath . '/' . $item);
        });

        foreach ($modules as $module) {
            $this->info("Processing module: {$module}");
            $this->applyPermissionsToModule($module);
        }

        $this->info('Permissions have been applied to all modules!');
        return 0;
    }

    private function applyPermissionsToModule($moduleName)
    {
        $modulePath = base_path("modules/{$moduleName}");
        $routesPath = $modulePath . '/routes/web.php';

        if (!File::exists($routesPath)) {
            $this->warn("No web.php routes file found for module: {$moduleName}");
            return;
        }

        $content = File::get($routesPath);
        
        // Define permission mapping for common routes
        $permissionMap = [
            'index' => 'read',
            'list' => 'read', 
            'add' => 'create',
            'store' => 'create',
            'edit' => 'update',
            'update' => 'update',
            'delete' => 'delete',
        ];

        $moduleLower = strtolower($moduleName);

        // Apply permissions to routes
        foreach ($permissionMap as $method => $action) {
            $pattern = "/Route::(get|post|delete)\s*\(\s*['\"]([^'\"]*{$method}[^'\"]*)['\"]/";
            $replacement = "Route::$1('$2')->middleware('permission:{$moduleLower}_{$action}')";
            $content = preg_replace($pattern, $replacement, $content);
        }

        // Handle routes with parameters
        $patterns = [
            "/Route::(get|post|put|patch|delete)\s*\(\s*['\"]([^'\"]*\/\{[^}]+\}[^'\"]*)['\"]/" => function($matches) use ($moduleLower) {
                $method = $matches[1];
                $route = $matches[2];
                
                // Determine permission based on HTTP method
                if (in_array($method, ['get']) && str_contains($route, 'edit')) {
                    $permission = "{$moduleLower}_update";
                } elseif (in_array($method, ['post', 'put', 'patch'])) {
                    $permission = "{$moduleLower}_update";
                } elseif ($method === 'delete') {
                    $permission = "{$moduleLower}_delete";
                } else {
                    $permission = "{$moduleLower}_read";
                }
                
                return "Route::{$method}('{$route}')->middleware('permission:{$permission}')";
            }
        ];

        foreach ($patterns as $pattern => $callback) {
            $content = preg_replace_callback($pattern, $callback, $content);
        }

        File::put($routesPath, $content);
        $this->line("  ✓ Applied permissions to {$moduleName} routes");
    }
}
