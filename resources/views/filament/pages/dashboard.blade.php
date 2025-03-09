<x-filament-panels::page class="fi-dashboard-page">
    @if (method_exists($this, 'filtersForm'))
        {{ $this->filtersForm }}
    @endif

    <x-filament-widgets::widgets :columns="$this->getColumns()" :data="[...property_exists($this, 'filters') ? ['filters' => $this->filters] : [], ...$this->getWidgetData()]" :widgets="$this->getVisibleWidgets()" />

    <div>
        <div class="mb-4">
            <x-filament-panels::resources.tabs class="mb-4" />
        </div>
        
        {{ $this->table }}
    </div>

</x-filament-panels::page>
