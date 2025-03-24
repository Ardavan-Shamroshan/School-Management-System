@php
    use App\Enums\AcademySectionType;
    use App\Filament\Pages\Dashboard;
@endphp

<x-filament-panels::page class="fi-dashboard-page">

    @if (method_exists($this, 'filtersForm'))
        {{ $this->filtersForm }}
    @endif

    <x-filament-widgets::widgets :columns="$this->getColumns()" :data="[...property_exists($this, 'filters') ? ['filters' => $this->filters] : [], ...$this->getWidgetData()]" :widgets="$this->getVisibleWidgets()"/>

    <div>
        <x-filament-panels::resources.tabs/>

        <div class="mt-2">
            {{ $this->table }}
        </div>

    </div>

</x-filament-panels::page>
