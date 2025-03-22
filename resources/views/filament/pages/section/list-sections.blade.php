<x-filament-panels::page>

    {{-- List sections --}}
    <div>
        <div class="grid grid-cols-9 items-center gap-x-1">
            @foreach($this->sections as $item)
                <div class="col-span-1">
                    <input
                            id="{{ $item->id }}"
                            name="section_id"
                            type="radio"
                            class="sr-only"
                            wire:model="section"
                            wire:click="changeSection({{ $item->id }})"
                    >
                    <label
                            for="{{ $item->id }}"
                            @class([
                                'flex flex-wrap h-28 hover:!bg-primary-50 p-1 bg-white border border-gray-300
                                rounded-lg transition-all duration-200 ease-in-out
                                cursor-pointer focus:outline-none hover:border-primary-500 overflow-scroll no-scrollbar',
                                'border-primary-500 border-2 !bg-primary-50' => $item->id == $section->id
                                ])
                            wire:key="section-{{ $item->id }}"
                    >
                        <div class="flex flex-col gap-2">
                            <span class="text-sm">
                            {{
                                // str($item->id)
                                // ->when($item->name, fn($string) => $string->append(' - ')->append($item->name))
                                str($item->name ?? $item->teacher?->name ?? '-')
                                // ->when($item->teacher?->name, fn($string) => $string->append(' - ')->append($item->teacher->name))
                                ->value()
                            }}
                            </span>

                            <span class="text-gray-500 text-sm">{{ $item->start_time ?? '-' }}</span>

                            <span class="flex flex-row flex-wrap justify-start gap-1">
                                @foreach($item->schedulesBadge as $badge)
                                    <x-filament::badge color="warning" size="sm">
                                        {{ $badge }}
                                    </x-filament::badge>
                                @endforeach
                            </span>
                        </div>


                    </label>
                </div>
            @endforeach

            {{-- Create section action --}}
            <div class="col-span-1">
                @if ($this->createSectionAction->isVisible())
                    {{ $this->createSectionAction }}
                @endif
            </div>
        </div>

        <x-filament::pagination
                class="mt-4"
                :paginator="$this->sections"
        />
    </div>

    {{-- Section actions form --}}
    <div>
        <x-filament-panels::form wire:submit="save">

            {{ $this->form }}

        </x-filament-panels::form>
    </div>

    {{-- List students --}}
    <div>
        {{ $this->table }}
    </div>

    {{-- Add student form --}}
    <div>
        <x-filament-panels::form wire:submit="addStudent">

            {{ $this->addStudentForm }}

        </x-filament-panels::form>
    </div>

    <x-filament-actions::modals/>

</x-filament-panels::page>
