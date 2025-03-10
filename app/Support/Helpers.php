<?php

namespace App\Support;

use App\Models\Setting;
use Filament\Notifications\Notification;
use Filament\Forms;
use Illuminate\Support\Facades\Blade;
use Schema;

if (! function_exists('App\Support\translate')) {
    function translate($key): string
    {
        return __(
            str($key)
                ->headline()
                ->lower()
                ->ucfirst()
                ->before('.')
                ->before('id')
                ->squish()
                ->value()
        );
    }
}

if (! function_exists('error')) {
    function error(string $title = null, string $message = null): void
    {
        Notification::make()
            ->danger()
            ->title($title ?? __('Error'))
            ->body($message)
            ->send();
    }
}

if (! function_exists('saved')) {
    function saved(string $message = null): void
    {
        Notification::make()
            ->success()
            ->title($message ?? __('Saved Successfully'))
            ->send();
    }
}


if (! function_exists('formComponentsConfiguration')) {
    function formComponentsConfiguration(): void
    {
        Forms\Components\Field::configureUsing(function ($component) {
            $component->inlineLabel();
        });

        Forms\Components\Checkbox::configureUsing(fn($component) => $component->inlineLabel(false));
        Forms\Components\Radio::configureUsing(fn($component) => $component->inline()->options([0 => __('No'), 1 => __('Yes')]));
        // SimpleAlert::configureUsing(fn($component) => $component->inlineLabel(false));
    }
}

if (! function_exists('loading')) {
    function loading($target): false|string
    {
        return Blade::render('<x-filament::loading-indicator wire:loading wire:target="' . $target . '" class="h-5 w-5"/>');
    }
}
if (! function_exists('setting')) {
    function setting($column)
    {
        if (! Schema::hasTable('settings')) {
            return null;
        }

        $setting = Setting::query()
            ->select($column)
            ->first();

        return $setting?->$column;
    }
}


