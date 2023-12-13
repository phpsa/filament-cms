<?php

namespace Phpsa\FilamentCms\Resources\CmsResource\Pages;

use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;
use Phpsa\FilamentCms\Components\Fields\CmsResource;

class CreateCmsPage extends CreateRecord
{
    protected static string $resource = CmsResource::class;

     /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {

        $data['user_id'] ??= Filament::auth()->user()?->id;

        $data['namespace'] = static::getResource();

        return $data;
    }
}
