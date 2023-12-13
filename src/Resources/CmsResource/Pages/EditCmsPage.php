<?php

namespace Phpsa\FilamentCms\Resources\CmsResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Phpsa\FilamentCms\Components\Fields\CmsResource;

class EditCmsPage extends EditRecord
{
    protected static string $resource = CmsResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
