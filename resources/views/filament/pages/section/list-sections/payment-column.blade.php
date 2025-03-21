@php
    use function App\Support\IRT;
@endphp

<div>
    @php
        $student = $this->resolveStudentSection($getRecord());

        $paid = collect($student?->pivot?->invoices)->sum('paid');
    @endphp

    <div class="flex flex-col gap-y-2 my-2">
        @if($this->section->price && $this->section->price != 0)
            @if($this->section->price == $paid)

                <x-filament::badge
                        color="success"
                        icon="heroicon-o-plus"
                        @click="$dispatch('open-modal', { id: 'payment-modal-{{ $student->id }}' })"
                >
                    {{ IRT($paid) }}
                </x-filament::badge>

                {{--            <x-tag :tag="IRT($paid)" type="success" icon="plus" @click="$dispatch('open-modal', { id: 'payment-modal-{{ $student->id }}' })" dir="ltr" class="hint--top hint--rounded" data-hint="ویرایش پرداختی"/>--}}

            @elseif($paid != 0)
                <x-filament::badge color="success">{{ IRT($paid) }}</x-filament::badge>
                <x-filament::badge
                        color="danger"
                        icon="heroicon-o-plus"
                        @click="$dispatch('open-modal', { id: 'payment-modal-{{ $student->id }}' })"
                >
                    {{ IRT($this->section->price - $paid) }}
                </x-filament::badge>

                {{--            <x-tag :tag="IRT($paid)" type="success" dir="ltr"/>--}}
                {{--            <x-tag :tag="IRT($this->section->price - $paid)" type="error" icon="plus" @click="$dispatch('open-modal', { id: 'payment-modal-{{ $student->id }}' })" dir="ltr" class="hint--top hint--rounded" data-hint="ثبت پرداختی"/>--}}

            @else
                <x-filament::badge
                        color="danger"
                        icon="heroicon-o-plus"
                        @click="$dispatch('open-modal', { id: 'payment-modal-{{ $student->id }}' })"
                >
                    {{ IRT($this->section->price - $paid) }}
                </x-filament::badge>
                {{--            <x-tag :tag="IRT($this->section->price - $paid)" type="error" icon="plus" @click="$dispatch('open-modal', { id: 'payment-modal-{{ $student->id }}' })" dir="ltr" class="hint--top hint--rounded" data-hint="ثبت پرداختی"/>--}}
            @endif

        @else

            @if($paid)
                <x-filament::badge color="success">{{ IRT($paid) }}</x-filament::badge>
                {{--            <x-tag :tag="IRT($paid)" type="success" dir="ltr"/>--}}
            @endif
            <x-filament::badge
                    color="danger"
                    icon="heroicon-o-plus"
                    @click="$dispatch('open-modal', { id: 'payment-modal-{{ $student->id }}' })"
            >
                {{ IRT() }}
            </x-filament::badge>

            {{--        <x-tag :tag="IRT(0)" type="error" icon="plus" @click="$dispatch('open-modal', { id: 'payment-modal-{{ $student->id }}' })" dir="ltr" class="hint--top hint--rounded" data-hint="ثبت پرداختی"/>--}}

        @endif
    </div>
</div>

{{--<x-filament::modal id="payment-modal-{{ $student->id }}"--}}
{{--                   sticky-header--}}
{{--                   sticky-footer--}}
{{--                   width="xl"--}}
{{--                   icon="heroicon-o-information-circle"--}}
{{--                   icon-color="info"--}}
{{-->--}}
{{--    <x-slot name="heading">{{ $student->name }}</x-slot>--}}
{{--    <x-slot name="description"> ثبت پرداختی در تاریخ {{ verta()->format('%B %d، %Y H:i') }} </x-slot>--}}

{{--    <h3>مجموع قابل پرداخت {{ IRT($this->section->price - $paid) }}</h3>--}}

{{--    {{ $this->paymentModalForm(form: $this->paymentModalForm, record: $getRecord()) }}--}}

{{--    <x-slot:footerActions>--}}
{{--        <x-filament::button wire:click="save" class="button h-button is-elevated is-success">Save--}}
{{--        </x-filament::button>--}}
{{--        <x-filament::button @click="$dispatch('close-modal', { id: 'payment-modal-{{ $student->id }}' })" class="button is-elevated is-danger">Close</x-filament::button>--}}
{{--    </x-slot:footerActions>--}}

{{--</x-filament::modal>--}}