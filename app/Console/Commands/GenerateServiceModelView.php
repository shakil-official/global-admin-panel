<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GenerateServiceModelView extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:smv {modelName?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates the service, model, and view structure for a given model.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {

        // Check if model name is provided
        $modelName = $this->argument('modelName');

        if (!$modelName) {
            $this->error('You must provide a model name.');
            return;
        }

        // Capitalize the model name if it's in lowercase or mixed case
        $modelName = Str::studly($modelName);  // Converts to PascalCase (e.g., 'user' -> 'User')

        // Ensure necessary directories and files exist
        $this->ensureBaseDirectoriesExist();
        $this->ensureModelSpecificDirectoriesExist($modelName);

        // Create repository, service, and model files
        $this->createRepository($modelName);
        $this->createService($modelName);

        // Optionally create model file
        $this->createModelAndMigration($modelName);

        $this->createController($modelName);

        $this->createRequestClass($modelName);

        $this->viewGenerate();


        $this->info("Service, model, and view structure for {$modelName} created successfully.");
    }

    /**
     * Ensure the base directories for repositories and services exist.
     */
    protected function ensureBaseDirectoriesExist(): void
    {
        // Ensure that the base directories exist
        $baseRepositoryDir = app_path('Engine/Base/Repositories');
        $baseServiceDir = app_path('Engine/Base/Services');

        if (!File::exists($baseRepositoryDir)) {
            File::makeDirectory($baseRepositoryDir, 0755, true);
            $this->info('Base repository directory created.');
        }

        if (!File::exists($baseServiceDir)) {
            File::makeDirectory($baseServiceDir, 0755, true);
            $this->info('Base service directory created.');
        }

        // Generate base repository and service files if not exist
        $this->generateBaseRepositoryInterface();
        $this->generateBaseRepository();
        $this->generateBaseServiceInterface();
        $this->generateBaseService();
    }

    /**
     * Ensure model-specific directories exist.
     *
     * @param string $modelName
     * @return void
     */
    protected function ensureModelSpecificDirectoriesExist(string $modelName): void
    {
        $modelPath = app_path("Engine/{$modelName}");
        $repositoriesPath = app_path("Engine/{$modelName}/Repositories");
        $servicesPath = app_path("Engine/{$modelName}/Services");
        $contractsPath = app_path("Engine/{$modelName}/Repositories/Contracts");

        // Create model-specific directories if they don't exist
        if (!File::exists($modelPath)) {
            File::makeDirectory($modelPath, 0755, true);
        }

        if (!File::exists($repositoriesPath)) {
            File::makeDirectory($repositoriesPath, 0755, true);
        }

        if (!File::exists($servicesPath)) {
            File::makeDirectory($servicesPath, 0755, true);
        }

        if (!File::exists($contractsPath)) {
            File::makeDirectory($contractsPath, 0755, true);
        }

        // Ensure subdirectories within Repositories and Services exist
        $this->ensureRepositoryDirectoriesExist($modelName);
        $this->ensureServiceDirectoriesExist($modelName);
    }

    /**
     * Ensure the subdirectories for repositories exist.
     *
     * @param string $modelName
     * @return void
     */
    protected function ensureRepositoryDirectoriesExist(string $modelName): void
    {
        // Ensure the "Repositories/Contracts" and "Repositories/Eloquent" directories exist
        $contractsDir = app_path("Engine/{$modelName}/Repositories/Contracts");
        $eloquentDir = app_path("Engine/{$modelName}/Repositories/Eloquent");

        if (!File::exists($contractsDir)) {
            File::makeDirectory($contractsDir, 0755, true);
        }

        if (!File::exists($eloquentDir)) {
            File::makeDirectory($eloquentDir, 0755, true);
        }
    }

    /**
     * Ensure the subdirectories for services exist.
     *
     * @param string $modelName
     * @return void
     */
    protected function ensureServiceDirectoriesExist(string $modelName): void
    {
        $servicesContractsDir = app_path("Engine/{$modelName}/Services/Contracts");

        if (!File::exists($servicesContractsDir)) {
            File::makeDirectory($servicesContractsDir, 0755, true);
        }
    }

    /**
     * Generate the repository interface file.
     *
     * @param string $modelName
     * @return void
     */
    protected function createRepository(string $modelName): void
    {
        $repositoryInterfacePath = app_path("Engine/{$modelName}/Repositories/Contracts/{$modelName}RepositoryInterface.php");

        if (!file_exists($repositoryInterfacePath)) {
            $stub = file_get_contents(base_path('stubs/repository.interface.stub'));
            $stub = str_replace('{{modelName}}', $modelName, $stub);
            file_put_contents($repositoryInterfacePath, $stub);
            $this->info("Repository interface for {$modelName} created.");
        }

        // Create the repository implementation
        $this->createRepositoryImplementation($modelName);
    }

    /**
     * Generate the repository implementation file.
     *
     * @param string $modelName
     * @return void
     */
    protected function createRepositoryImplementation(string $modelName): void
    {
        $repositoryPath = app_path("Engine/{$modelName}/Repositories/Eloquent/{$modelName}Repository.php");

        if (!file_exists($repositoryPath)) {
            $stub = file_get_contents(base_path('stubs/repository.implementation.stub'));
            $stub = str_replace('{{modelName}}', $modelName, $stub);
            file_put_contents($repositoryPath, $stub);
            $this->info("Repository implementation for {$modelName} created.");
        }
    }

    /**
     * Generate the service interface file.
     *
     * @param string $modelName
     * @return void
     */
    protected function createService(string $modelName): void
    {
        $serviceInterfacePath = app_path("Engine/{$modelName}/Services/Contracts/{$modelName}ServiceInterface.php");

        if (!file_exists($serviceInterfacePath)) {
            $stub = file_get_contents(base_path('stubs/service.interface.stub'));
            $stub = str_replace('{{modelName}}', $modelName, $stub);
            file_put_contents($serviceInterfacePath, $stub);
            $this->info("Service interface for {$modelName} created.");
        }

        // Create the service implementation
        $this->createServiceImplementation($modelName);
    }

    /**
     * Generate the service implementation file.
     *
     * @param string $modelName
     * @return void
     */
    protected function createServiceImplementation(string $modelName): void
    {
        $servicePath = app_path("Engine/{$modelName}/Services/{$modelName}Service.php");

        if (!file_exists($servicePath)) {
            $stub = file_get_contents(base_path('stubs/service.implementation.stub'));
            $stub = str_replace('{{modelName}}', $modelName, $stub);
            file_put_contents($servicePath, $stub);
            $this->info("Service implementation for {$modelName} created.");
        }
    }

    /**
     * Create the model and its migration file if needed.
     *
     * @param string $modelName
     * @return void
     */
    protected function createModelAndMigration(string $modelName): void
    {
        // Check if the model already exists
        $modelPath = app_path("Models/{$modelName}.php");

        if (!file_exists($modelPath)) {
            // Run the make:model command with the -m flag to create both the model and the migration
            $this->info("Creating model and migration for {$modelName}...");

            // Call the make:model command
            Artisan::call('make:model', [
                'name' => $modelName,
                '--migration' => true, // Creates the migration file along with the model
            ]);

            // Output result
            $this->info(Artisan::output());  // This will print the output of the artisan command

            $this->info("Model and migration for {$modelName} created successfully.");
        } else {
            $this->error("Model for {$modelName} already exists.");
        }
    }


    /**
     * Create a validation request class for the model.
     *
     * @param string $modelName
     * @return void
     */
    protected function createRequestClass(string $modelName): void
    {
        $requestName = "{$modelName}Request";
        $requestPath = app_path("Http/Requests/{$requestName}.php");

        if (!file_exists($requestPath)) {
            // Run the make:request command
            $this->call('make:request', [
                'name' => "{$requestName}",
            ]);

            $this->info("Validation request {$requestName} created successfully.");
        } else {
            $this->error("Validation request {$requestName} already exists.");
        }
    }


    /**
     * Generate the base repository interface if not exist.
     */
    private function generateBaseRepositoryInterface(): void
    {
        $path = app_path('Engine/Base/Repositories/BaseRepositoryInterface.php');
        if (!file_exists($path)) {
            $stub = file_get_contents(base_path('stubs/base.repository.interface.stub'));
            file_put_contents($path, $stub);
            $this->info('Base repository interface created.');
        }
    }

    /**
     * Generate the base repository if not exist.
     */
    private function generateBaseRepository(): void
    {
        $path = app_path('Engine/Base/Repositories/BaseRepository.php');
        if (!file_exists($path)) {
            $stub = file_get_contents(base_path('stubs/base.repository.stub'));
            file_put_contents($path, $stub);
            $this->info('Base repository created.');
        }
    }

    /**
     * Generate the base service interface if not exist.
     */
    private function generateBaseServiceInterface(): void
    {
        $path = app_path('Engine/Base/Services/BaseServiceInterface.php');
        if (!file_exists($path)) {
            $stub = file_get_contents(base_path('stubs/base.service.interface.stub'));
            file_put_contents($path, $stub);
            $this->info('Base service interface created.');
        }
    }

    /**
     * Generate the base service if not exist.
     */
    private function generateBaseService(): void
    {
        $path = app_path('Engine/Base/Services/BaseService.php');
        if (!file_exists($path)) {
            $stub = file_get_contents(base_path('stubs/base.service.stub'));
            file_put_contents($path, $stub);
            $this->info('Base service created.');
        }
    }


    /**
     * Create a controller for the model.
     *
     * @param string $modelName
     * @return void
     */
    protected function createController(string $modelName): void
    {
        $controllerName = "{$modelName}Controller";
        $controllerPath = app_path("Http/Controllers/{$modelName}/{$controllerName}.php");

        if (!file_exists($controllerPath)) {
            // Ensure the directory exists
            $directory = dirname($controllerPath);
            if (!is_dir($directory)) {
                File::makeDirectory($directory, 0755, true);
                $this->info("Controller directory created: {$directory}");
            }

            // Load the stub file for the controller
            $stub = file_get_contents(base_path('stubs/base.controller.stub'));


            $stub = str_replace('{{ucfirst($modelName)}}', ucfirst($modelName), $stub);
            $stub = str_replace('{{strtolower($modelName)}}', strtolower($modelName), $stub);
            // Replace the placeholder with the model name
            $stub = str_replace('{{modelName}}', $modelName, $stub);

            // Write the content to the controller file
            file_put_contents($controllerPath, $stub);
            $this->info("Controller {$controllerName} created successfully.");
        } else {
            $this->error("Controller {$controllerName} already exists.");
        }
    }

    public function viewGenerate(): void
    {
        $modelName = ucfirst($this->argument('modelName'));
        $modelNameLower = strtolower($modelName);

        $viewsDirectory = resource_path("views/backend/{$modelNameLower}");

        // Check if the folder exists, if not create it
        if (!File::exists($viewsDirectory)) {
            File::makeDirectory($viewsDirectory, 0755, true);
            $this->info("Directory created: {$viewsDirectory}");
        } else {
            $this->info("Directory already exists: {$viewsDirectory}");
        }

        // Define stub file paths and target file paths
        $stubFiles = [
            'index' => base_path('stubs/view.index.stub'),
            'edit' => base_path('stubs/view.edit.stub'),
            'add' => base_path('stubs/view.add.stub'),
        ];

        $viewFiles = [
            'index' => $viewsDirectory . '/index.blade.php',
            'edit' => $viewsDirectory . '/edit.blade.php',
            'add' => $viewsDirectory . '/add.blade.php',
        ];

        // Loop through each file to create and replace content
        foreach ($stubFiles as $key => $stubFile) {
            if (File::exists($stubFile)) {
                $content = File::get($stubFile);
                $content = str_replace('{{ $modelName }}', $modelName, $content);

                // Write the content to the corresponding view file
                File::put($viewFiles[$key], $content);
                $this->info("View file created: {$viewFiles[$key]}");
            } else {
                $this->error("Stub file does not exist: {$stubFile}");
            }
        }

        $this->info("Service, Model, and View files have been generated for {$modelName}.");
    }
}
