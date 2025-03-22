<?php

namespace App\Filament\Pages;

use App\Models\Academy\AcademySection;
use App\Models\Academy\Course;
use Filament\Forms\Components\Section;
use Filament\Forms;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Str;

class ListCourses extends ListRecords
{
    protected static ?string $model                    = Course::class;
    protected static ?string $navigationIcon           = 'heroicon-o-book-open';
    protected static bool    $shouldRegisterNavigation = true;

    public function getFormComponents(): array
    {
        return [
            Forms\Components\Select::make('academy_section_id')
                ->options(AcademySection::all()->pluck('name', 'id'))
                ->placeholder(__('Academy section'))
                ->hiddenLabel()
                ->required()
                ->prefixIcon('heroicon-o-academic-cap'),

            Forms\Components\TextInput::make('name')
                ->live(onBlur: true)
                ->afterStateUpdated(fn(Forms\Set $set, ?string $state) => $set('slug', $this->record->slug ?? Str::slug($state)))
                // ->placeholder(__('Name'))
                ->hiddenLabel()
                ->prefixIcon('heroicon-o-italic')
                ->required(),

            Forms\Components\TextInput::make('slug')
                ->hintColor('warning')
                ->hintIcon('heroicon-o-exclamation-circle')
                ->hint(__('Slug will be generated based on name'))
                ->placeholder(__('Slug'))->hiddenLabel()->prefixIcon('heroicon-o-link')->required(),

            Forms\Components\Textarea::make('description')->placeholder(__('Description'))->hiddenLabel()->nullable(),

            Forms\Components\FileUpload::make('image'),
        ];
    }

    public function getTableSchema(): array
    {
        return [
            ImageColumn::make('image'),
            TextColumn::make('name')->searchable(),
            TextColumn::make('academySection.type')->searchable()->badge(),
            TextColumn::make('description')->searchable(),
        ];
    }

    public function getFilters(): array
    {
        return [
            SelectFilter::make('academy_section_id')
                ->label(__('Academy section'))
                ->relationship('academySection', 'name')
        ];
    }

    public function getTitle(): string|Htmlable
    {
        return __('Courses');
    }

    public static function getNavigationLabel(): string
    {
        return __('Courses');
    }
}
