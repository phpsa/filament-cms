<?php

namespace Phpsa\FilamentCms\Resources\Resource\Pages;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\CreateRecord as FilamentCreateRecord;
use Phpsa\FilamentCms\Resources\Resource\Pages\Contract\HasLocalisedDates;
use Phpsa\FilamentCms\Resources\Resource\Pages\Contract\HasNode;

class CreateRecord extends FilamentCreateRecord
{
    use HasLocalisedDates;

    protected function handleRecordCreation(array $data): Model
    {

        $data = $this->convertLocalDatesToSystemDates($data);

        $data['user_id'] ??= Filament::auth()->user()?->id;

        $data['namespace'] = static::getResource();

        return parent::handleRecordCreation($data);
    }
}
