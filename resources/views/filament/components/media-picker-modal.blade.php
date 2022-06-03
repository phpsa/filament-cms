<div
    x-data="{
        isOpen: false,
        selected: @entangle('selected'),
        fieldId: null,
        files: [],
        nextPageUrl: null,
        selectedTabId: null,
        isFetching: false,
        init() {
            // Set the first available tab on the page on page load.
            this.$nextTick(() => this.selectTab(this.$id('tab', 1)))
        },
        selectTab(id) {
            this.selectedTabId = id
        },
        isTabSelected(id) {
            return this.selectedTabId === id
        },
        whichChild(el, parent) {
            return Array.from(parent.children).indexOf(el) + 1
        },
        getFiles: async function(url = '{{ route('filament-cms.cms-media') }}') {
            this.isFetching = true;
            const response = await fetch(url);
            const result = await response.json();
            this.files = this.files ? this.files.concat(result.data) : result.data;
            this.nextPageUrl = result.next_page_url;
            this.isFetching = false;
        },
        loadMoreFiles: async function() {
            if (this.nextPageUrl) {
                this.isFetching = true;
                await this.getFiles(this.nextPageUrl);
                this.isFetching = false;
            }
        },
        searchFiles: async function(event) {
            this.isFetching = true;
            const response = await fetch('{{ route('filament-cms.cms-media') }}?q=' + event.target.value);
            const result = await response.json();
            this.files = result.data;
            this.isFetching = false;
        },
        addNewFile: function(media = null) {
            if (media) {
                this.files = [];
                this.getFiles();
                this.setSelected(media.id);
            }
        },
        removeFile: function(media = null) {
            if (media) {
                this.files = this.files.filter((obj) => obj.id !== media.id);
                this.files = [];
                this.getFiles();
                this.selected = null;
            }
        },
        setSelected: function(mediaId = null) {
            if (!mediaId || (this.selected && this.selected.id === mediaId)) {
                this.selected = null;
            } else {
                this.selected = this.files.find(obj => obj.id === mediaId);
            }
        },
        resetPicker: function() {
            this.files = [];
            this.setSelected(null);
        }
    }"
    x-on:close-modal.window="if ($event.detail.id === 'filament-cms-media-picker') isOpen = false; resetPicker();"
    x-on:open-modal.window="if ($event.detail.id === 'filament-cms-media-picker') isOpen = true; fieldId = $event.detail.fieldId; getFiles();"
    x-on:clear-selected="selected = null"
    x-on:new-media-added.window="addNewFile($event.detail.media)"
    x-on:remove-media.window="removeFile($event.detail.media)"
    aria-labelledby="filament-cms-media-modal-heading"
    role="dialog"
    aria-modal="true"
    class="filament-cms-media-picker-modal inline-block"
    x-id="['tab']"
