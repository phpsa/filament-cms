<?php

namespace Phpsa\FilamentCms\Resources\Resource\Pages;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\CreateRecord as FilamentCreateRecord;

class CreateRecord extends FilamentCreateRecord
{
    protected function handleRecordCreation(array $data): Model
    {

        $data['user_id'] ??= Filament::auth()->user()?->id;

        $data['namespace'] = static::getResource();

        return parent::handleRecordCreation($data);
    }
}
