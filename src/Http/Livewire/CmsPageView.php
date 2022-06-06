<?php

namespace Phpsa\FilamentCms\Http\Livewire;

use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Component;
use Filament\Forms\Concerns\InteractsWithForms;
use Phpsa\FilamentCms\Models\CmsContentPages;

class CmsPageView extends Component implements HasForms
{
    use InteractsWithForms;

    protected static bool $shouldRegisterNavigation = false;

    public CmsContentPages $page;

    public $data;

    public function mount(): void
    {
        $this->form->fill();
    }


    public function __construct(CmsContentPages $page)
    {
        $this->page = $page;
    }

    protected function getFormStatePath(): string
    {
        return 'data';
    }

    protected function getFormSchema(): array
    {

        dd();
    }
}
