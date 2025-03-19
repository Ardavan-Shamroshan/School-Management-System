<div class="bottom-0 top-100 left-0 z-20 w-full mx-auto border-t pt-6 dark:border-gray-700 mt-auto flex items-center gap-2">
    <x-filament-panels::user-menu/>

    <div x-show="$store.sidebar.isOpen">
        {{ auth()->user()->name }}
    </div>

</div>