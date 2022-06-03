<?php

namespace Phpsa\FilamentCms\Components\Forms;

use Filament\Forms;
use Livewire\Component;
use Filament\Forms\Contracts\HasForms;
use Phpsa\FilamentCms\Components\Fields\MediaUpload;
use Filament\Forms\Concerns\InteractsWithForms;
use Phpsa\FilamentCms\Models\CmsMedia;

class CreateMediaForm extends Component implements HasForms
{
    use InteractsWithForms;

    public $data;

    public $public_id;
    public $filename;
    public $ext;
    public $type;
    public $width;
    public $height;
    public $disk;
    public $size;
    public $upload;
    public $alt;
    public $title;
    public $caption;
    public $description;

    public function mount()
    {
        $this->form->fill();
    }

    protected function getFormStatePath(): string
    {
        return 'data';
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Group::make()
                ->schema([
                    MediaUpload::make('filename')
                        ->label('File')
                          ->disableLabel(false)
                        ->columnSpan(['md' => 1, 'lg' => 2]),
                    Forms\Components\Group::make()
                        ->schema([
                            Forms\Components\TextInput::make('alt')
                                ->label('Alt Text')
                                ->helperText('<span class="block -mt-1 text-xs"><a href="https://www.w3.org/WAI/tutorials/images/decision-tree" target="_blank" rel="noopener" class="underline text-primary-500 hover:text-primary-600 focus:text-primary-600">Learn how to describe the purpose of the image</a>. Leave empty if the image is purely decorative.</span>'),
                            Forms\Components\TextInput::make('title'),
                            Forms\Components\Textarea::make('caption')
                                ->rows(2),
                            Forms\Components\Textarea::make('description')
                                ->rows(2),
                        ])
                        ->columnSpan(['md' => 1]),
                ])
                ->columns(['md' => 2, 'lg' => 3])
        ];
    }

    public function create(): void
    {
        $media = CmsMedia::create($this->form->getState());
        $this->form->fill([]);
        $this->dispatchBrowserEvent('new-media-added', ['media' => $media]);
    }

    public function render()
    {
        return view('filament-cms::filament.components.create-media-form');
    }
}
