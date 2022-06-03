<x-forms::field-wrapper
    :id="$getId()"
    :label="$getLabel()"
    :label-sr-only="$isLabelHidden()"
    :helper-text="$getHelperText()"
    :hint="$getHint()"
    :hint-icon="$getHintIcon()"
    :required="$isRequired()"
    :state-path="$getStatePath()"
>

    <div
        x-data="{ state: $wire.entangle('{{ $getStatePath() }}') }"
        x-on:close-modal.window="$event.detail.fieldId == '{{ $getStatePath() }}' ? state = $event.detail.media : null"
        class="w-full"
    >

        @if (!$getState())
            <div>
                <x-filament::button
                    type="button"
                    icon="heroicon-o-document-add"
                    outlined="true"
                    x-on:click="$dispatch('open-modal', {id: 'filament-cms-media-picker', fieldId: '{{ $getStatePath() }}'})"
                >
                    Add Media
                </x-filament::button>
            </div>
        @else
            @php
                $currentItem = $getCurrentItem($getState());
            @endphp
            <div
                class="relative block h-64 w-full gap-4 overflow-hidden rounded-lg border border-gray-300 shadow-sm transition duration-75 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                <img
                    src="{{ $currentItem['url'] }}"
                    alt="{{ $currentItem['alt'] }}"
                    class="checkered h-full w-full object-cover"
                />
                <div
                    class="filament-page-actions filament-form-actions mt-3 flex flex-wrap items-center justify-start gap-4 p-3">
                    <x-filament::button
                        type="button"
                        icon="heroicon-o-document-add"
                        x-on:click="$dispatch('open-modal', {id: 'filament-cms-media-picker', fieldId: '{{ $getStatePath() }}'})"
                    >
                        Swap
                    </x-filament::button>

                    <x-filament::button
                        color="danger"
                        type="button"
                        icon="heroicon-o-document-remove"
                        x-on:click="state = null"
                    >Clear
                    </x-filament::button>
                </div>
            </div>
        @endif
    </div>

    @once
        @push('modals')
            @livewire('media-picker-modal')
        @endpush
    @endonce
</x-forms::field-wrapper>
