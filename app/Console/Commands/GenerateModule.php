<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class GenerateModule extends Command
{
    protected $signature = 'make:genModule {name}';
    protected $description = 'Generate full module with model, repository, service, controller, routes and views';

    public function handle(): int
    {
        $name = Str::studly($this->argument('name'));
        $lower = Str::kebab($name);

        $modulePath = base_path("modules/{$name}");

        if (File::exists($modulePath)) {
            $this->error("Module {$name} already exists.");
            return self::FAILURE;
        }

        $this->createDirectories($modulePath, $lower);
        $this->createFilesFromStubs($name, $lower);
        $this->createRoutes($name, $lower);
        $this->createServiceProvider($name);
        $this->createMigration($name, $lower); // ✅ ADD THIS

        $this->info("Module {$name} generated successfully.");
        return self::SUCCESS;
    }

    protected function createDirectories(string $base, string $lower): void
    {
        $dirs = [
            'Http/Controllers',
            'Models',
            'Repositories/Contracts',
            'Repositories/Eloquent',
            'Services/Contracts',
            'Services',
            'routes',
            'database/migrations',
            "resources/views/{$lower}",
        ];

        foreach ($dirs as $dir) {
            File::ensureDirectoryExists("{$base}/{$dir}");
        }
    }

    protected function createFilesFromStubs(string $name, string $lower): void
    {
        $stubBase = base_path('stubs');

        $map = [
            'modules.controller.stub'
            => "Http/Controllers/{$name}Controller.php",

            'modules.api.controller.stub'
            => "Http/Controllers/{$name}ApiController.php",


            'modules.repository.interface.stub'
            => "Repositories/Contracts/{$name}RepositoryInterface.php",

            'modules.repository.implementation.stub'
            => "Repositories/Eloquent/{$name}Repository.php",

            'modules.service.interface.stub'
            => "Services/Contracts/{$name}ServiceInterface.php",

            'modules.service.implementation.stub'
            => "Services/{$name}Service.php",

            'modules.view.index.stub'
            => "resources/views/{$lower}/index.blade.php",

            'modules.view.add.stub'
            => "resources/views/{$lower}/add.blade.php",

            'modules.view.edit.stub'
            => "resources/views/{$lower}/edit.blade.php",
        ];

        foreach ($map as $stub => $target) {

            $stubPath = "{$stubBase}/{$stub}";

            if (!File::exists($stubPath)) {
                $this->error("Missing stub file: {$stubPath}");
                exit(1);
            }

            $content = File::get($stubPath);

            $content = str_replace(
                [
                    '{{ Module }}',
                    '{{Module}}',
                    '{{ module }}',
                    '{{module}}',
                    '{{ moduleLower }}',
                ],
                [
                    $name,
                    $name,
                    $name,
                    $name,
                    $lower,
                ],
                $content
            );

            File::put(
                base_path("modules/{$name}/{$target}"),
                $content
            );
        }

        // Model
        File::put(
            base_path("modules/{$name}/Models/{$name}.php"),
            $this->modelStub($name)
        );
    }

    protected function createRoutes(string $name, string $lower): void
    {
        $controllerClass = "Modules\\{$name}\\Http\\Controllers\\{$name}Controller";
        $controllerClassApi = "Modules\\{$name}\\Http\\Controllers\\{$name}ApiController";


        // Web routes
        File::put(
            base_path("modules/{$name}/routes/web.php"),
            <<<PHP
<?php

use Illuminate\Support\Facades\Route;
use {$controllerClass};

Route::middleware(['web','auth', 'verified'])->prefix('/{$lower}')->group(function () {
    Route::get('/', [{$name}Controller::class, 'index'])->name('{$lower}.index');
    Route::get('/add', [{$name}Controller::class, 'add'])->name('{$lower}.add');
    Route::post('/store', [{$name}Controller::class, 'store'])->name('{$lower}.store');
    Route::get('/list', [{$name}Controller::class, 'dataTableList'])->name('{$lower}.list');
    Route::get('/edit/{id}', [{$name}Controller::class, 'edit'])->name('{$lower}.edit');
    Route::post('/update/{id}', [{$name}Controller::class, 'update'])->name('{$lower}.update');
    Route::delete('/delete', [{$name}Controller::class, 'delete'])->name('{$lower}.delete');
});
PHP
        );

        // API routes (optional)
        File::put(
            base_path("modules/{$name}/routes/api.php"),
            <<<PHP
<?php

use Illuminate\Support\Facades\Route;
use {$controllerClassApi};

Route::middleware('api')
    ->prefix('api/v1')
    ->as('api_{$lower}.')
//    ->middleware('auth:sanctum')
    ->group(function () {
        Route::apiResource('{$lower}', {$name}ApiController::class);
    });
PHP
        );
    }

    protected function createServiceProvider(string $name): void
    {
        File::put(
            base_path("modules/{$name}/{$name}ServiceProvider.php"),
            <<<PHP
<?php

namespace Modules\\{$name};

use Illuminate\Support\ServiceProvider;
use Modules\\{$name}\\Repositories\\Contracts\\{$name}RepositoryInterface;
use Modules\\{$name}\\Repositories\\Eloquent\\{$name}Repository;
use Modules\\{$name}\\Services\\Contracts\\{$name}ServiceInterface;
use Modules\\{$name}\\Services\\{$name}Service;

class {$name}ServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        \$this->app->bind({$name}RepositoryInterface::class, {$name}Repository::class);
        \$this->app->bind({$name}ServiceInterface::class, {$name}Service::class);
    }

    public function boot(): void
    {
        // Load module routes
        \$this->loadRoutesFrom(__DIR__.'/routes/web.php');
        \$this->loadRoutesFrom(__DIR__.'/routes/api.php');

        // Load views
        \$this->loadViewsFrom(__DIR__.'/resources/views', '{$name}');

        // Load migrations
        \$this->loadMigrationsFrom(__DIR__.'/database/migrations');
    }


}
PHP
        );
    }

    protected function modelStub(string $name): string
    {
        return <<<PHP
<?php

namespace Modules\\{$name}\\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class {$name} extends Model
{
     protected \$fillable = [
        'title',
        'slug',
        'image',
        'short_description',
        'description',
        'type',
        'status',
    ];

    protected static function booted(): void
    {
        static::creating(function (\$data) {
            if (empty(\$data->slug)) {
                \$data->slug = static::generateUniqueSlug(\$data->title);
            }
        });

        static::updating(function (\$data) {
            if (\$data->isDirty('title')) {
                \$data->slug = static::generateUniqueSlug(
                    \$data->title,
                    \$data->id
                );
            }
        });

        static::deleting(function (\$data) {
            if (\$data->image && File::exists(public_path(\$data->image))) {
                File::delete(public_path(\$data->image));
            }
        });

    }

    protected static function generateUniqueSlug(string \$title, ?int \$ignoreId = null): string
    {
        \$slug = Str::slug(\$title);
        \$originalSlug = \$slug;
        \$count = 1;

        while (
        static::where('slug', \$slug)
            ->when(\$ignoreId, fn(\$q) => \$q->where('id', '!=', \$ignoreId))
            ->exists()
        ) {
            \$slug = \$originalSlug . '-' . \$count++;
        }

        return \$slug;
    }
}
PHP;
    }

    protected function createMigration(string $name, string $lower): void
    {
        $timestamp = date('Y_m_d_His');
        $table = Str::snake(Str::pluralStudly($name));

        $path = base_path("modules/{$name}/database/migrations/{$timestamp}_create_{$table}_table.php");

        File::put($path, <<<PHP
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('{$table}', function (Blueprint \$table) {
            \$table->id();
            \$table->string('title');
            \$table->string('slug');
            \$table->string('image');
            \$table->unsignedBigInteger('user_id')->nullable(); // Make nullable for onDelete('set null')
            \$table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            \$table->tinyText('short_description');
            \$table->text('description');
            \$table->enum('type', ['upstream']);
            \$table->enum('status', ['active', 'inactive'])->default('active');
            \$table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('{$table}');
    }
};
PHP
        );
    }

}
