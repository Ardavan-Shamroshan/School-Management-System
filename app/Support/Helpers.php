<?php

namespace App\Support;

use App\Models\Setting;
use Filament\Notifications\Notification;
use Filament\Forms;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Number;
use Illuminate\Support\Str;
use Schema;

if (! function_exists('App\Support\translate')) {
    function translate($key): string
    {
        return __(
            str($key)
                // ->headline()
                ->replace(['-', '_'], ' ')
                ->lower()
                ->ucfirst()
                ->remove(['.', '_id'])
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


if (! function_exists('IRR')) {
    function IRR($price): false|string
    {
        return Number::currency($price, in: 'IRR', locale: 'fa');
    }
}

if (! function_exists('IRT')) {
    function IRT($price = null, $in = true, $delimiter = ', '): bool|string
    {
        if (Str::contains($price, $delimiter)) {
            $prices      = str($price)->explode($delimiter)->toArray();
            $pricesArray = Arr::map($prices, fn($price) => IRT($price));
            return implode(', ', $pricesArray);
        }

        $price = str(Number::format($price ?? 0, locale: 'fa'));

        if ($in) {
            $price = $price->append(' ')->append(__('IRT'));
        }

        return $price;
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

        // if (Schema::hasColumn($column, 'settings')) {
        $setting = Setting::query()
            ->select($column)
            ->first()?->$column;
        // }

        return $setting ?? null;
    }
}


