@php use Illuminate\Pagination\LengthAwarePaginator; @endphp
<x-filament-panels::page>

    {{-- List sections --}}
    <div>
        <div class="grid grid-cols-11 gap-x-1">
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
                                'flex flex-wrap min-h-28 max-h-28 hover:!bg-primary-50 p-1 bg-white border border-gray-300
                                rounded-lg transition-all duration-200 ease-in-out
                                cursor-pointer focus:outline-none hover:border-primary-500 overflow-scroll no-scrollbar',
                                'border-primary-500 border-2 !bg-primary-50' => $item->id == $section->id
                                ])
                            wire:key="section-{{ $item->id }}"
                    >
                        <div class="flex flex-col justify-center mx-auto text-center gap-1">
                            <span @class(['text-sm', 'text-gray-500' => ! $item->name])>
                            {{ $item->name ?? __('No data') }}
                            </span>

                            <span class="text-sm">
                            {{ $item->teacher?->name }}
                            </span>

                            <span class="text-sm">{{ $item->schedule?->time ?? '' }}</span>

                            <span class="flex flex-row flex-wrap justify-start gap-1">
                                @foreach($item->schedules ?? [] as $badge)
                                    <x-filament::badge color="warning">
                                        {{ $badge->getLabel() }}
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

        @if($this->sections instanceof LengthAwarePaginator)
            <x-filament::pagination
                    class="mt-2"
                    :paginator="$this->sections"
            />
        @endif
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
