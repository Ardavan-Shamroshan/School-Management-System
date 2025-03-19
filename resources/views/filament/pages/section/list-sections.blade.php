<x-filament-panels::page>

    {{--    @if($record->image)--}}
    {{--        <img src="{{ asset($record->image) }}" alt="{{ $record->name }}" style="width: 100px"/>--}}
    {{--    @endif--}}

    <div class="grid grid-cols-10 items-center gap-x-1">
        @foreach($this->sections as $section)
            <div class="col-span-1">
                <input
                        id="{{ $section->id }}"
                        name="section_id"
                        type="radio"
                        class="sr-only peer"
                        wire:model="selected"
                        wire:click="changeSection({{ $section->id }})"
                >
                <label
                        for="{{ $section->id }}"
                        @class([
                            'flex h-20 p-3 bg-white border border-gray-300
                            rounded-lg transition-all duration-200 ease-in-out
                             cursor-pointer focus:outline-none hover:border-primary-500',
                            'border-primary-500 border-2' => $section->id == $selected->id
                            ])
                        wire:key="section-{{ $section->id }}"
                >
                    <span class="text-sm">{{ $section->teacher->name ?? $section->name }}</span>
                </label>
            </div>
        @endforeach


        <x-filament::modal
                id="create-section"
                :heading="__('Type section name')"
                icon="heroicon-o-academic-cap"
                width="xl"
        >
            <x-slot
                    name="trigger"
                    class="
                        flex h-20 bg-transparent hover:scale-95 scale-75 hover:bg-primary-50
                        border-2 border-dashed border-primary-500 rounded-lg transition-all
                        duration-200 ease-in-out cursor-pointer focus:outline-none
                    ">
                <x-filament::link
                        {{--                            wire:target="create"--}}
                        {{--                            wire:click="create"--}}
                        class="mx-auto text-center"
                        icon="heroicon-m-plus"
                        size="xl"
                        :tooltip="__('New section')"
                />
            </x-slot>

            <x-filament::input.wrapper
                    :valid="! $errors->has('name')"
            >
                <x-filament::input
                        required="true"
                        type="text"
                        wire:model="name"
                />
            </x-filament::input.wrapper>
            @error('name')
            <p
                    data-validation-error="true"
                    class="fi-fo-field-wrp-error-message text-sm text-danger-600 dark:text-danger-400"
            >
                {{ $message }}
            </p>
            @enderror


            <x-slot:footerActions>
                <x-filament::button
                        wire:target="create"
                        wire:click="create"
                        :tooltip="__('Create')"
                >
                    {{ __('Create') }}
                </x-filament::button>
            </x-slot:footerActions>

        </x-filament::modal>

    </div>

    <x-filament::pagination
            :paginator="$this->sections"
            {{--            :page-options="[5, 10, 20, 50, 100, 'all']"--}}
            {{--            :current-page-option-property="$perPage"--}}
            {{--            extreme-links--}}
    />

</x-filament-panels::page>
