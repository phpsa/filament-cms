<?php

namespace Phpsa\FilamentCms\Components;

use Filament\Forms\Components\TextInput;
use MediaEmbed\MediaEmbed;

class VideoEmbed extends TextInput
{
    protected string $view = 'filament-cms::filament.components.video-embed';

    protected MediaEmbed $mediaEmbed;

    protected function setUp(): void
    {
        $this->url()->reactive();
        $this->mediaEmbed = new MediaEmbed();
    }

    public function getPreview(): ?string
    {
        $url =  $this->mediaEmbed->parseUrl($this->getState());
        if ($url === null) {
            return null;
        }
        $url->setWidth('100%');
        $url->getEmbedCode();

        return $url;
    }
}
