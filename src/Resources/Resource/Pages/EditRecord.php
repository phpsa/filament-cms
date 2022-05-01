<?php

namespace Phpsa\FilamentCms\Resources\Resource\Pages;

use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\EditRecord as FilamentEditRecord;
use Phpsa\FilamentCms\Resources\Resource\Pages\Contract\HasLocalisedDates;
use Phpsa\FilamentCms\Resources\Resource\Pages\Contract\HasNode;

class EditRecord extends FilamentEditRecord
{
    use HasLocalisedDates;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        return $this->convertSystemDatesToLocalDates($data);
    }


    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $record->update(
            $this->convertLocalDatesToSystemDates($data)
        );

        return $record;
    }
}
