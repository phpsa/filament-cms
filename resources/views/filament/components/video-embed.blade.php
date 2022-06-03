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
    <div>
        @if ($getState() != '')
            <div @class([
                'p-4 relative',
                'border-t border-x rounded-t-lg' => $getState(),
                'dark:border-gray-600 dark:bg-transparent dark:text-white' => config(
                    'forms.dark_mode'
                ),
                'border-gray-300 bg-gray-100' => !$errors->has($getStatePath()),
                'border-danger-600 ring-danger-600' => $errors->has($getStatePath()),
            ])>
                <h3 @class([
                    'absolute py-0.5 px-2 top-0 left-0 text-xs border-b border-r rounded-br',
                    'dark:border-gray-600 dark:text-white' => config('forms.dark_mode'),
                    'border-gray-300' => !$errors->has($getStatePath()),
                ])>Preview</h3>
                <div class="grid place-content-center">
                    {!! $getPreview() !!}
                </div>
            </div>
        @endif
        <input
            id="{{ $getId() }}"
            type="{{ $getType() }}"
            dusk="filament.forms.{{ $getStatePath() }}"
            {!! $isAutofocused() ? 'autofocus' : null !!}
            {!! $isDisabled() ? 'disabled' : null !!}
            {!! $isRequired() ? 'required' : null !!}
            {{ $applyStateBindingModifiers('wire:model') }}="{{ $getStatePath() }}"
            {{ $getExtraInputAttributeBag()->class([
                'block w-full transition duration-75 rounded-lg shadow-sm focus:border-primary-600 focus:ring-1 focus:ring-inset focus:ring-primary-600 disabled:opacity-70',
                'dark:bg-gray-700 dark:text-white' => config('forms.dark_mode'),
                'border-gray-300' => !$errors->has($getStatePath()),
                'dark:border-gray-600' => !$errors->has($getStatePath()) && config('forms.dark_mode'),
                'border-danger-600 ring-danger-600' => $errors->has($getStatePath()),
            ]) }}
            {{ $getExtraAlpineAttributeBag() }}
        >
    </div>
    <p class="text-xs">Paste entire share url into this field.</p>
</x-forms::field-wrapper>
