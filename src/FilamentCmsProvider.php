<?php

namespace Phpsa\FilamentCms;

use Illuminate\Support\Str;
use Filament\Facades\Filament;
use Filament\PluginServiceProvider;
use Filament\Tables\Columns\Column;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Spatie\LaravelPackageTools\Package;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Phpsa\FilamentCms\Resources\Resource;
use Filament\Navigation\NavigationBuilder;
use Phpsa\FilamentCms\Models\CmsContentPages;
use Phpsa\FilamentCms\Commands\InstallCommand;
use Phpsa\FilamentCms\Resources\PagesResource;
use Phpsa\FilamentCms\Resources\PostsResource;
use Phpsa\FilamentCms\Resources\BlogPostResource;
use Phpsa\FilamentCms\Commands\MakeResourceCommand;
use Phpsa\FilamentCms\Resources\CategoriesResource;

class FilamentCmsProvider extends PluginServiceProvider
{
    public static string $name = 'filament-cms';

    protected array $widgets = [

    ];

    protected function getResources(): array
    {
        return config('filament-cms.resources', []);
    }

    public function configurePackage(Package $package): void
    {
        $package->name('filament-cms')
            ->hasViews()
            ->hasConfigFile()
            ->hasTranslations()
            ->hasCommand(InstallCommand::class)
            ->hasCommand(MakeResourceCommand::class)
            ->hasMigrations([
                'create_cms_content_nodes_table',
                'create_cms_content_pages',
            ]); //->hasViews()->hasConfigFile();
    }


    protected function registerMacros(): void
    {

        Select::macro(
            'fromCmsResource',
            /** @phpstan-ignore-next-line */
            fn (string|array $relations)  => $this->options(
                CmsContentPages::whereIn('namespace', (array) $relations)->pluck('name', 'id')
            )
        );

        Column::macro(
            'fromNodeState',
            /** @phpstan-ignore-next-line */
            fn() => $this->getStateUsing(
                function (Column $column, $record) {
                    $col = Str::of($column->getName())->replace(["nodes.","node."], "")->value();
                    return $record->node($col);
                }
            )
        );

        self::addResourceToNavigation('Cms Page', PagesResource::class);
        self::addResourceToNavigation('Cms Category', CategoriesResource::class);
        self::addResourceToNavigation('Cms BlogPost', BlogPostResource::class);
    }

    /**
     * Undocumented function
     *
     * @param string $type
     * @param class-string $namespace
     *
     * @return void
     */
    public static function addResourceToNavigation(string $type, string $namespace): void
    {
        if (class_exists(\RyanChandler\FilamentNavigation\Facades\FilamentNavigation::class)) {
            \RyanChandler\FilamentNavigation\Facades\FilamentNavigation::addItemType($type, [
                Hidden::make('route')->dehydrateStateUsing(fn () => Str::of($namespace)->replace("\\", "")->snake(".")),

                Select::make('slug')->label($type)
                ->options(
                    fn() =>  CmsContentPages::whereNamespace($namespace)->pluck('name', 'slug')
                )
            ]);
        }
    }
}
