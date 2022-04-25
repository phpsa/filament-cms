<?php

namespace Phpsa\FilamentCms\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Filament\Commands\Concerns\CanValidateInput;
use Filament\Commands\Concerns\CanManipulateFiles;
use Filament\Commands\Concerns\CanGenerateResources;
use Phpsa\FilamentCms\Resources\Resource\Pages\EditRecord;
use Phpsa\FilamentCms\Resources\Resource\Pages\ListRecords;
use Phpsa\FilamentCms\Resources\Resource\Pages\CreateRecord;

class MakeResourceCommand extends Command
{
    use CanGenerateResources;
    use CanManipulateFiles;
    use CanValidateInput;

    protected $description = 'Creates a Filament CMS resource class and default page classes.';

    protected $signature = 'make:filament-cms-resource {name?}  {--F|force}';
    //protected $signature = 'make:filament-cms-resource {name?} {--view-page} {--G|generate} {--S|simple} {--F|force}';

    public function handle(): int
    {
         /** @phpstan-ignore-next-line */
        $model = (string) Str::of($this->argument('name') ?? $this->askRequired('Name (e.g. `BlogPost`)', 'name'))
            ->studly()
            ->beforeLast('Resource')
            ->trim('/')
            ->trim('\\')
            ->trim(' ')
            ->studly()
            ->replace('/', '\\');
        $modelClass = (string) Str::of($model)->afterLast('\\');
        $modelNamespace = Str::of($model)->contains('\\') ?
        (string) Str::of($model)->beforeLast('\\') :
        '';
        $resourceClass = "{$modelClass}Resource";
        $pluralModelClass = (string) Str::of($modelClass)->pluralStudly();

        $resource = "{$model}Resource";
        $resourceNamespace = $modelNamespace;
        $listResourcePageClass = "List{$pluralModelClass}";
        $manageResourcePageClass = "Manage{$pluralModelClass}";
        $createResourcePageClass = "Create{$modelClass}";
        $editResourcePageClass = "Edit{$modelClass}";
        $viewResourcePageClass = "View{$modelClass}";

        $baseResourcePath = app_path(
            (string) Str::of($resource)
                ->prepend('Filament\\Resources\\')
                ->replace('\\', '/'),
        );

        $resourcePath = "{$baseResourcePath}.php";
        $resourcePagesDirectory = "{$baseResourcePath}/Pages";
        $listResourcePagePath = "{$resourcePagesDirectory}/{$listResourcePageClass}.php";
        $manageResourcePagePath = "{$resourcePagesDirectory}/{$manageResourcePageClass}.php";
        $createResourcePagePath = "{$resourcePagesDirectory}/{$createResourcePageClass}.php";
        $editResourcePagePath = "{$resourcePagesDirectory}/{$editResourcePageClass}.php";
        $viewResourcePagePath = "{$resourcePagesDirectory}/{$viewResourcePageClass}.php";

        if (
            ! $this->option('force') && $this->checkForCollision([
                $resourcePath,
                $listResourcePagePath,
                $manageResourcePagePath,
                $createResourcePagePath,
                $editResourcePagePath,
                $viewResourcePagePath,
            ])
        ) {
            return static::INVALID;
        }

        $pages = '';
        $pages .= '\'index\' => Pages\\' . $listResourcePageClass . '::route(\'/\'),';

        $pages .= PHP_EOL . "'create' => Pages\\{$createResourcePageClass}::route('/create'),";

        // if ($this->option('view-page')) {
        //     $pages .= PHP_EOL . "'view' => Pages\\{$viewResourcePageClass}::route('/{record}'),";
        // }

        $pages .= PHP_EOL . "'edit' => Pages\\{$editResourcePageClass}::route('/{record}/edit'),";

        $relations = '';

        $this->copyStubToApp('Resource', $resourcePath, [
            'formSchema'    => $this->indentString('//', 4),
            'model'         => $model,
            'modelClass'    => $modelClass,
            'resourceClass' => $resourceClass,
            'namespace'     => 'App\\Filament\\Resources' . ($resourceNamespace !== '' ? "\\{$resourceNamespace}" : ''),
            'resource'      => $resource,
            'tableColumns'  => $this->indentString('//', 4),
            'pages'         => $this->indentString($pages, 3),
            'relations'     => $this->indentString($relations, 1),
            'label'         => Str::of($model)->snake(' '),
            'pluralLabel'   => Str::of($model)->plural()->snake(' '),
        ]);

        $this->copyStubToApp('DefaultResourcePage', $listResourcePagePath, [
            'baseResourcePage'      => ListRecords::class,
            'baseResourcePageClass' => 'ListRecords',
            'namespace'             => "App\\Filament\\Resources\\{$resource}\\Pages",
            'resource'              => $resource,
            'resourceClass'         => $resourceClass,
            'resourcePageClass'     => $listResourcePageClass,
        ]);

        $this->copyStubToApp('DefaultResourcePage', $createResourcePagePath, [
            'baseResourcePage'      => CreateRecord::class,
            'baseResourcePageClass' => 'CreateRecord',
            'namespace'             => "App\\Filament\\Resources\\{$resource}\\Pages",
            'resource'              => $resource,
            'resourceClass'         => $resourceClass,
            'resourcePageClass'     => $createResourcePageClass,
        ]);

            // if ($this->option('view-page')) {
            //     $this->copyStubToApp('DefaultResourcePage', $viewResourcePagePath, [
            //         'baseResourcePage'      => 'Phpsa\\FilamentCms\\Resources\\Resources\\Pages\\ViewRecord',
            //         'baseResourcePageClass' => 'ViewRecord',
            //         'namespace'             => "App\\Filament\\Resources\\{$resource}\\Pages",
            //         'resource'              => $resource,
            //         'resourceClass'         => $resourceClass,
            //         'resourcePageClass'     => $viewResourcePageClass,
            //     ]);
            // }

        $this->copyStubToApp('DefaultResourcePage', $editResourcePagePath, [
            'baseResourcePage'      => EditRecord::class,
            'baseResourcePageClass' => 'EditRecord',
            'namespace'             => "App\\Filament\\Resources\\{$resource}\\Pages",
            'resource'              => $resource,
            'resourceClass'         => $resourceClass,
            'resourcePageClass'     => $editResourcePageClass,
        ]);

        $this->info("Successfully created {$resource}!");

        return static::SUCCESS;
    }

    protected function copyStubToApp(string $stub, string $targetPath, array $replacements = []): void
    {
        $filesystem = app(Filesystem::class);

        if (! $this->fileExists($stubPath = base_path("stubs/filament/{$stub}.stub"))) {
            $stubPath = __DIR__ . "/../../stubs/{$stub}.stub";
        }

        $stub = Str::of($filesystem->get($stubPath));

        foreach ($replacements as $key => $replacement) {
            $stub = $stub->replace("{{ {$key} }}", $replacement);
        }

        $stub = (string) $stub;

        $this->writeFile($targetPath, $stub);
    }

    protected function indentString(string $string, int $level = 1): string
    {
        return implode(
            PHP_EOL,
            array_map(
                fn (string $line) => str_repeat('    ', $level) . "{$line}",
                explode(PHP_EOL, $string),
            ),
        );
    }
}