>

    <div
        x-show="isOpen"
        x-transition:enter="ease duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        x-cloak
        class="fixed inset-0 z-40 flex min-h-screen items-center overflow-y-auto p-10 transition"
    >

        <button
            x-on:click="isOpen = false; resetPicker();"
            type="button"
            aria-hidden="true"
            class="filament-cms-media-picker-modal-close-overlay fixed inset-0 h-full w-full bg-black/50 focus:outline-none"
        ></button>

        <div
            x-show="isOpen"
            x-trap.noscroll="isOpen"
            x-on:keydown.window.escape="isOpen = false; resetPicker();"
            x-transition:enter="ease duration-300"
            x-transition:enter-start="translate-y-8"
            x-transition:enter-end="translate-y-0"
            x-transition:leave="ease duration-300"
            x-transition:leave-start="translate-y-0"
            x-transition:leave-end="translate-y-8"
            x-cloak
            class="relative mt-auto h-full w-full cursor-pointer md:mb-auto"
        >
            <div @class([
                'w-full mx-auto space-y-2 bg-white rounded-xl flex flex-col cursor-default h-full filament-cms-media-picker-modal-window',
                'dark:bg-gray-800' => config('filament.dark_mode'),
            ])>
                <div
                    id="filament-cms-media-modal-heading"
                    @class([
                        'flex items-center justify-between py-2 pl-4 pr-2 border-b border-gray-300 filament-cms-media-picker-modal-header',
                        'dark:border-gray-700' => config('filament.dark_mode'),
                    ])
                >
                    <h3 class="font-bold">{{ __('Media Picker') }}</h3>
                    <div
                        class="group filament-forms-text-input-component flex items-center space-x-1 rtl:space-x-reverse">
                        <label
                            for="media-search-input"
                            class="sr-only"
                        >
                            Search
                        </label>

                        <div class="flex-1">
                            <input
                                type="search"
                                id="media-search-input"
                                wire:ignore
                                placeholder="Search"
                                x-on:input.debounce.500ms="searchFiles"
                                class="focus:border-primary-600 focus:ring-primary-600 block w-full rounded-lg py-1 shadow-sm transition duration-75 focus:ring-1 focus:ring-inset disabled:opacity-70 dark:bg-gray-700 dark:text-white"
                            />
                        </div>

                    </div>
                </div>

                <div class="filament-cms-media-picker-modal-content flex-1 space-y-2 overflow-hidden">
                    <div class="h-full space-y-4 p-4">
                        <div
                            class="flex h-full flex-col"
                            wire:ignore
                            x-on:new-media-added.window="$nextTick(() => selectTab($id('tab', 1)))"
                        >

                            <ul
                                x-ref="tablist"
                                x-on:keydown.right.prevent.stop="$focus.wrap().next()"
                                x-on:keydown.home.prevent.stop="$focus.first()"
                                x-on:keydown.page-up.prevent.stop="$focus.first()"
                                x-on:keydown.left.prevent.stop="$focus.wrap().prev()"
                                x-on:keydown.end.prevent.stop="$focus.last()"
                                x-on:keydown.page-down.prevent.stop="$focus.last()"
                                role="tablist"
                                class="-mb-px flex items-stretch text-sm"
                            >
                                <li>
                                    <button
                                        :id="$id('tab', whichChild($el.parentElement, $refs.tablist))"
                                        x-on:click="selectTab($el.id)"
                                        x-on:focus="selectTab($el.id)"
                                        type="button"
                                        x-bind:tabindex="isTabSelected($el.id) ? 0 : -1"
                                        x-bind:aria-selected="isTabSelected($el.id)"
                                        x-bind:class="isTabSelected($el.id) ?
                                            'border-gray-300 dark:border-gray-700 bg-gray-200 dark:bg-gray-700' :
                                            'border-transparent'"
                                        class="inline-flex rounded-t-md border-t border-l border-r px-4 py-2"
                                        role="tab"
                                    >Media Library</button>
                                </li>
                                <li>
                                    <button
                                        :id="$id('tab', whichChild($el.parentElement, $refs.tablist))"
                                        x-on:click="selectTab($el.id); $dispatch('clear-selected');"
                                        x-on:focus="selectTab($el.id)"
                                        type="button"
                                        x-bind:tabindex="isTabSelected($el.id) ? 0 : -1"
                                        x-bind:aria-selected="isTabSelected($el.id)"
                                        x-bind:class="isTabSelected($el.id) ?
                                            'border-gray-300 dark:border-gray-700 bg-gray-200 dark:bg-gray-700' :
                                            'border-transparent'"
                                        class="inline-flex rounded-t-md border-t border-l border-r px-4 py-2"
                                        role="tab"
                                    >Upload Media</button>
                                </li>

                            </ul>

                            <div
                                role="tabpanels"
                                class="h-full flex-1 overflow-hidden rounded-b-md border border-gray-300 dark:border-gray-700"
                            >
                                <section
                                    x-show="isTabSelected($id('tab', whichChild($el, $el.parentElement)))"
                                    x-bind:aria-labelledby="$id('tab', whichChild($el, $el.parentElement))"
                                    role="tabpanel"
                                    class="h-full overflow-hidden"
                                >
                                    <div class="relative flex h-full w-full overflow-hidden">
                                        <div class="relative h-full flex-1 overflow-scroll p-4">

                                            {{-- Loading Indicator --}}
                                            <div
                                                x-show="isFetching"
                                                style="display: none;"
                                                class="absolute inset-0 z-10 flex items-center justify-center bg-gray-300 bg-opacity-70 dark:bg-gray-900"
                                            >
                                                <svg
                                                    class="h-12 w-12 animate-spin text-white"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <circle
                                                        class="opacity-25"
                                                        cx="12"
                                                        cy="12"
                                                        r="10"
                                                        stroke="currentColor"
                                                        stroke-width="4"
                                                    ></circle>
                                                    <path
                                                        class="opacity-75"
                                                        fill="currentColor"
                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                                    >
                                                    </path>
                                                </svg>
                                            </div>
                                            {{-- End Loading Indicator --}}

                                            {{-- File List --}}
                                            <ul
                                                class="grid grid-cols-3 gap-4 sm:grid-cols-4 md:grid-cols-6 2xl:grid-cols-8">
                                                <template x-for="file in files">
                                                    <li
                                                        x-bind:key="file.id"
                                                        class="relative aspect-square"
                                                    >
                                                        <button
                                                            type="button"
                                                            x-on:click.prevent="setSelected(file.id)"
                                                            class="focus:outline-3 focus:outline-primary-500 block bg-gray-700 focus:shadow-lg focus:outline focus:outline-offset-1"
                                                            x-bind:class="{
                                                                'outline outline-offset-1 outline-3 outline-primary-500 shadow-lg': selected &&
                                                                    selected.id === file.id
                                                            }"
                                                        >
                                                            <img
                                                                x-bind:src="file.thumbnail_url"
                                                                x-bind:alt="file.alt"
                                                                width="300"
                                                                height="300"
                                                                class="checkered block h-full object-cover"
                                                            />
                                                        </button>
                                                        <button
                                                            x-on:click="setSelected(null)"
                                                            style="display: none;"
                                                            class="bg-primary-500 absolute top-0 right-0 flex h-6 w-6 items-center justify-center text-white"
                                                            x-show="selected && selected.id === file.id"
                                                        >
                                                            <x-heroicon-s-check class="h-5 w-5" />
                                                            <span class="sr-only">Deselect</span>
                                                        </button>
                                                    </li>
                                                </template>
                                                <li
                                                    class="relative aspect-square"
                                                    x-intersect="loadMoreFiles();"
                                                    x-show="nextPageUrl"
                                                    style="display: none;"
                                                >
                                                    <button
                                                        type="button"
                                                        x-on:click.prevent="loadMoreFiles()"
                                                        class="focus:outline-primary-500 absolute inset-0 flex items-center justify-center !bg-gray-700 focus:shadow-lg focus:outline focus:outline-2 focus:outline-offset-1"
                                                    >
                                                        Load More
                                                    </button>
                                                </li>
                                                <li
                                                    x-show="files.length === 0"
                                                    style="display: none;"
                                                    class="col-span-3 sm:col-span-4 md:col-span-6 lg:col-span-8"
                                                >
                                                    No Files in the library or nothing found for your search.
                                                </li>
                                            </ul>
                                            {{-- End File List --}}

                                        </div>

                                        {{-- Edit Form --}}
                                        <div @class([
                                            'hidden w-full h-full max-w-xs overflow-scroll bg-gray-200 lg:!block ',
                                            'dark:bg-gray-900' => config('filament.dark_mode'),
                                        ])>
                                            <form
                                                wire:submit.prevent="update"
                                                x-show="selected"
                                                class="p-4"
                                            >

                                                <h4 class="mb-4 font-bold">
                                                    Edit Media
                                                </h4>

                                                <div
                                                    class="mb-4 overflow-hidden rounded border border-gray-300 bg-gray-300 dark:border-gray-700 dark:bg-gray-700">
                                                    <img
                                                        x-bind:src="selected?.medium_url"
                                                        x-bind:alt="selected?.alt"
                                                        x-bind:width="selected?.width"
                                                        x-bind:height="selected?.height"
                                                        class="checkered block h-full object-cover"
                                                    />
                                                </div>

                                                {{ $this->form }}

                                                <div class="mt-4 flex items-center gap-3">

                                                    <x-filament::button type="submit">
                                                        <span wire:loading>
                                                            <svg
                                                                class="inline-block h-5 w-5 animate-spin text-white"
                                                                xmlns="http://www.w3.org/2000/svg"
                                                                fill="none"
                                                                viewBox="0 0 24 24"
                                                            >
                                                                <circle
                                                                    class="opacity-25"
                                                                    cx="12"
                                                                    cy="12"
                                                                    r="10"
                                                                    stroke="currentColor"
                                                                    stroke-width="4"
                                                                ></circle>
                                                                <path
                                                                    class="opacity-75"
                                                                    fill="currentColor"
                                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                                                >
                                                                </path>
                                                            </svg>
                                                        </span>
                                                        <span>Save</span>
                                                    </x-filament::button>

                                                    <x-filament::button
                                                        type="button"
                                                        color="danger"
                                                        wire:click.prevent="destroy"
                                                    >
                                                        Delete
                                                    </x-filament::button>

                                                    <x-filament::button
                                                        type="button"
                                                        color="secondary"
                                                        x-on:click="selected = null"
                                                    >
                                                        Cancel
                                                    </x-filament::button>

                                                </div>

                                            </form>
                                        </div>
                                        {{-- End Edit Form --}}
                                    </div>
                                </section>

                                <section
                                    x-show="isTabSelected($id('tab', whichChild($el, $el.parentElement)))"
                                    x-bind:aria-labelledby="$id('tab', whichChild($el, $el.parentElement))"
                                    role="tabpanel"
                                    class="h-full overflow-y-scroll p-4 md:p-6"
                                >
                                    @livewire('create-media-form')
                                </section>
                            </div>
                        </div>
                    </div>
                </div>

                <div @class([
                    'flex filament-form-actions items-center gap-4 justify-end flex-wrap p-3 filament-cms-media-picker-modal-footer border-t border-gray-300',
                    'dark:border-gray-700' => config('filament.dark_mode'),
                ])>

                    <x-filament::button
                        type="button"
                        color="success"
                        x-bind:disabled="!selected"
                        x-on:click="$dispatch('insert-media', {id: 'filament-cms-media-picker', media: selected, fieldId: fieldId}); $dispatch('close-modal', {id: 'filament-cms-media-picker', media: selected, fieldId: fieldId})"
                    >
                        Use Selected Image
                    </x-filament::button>
                    <x-filament::button
                        keybindings="esc"
                        class="ml-5"
                        type="button"
                        color="secondary"
                        x-on:click="$dispatch('close-modal', {id: 'filament-cms-media-picker', fieldId: fieldId});"
                    >
                        Cancel
                    </x-filament::button>
                </div>
            </div>
        </div>
    </div>
</div>
