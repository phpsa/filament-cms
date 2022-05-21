<?php

namespace Phpsa\FilamentCms\Resources\Resource\Pages;

use Filament\Pages\Actions\Action;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\EditRecord as FilamentEditRecord;
use Phpsa\FilamentCms\Resources\Resource\Pages\Contract\HasNode;
use Phpsa\FilamentCms\Resources\Resource\Pages\Contract\HasLocalisedDates;

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

    // public function saveClose()
    // {
    //     $this->save(false);
    //     $this->redirect(static::getResource()::getUrl('index'));
    //     $this->getRedirectUrl();
    // }


    protected function getRedirectUrl(): ?string
    {
        return static::getResource()::getUrl('index');
    }



   /*
       protected function getFormActions(): array
       {
        return array_merge([
            Action::make('save_and_close')
            ->label(__('Save & Close'))
            ->action('saveClose')
            ->keyBindings(['mod+P'])
        ], parent::getFormActions());
    }*/
}
