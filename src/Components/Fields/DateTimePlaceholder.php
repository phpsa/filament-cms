<?php

namespace Phpsa\FilamentCms\Components\Fields;

use Filament\Forms\Components\DateTimePicker;

class DateTimePlaceholder extends DateTimePicker
{
    protected string $view = 'forms::components.placeholder';

    protected $content = null;

    public function getContent()
    {
        return $this->evaluate($this->content ?? $this->getState());
    }

    public function content($content): static
    {
        $this->content = $content;

        return $this;
    }
}
