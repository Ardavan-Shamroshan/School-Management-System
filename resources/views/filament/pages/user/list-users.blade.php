<x-filament-panels::page>

    <div class="grid grid-cols-3 gap-4">
        <div class="col-span-2">
            {{ $this->table }}
        </div>

        <x-filament-panels::form class="col-span-1" wire:submit="save">
{{--            <x-filament-panels::form.actions :actions="$this->getFormActions()"/>--}}



            {{ $this->form }}

        </x-filament-panels::form>
    </div>

</x-filament-panels::page>
