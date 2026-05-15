<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

class ModuleScannerService
{
    /**
     * Get all modules from the modules directory
     */
    public function getAllModules(): array
    {
        $modulesPath = base_path('modules');
        $modules = [];

        if (!File::exists($modulesPath)) {
            return $modules;
        }

        $moduleDirectories = File::directories($modulesPath);

        foreach ($moduleDirectories as $modulePath) {
            $moduleName = basename($modulePath);
            $modules[$moduleName] = $this->getModuleDetails($moduleName, $modulePath);
        }

        return $modules;
    }

    /**
     * Get module details including routes and permissions
     */
    private function getModuleDetails(string $moduleName, string $modulePath): array
    {
        return [
            'name' => $moduleName,
            'path' => $modulePath,
            'routes' => $this->getModuleRoutes($moduleName),
            'permissions' => $this->generateModulePermissions($moduleName),
            'controller' => $this->getModuleController($moduleName)
        ];
    }

    /**
     * Get routes for a specific module
     */
    private function getModuleRoutes(string $moduleName): array
    {
        $routes = [];
        $routeFiles = [
            base_path("modules/{$moduleName}/routes/web.php"),
            base_path("modules/{$moduleName}/routes/api.php")
        ];

        foreach ($routeFiles as $routeFile) {
            if (File::exists($routeFile)) {
                $routeContent = File::get($routeFile);
                $routes = array_merge($routes, $this->parseRoutesFromFile($routeContent, $moduleName));
            }
        }

        return $routes;
    }

    /**
     * Parse routes from file content
     */
    private function parseRoutesFromFile(string $content, string $moduleName): array
    {
        $routes = [];
        $lines = explode("\n", $content);

        foreach ($lines as $line) {
            if (preg_match('/Route::(get|post|put|patch|delete)\s*\(\s*[\'"]([^\'"]+)[\'"]/', $line, $matches)) {
                $method = strtoupper($matches[1]);
                $route = $matches[2];
                
                // Clean route parameters
                $route = preg_replace('/\{[^}]+\}/', '*', $route);
                
                $routes[] = [
                    'method' => $method,
                    'uri' => $route,
                    'permission_name' => $this->generatePermissionName($moduleName, $route, $method)
                ];
            }
        }

        return $routes;
    }

    /**
     * Generate permission name for a route
     */
    private function generatePermissionName(string $moduleName, string $route, string $method): string
    {
        // Convert route to permission format
        $route = str_replace('/', '.', trim($route, '/'));
        $route = str_replace('*', 'any', $route);
        
        // Map HTTP methods to actions
        $action = match($method) {
            'GET' => 'view',
            'POST' => 'create',
            'PUT', 'PATCH' => 'update',
            'DELETE' => 'delete',
            default => 'access'
        };

        // Handle special routes
        if (str_contains($route, 'add') || str_contains($route, 'create')) {
            $action = 'create';
        } elseif (str_contains($route, 'edit') || str_contains($route, 'update')) {
            $action = 'update';
        } elseif (str_contains($route, 'delete') || str_contains($route, 'destroy')) {
            $action = 'delete';
        } elseif (str_contains($route, 'list') || str_contains($route, 'index')) {
            $action = 'view';
        }

        return strtolower($moduleName . '.' . ($route ?: 'index') . '.' . $action);
    }

    /**
     * Generate all possible permissions for a module
     */
    private function generateModulePermissions(string $moduleName): array
    {
        $moduleNameLower = strtolower($moduleName);
        
        // Generate permissions based on actual module routes
        $permissions = [
            $moduleNameLower . '.view',
            $moduleNameLower . '.create',
            $moduleNameLower . '.update',
            $moduleNameLower . '.delete'
        ];

        return array_unique($permissions);
    }

    /**
     * Get module controller class name
     */
    private function getModuleController(string $moduleName): ?string
    {
        $controllerPath = base_path("modules/{$moduleName}/Http/Controllers");
        
        if (!File::exists($controllerPath)) {
            return null;
        }

        $controllerFiles = File::files($controllerPath);
        
        foreach ($controllerFiles as $file) {
            if ($file->getExtension() === 'php') {
                $className = $file->getFilenameWithoutExtension();
                return "Modules\\{$moduleName}\\Http\\Controllers\\{$className}";
            }
        }

        return null;
    }

    /**
     * Get permissions grouped by module
     */
    public function getPermissionsByModule(): array
    {
        $modules = $this->getAllModules();
        $groupedPermissions = [];

        foreach ($modules as $moduleName => $moduleDetails) {
            $groupedPermissions[$moduleName] = [
                'name' => $moduleName,
                'permissions' => $moduleDetails['permissions'],
                'routes' => $moduleDetails['routes']
            ];
        }

        return $groupedPermissions;
    }

    /**
     * Get all available permissions across all modules
     */
    public function getAllAvailablePermissions(): array
    {
        $modules = $this->getAllModules();
        $allPermissions = [];

        foreach ($modules as $moduleName => $moduleDetails) {
            $allPermissions = array_merge($allPermissions, $moduleDetails['permissions']);
        }

        return array_unique($allPermissions);
    }
}
