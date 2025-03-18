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
        <x-filament-panels::resources.tabs class="mb-4"/>

        {{--        <div class="flex flex-wrap gap-2 w-1/2 justify-center text-center mx-auto mb-4">--}}
        {{--            @foreach (AcademySectionType::cases() as $type)--}}
        {{--                @php--}}
        {{--                    $inputId = "{$type->value}-{$type->name}";--}}
        {{--                @endphp--}}

        {{--                <x-filament::button--}}
        {{--                        color="primary"--}}
        {{--                        for="{{ $inputId }}"--}}
        {{--                        grouped--}}
        {{--                        icon="heroicon-o-academic-cap"--}}
        {{--                        tag="a"--}}
        {{--                        :href="Dashboard::getUrl(['tableFilters'=> $type->value])"--}}
        {{--                        outlined--}}
        {{--                >--}}

        {{--                    {{ $type->getLabel() }}--}}

        {{--                </x-filament::button>--}}

        {{--            @endforeach--}}

        {{--        </div>--}}

        {{ $this->table }}
    </div>

</x-filament-panels::page>
