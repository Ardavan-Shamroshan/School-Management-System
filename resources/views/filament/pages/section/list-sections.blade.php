<x-filament-panels::page>

    {{--    @if($record->image)--}}
    {{--        <img src="{{ asset($record->image) }}" alt="{{ $record->name }}" style="width: 100px"/>--}}
    {{--    @endif--}}

    <div class="flex flex-row gap-2 w-full">
        @foreach($this->sections as $section)
            <div>
                <input
                        id="{{ $section->id }}"
                        name="section_id"
                        type="radio"
                        class="sr-only peer"
                        wire:model="selectedSection"
                        wire:click="changeSection({{ $section->id }})"
                >
                <label
                        for="{{ $section->id }}"
                        @class([
        'flex h-20 w-28 p-3 bg-white border border-gray-300 rounded-lg transition-all duration-200 ease-in-out cursor-pointer focus:outline-none hover:border-primary-500',
        'border-primary-500' => $section->id == $selectedSection->id

])
                        wire:key="section-{{ $section->id }}"
                >
                    <span class="text-sm">{{ $section->teacher->name ?? 'نام و نام خانوادگی' }}</span>
                </label>
            </div>
        @endforeach
    </div>
    <div class="flex justify-between w-full">
        {{ $this->sections->links()  }}
    </div>

</x-filament-panels::page>
