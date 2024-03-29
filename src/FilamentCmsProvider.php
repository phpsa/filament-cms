<?php

namespace Phpsa\FilamentCms;

use Livewire\Livewire;
use Illuminate\Support\Str;
use Filament\Facades\Filament;
use Spatie\LaravelPackageTools\PackageServiceProvider;
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
use Phpsa\FilamentCms\Components\Forms\CreateMediaForm;
use Phpsa\FilamentCms\Resources\BlogPostResource;
use Phpsa\FilamentCms\Components\Forms\MediaPickerModal;
use Phpsa\FilamentCms\Commands\MakeResourceCommand;
use Phpsa\FilamentCms\Resources\CategoriesResource;

class FilamentCmsProvider extends PackageServiceProvider
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
            ->hasRoutes('web')
            ->hasCommand(InstallCommand::class)
            ->hasCommand(MakeResourceCommand::class)
            ->hasMigrations([
                'create_cms_content_pages_table',
                'create_cms_seo_table'
            ]); //->hasViews()->hasConfigFile();
    }


    protected function registerMacros(): void
    {
        Select::macro(
            'fromCmsResource',
            /** @phpstan-ignore-next-line */
            fn (string|array $relations)  => $this->options(
                CmsContentPages::whereIn('namespace', (array) $relations)->pluck('name', 'id')->toArray()
            )
        );
    }
}
