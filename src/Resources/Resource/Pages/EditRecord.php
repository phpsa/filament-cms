<?php

namespace Phpsa\FilamentCms\Resources\Resource\Pages;

use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\EditRecord as FilamentEditRecord;
use Phpsa\FilamentCms\Resources\Resource\Pages\Contract\HasLocalisedDates;
use Phpsa\FilamentCms\Resources\Resource\Pages\Contract\HasNode;

class EditRecord extends FilamentEditRecord
{
    use HasNode;
    use HasLocalisedDates;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['node'] ??= $this->record->nodes->mapWithKeys(
            fn($rec) => [$rec->node => json_decode($rec->content) ?? $rec->content]
        )->filter()->toArray();

        $data = $this->convertSystemDatesToLocalDates($data);

        return $data;
    }


    protected function handleRecordUpdate(Model $record, array $data): Model
    {

        /** @var \Phpsa\FilamentCms\Models\CmsContentPages $record */

        $data = $this->convertLocalDatesToSystemDates($data);

        $record->update($data);

        $this->saveNodes($data['node'] ?? [], $record);

        return $record;
    }
}
