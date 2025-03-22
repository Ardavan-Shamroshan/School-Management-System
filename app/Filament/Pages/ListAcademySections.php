<?php

namespace App\Filament\Pages;

use App\Enums\AcademySectionType;
use App\Models\Academy\AcademySection;
use Filament\Forms\Components\Section;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms;
use Filament\Tables\Actions;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Hamcrest\Core\Set;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use Nette\Utils\Image;

class ListAcademySections extends ListRecords
{
    protected static ?string $model                    = AcademySection::class;
    protected static ?string $navigationIcon           = 'heroicon-o-academic-cap';
    protected static bool    $shouldRegisterNavigation = true;

    public function getFormComponents(): array
    {
        return [
            Forms\Components\Select::make('type')->placeholder(__('Type'))->options(AcademySectionType::class)->hiddenLabel()->prefixIcon('heroicon-o-square-3-stack-3d')->nullable(),

            Forms\Components\TextInput::make('name')
                ->live(onBlur: true)
                ->afterStateUpdated(fn(Forms\Set $set, ?string $state) => $set('slug', $this->record->slug ?? Str::slug($state)))
                ->placeholder(__('Name'))
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
            TextColumn::make('type')->searchable()->badge(),
            TextColumn::make('description')->searchable(),
        ];
    }

    public function getTitle(): string|Htmlable
    {
        return __('Academy sections');
    }

    public static function getNavigationLabel(): string
    {
        return __('Academy sections');
    }
}
