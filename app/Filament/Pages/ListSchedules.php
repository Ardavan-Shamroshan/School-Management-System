<?php

namespace App\Filament\Pages;

use App\Models\Academy\AcademySection;
use App\Models\Academy\Course;
use App\Models\Academy\Schedule;
use Filament\Forms\Components\Section;
use Filament\Forms;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Str;

class ListSchedules extends ListRecords
{
    protected static ?string $model                    = Schedule::class;
    protected static ?string $navigationIcon           = 'heroicon-o-clock';
    protected static bool    $shouldRegisterNavigation = true;

    public function getFormComponents(): array
    {
        return [
            Forms\Components\TextInput::make('start_time')
                ->live(onBlur: true)
                ->hiddenLabel()
                ->prefixIcon('heroicon-o-clock')
                ->required(),

            Forms\Components\TextInput::make('end_time')
                ->live(onBlur: true)
                ->hiddenLabel()
                ->prefixIcon('heroicon-o-clock'),
        ];
    }

    public function getTableSchema(): array
    {
        return [
            TextColumn::make('start_time')->searchable(),
            TextColumn::make('end_time')->searchable(),
        ];
    }

    public function getTitle(): string|Htmlable
    {
        return __('Schedules');
    }

    public static function getNavigationLabel(): string
    {
        return __('Schedules');
    }
}
