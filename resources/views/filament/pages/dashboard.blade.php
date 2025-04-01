@php
    use App\Enums\AcademySectionType;
    use App\Filament\Pages\Dashboard;
@endphp

<x-filament-panels::page class="fi-dashboard-page">

    <div class="grid grid-cols-4 gap-4">
        <div class="col-span-3">
            {{ $this->table }}
        </div>

        @if (method_exists($this, 'filtersForm'))
            <x-filament-panels::form class="col-span-1" wire:submit="save">

                {{ $this->filtersForm }}

            </x-filament-panels::form>
        @endif

    </div>


    {{--    @if (method_exists($this, 'filtersForm'))--}}
    {{--        {{ $this->filtersForm }}--}}
    {{--    @endif--}}

    {{--    <x-filament-widgets::widgets :columns="$this->getColumns()" :data="[...property_exists($this, 'filters') ? ['filters' => $this->filters] : [], ...$this->getWidgetData()]" :widgets="$this->getVisibleWidgets()"/>--}}

    {{--    <div>--}}
    {{--        <x-filament-panels::resources.tabs/>--}}

    {{--        <div class="mt-2">--}}
    {{--            {{ $this->table }}--}}
    {{--        </div>--}}

    {{--    </div>--}}

</x-filament-panels::page>
