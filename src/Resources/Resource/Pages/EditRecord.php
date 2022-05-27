<?php

namespace Phpsa\FilamentCms\Resources\Resource\Pages;

use Filament\Resources\Pages\EditRecord as FilamentEditRecord;

class EditRecord extends FilamentEditRecord
{
    protected function getRedirectUrl(): ?string
    {
        return static::getResource()::getUrl('index');
    }
}
