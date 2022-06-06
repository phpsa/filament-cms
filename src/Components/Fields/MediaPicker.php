<?php

namespace Phpsa\FilamentCms\Components\Fields;

use Filament\Forms\Components\Field;
use Phpsa\FilamentCms\Models\CmsMedia;

class MediaPicker extends Field
{
    protected string $view = 'filament-cms::filament.components.media-picker';


    protected function setUp(): void
    {
        parent::setUp();

        $this->dehydrateStateUsing(static function (MediaPicker $component, $state) {
            if (blank($state)) {
                return null;
            }
            return is_array($state) ?  $state['id'] : $state;
        });
    }



    public function getCurrentItem($state)
    {
        return CmsMedia::where('id', $state)->first();
    }
}
