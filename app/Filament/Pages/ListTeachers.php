<?php

namespace App\Filament\Pages;

use App\Enums\GenderEnum;
use App\Enums\UserRole;
use App\Models\Academy\Teacher;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables\Actions;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Livewire\Attributes\On;

class ListTeachers extends ListRecords
{

    protected static ?string $model                    = Teacher::class;
    protected static ?string $navigationIcon           = 'heroicon-o-briefcase';
    protected static bool    $shouldRegisterNavigation = true;

    public function getFormComponents(): array
    {
        return [
            Forms\Components\TextInput::make('name')->prefixIcon('heroicon-o-user')->required(),
            Forms\Components\TextInput::make('mobile')->prefixIcon('heroicon-o-phone'),

            Forms\Components\Section::make(__('Optional information'))
                ->collapsible()
                ->schema([
                    Forms\Components\TextInput::make('father_name')->prefixIcon('heroicon-o-user'),
                    Forms\Components\TextInput::make('second_mobile')->placeholder(__('Mobile'))->label(__('Mobile'))->prefixIcon('heroicon-o-phone'),
                    Forms\Components\TextInput::make('address')->prefixIcon('heroicon-o-map-pin'),
                    Forms\Components\Select::make('gender')->options(GenderEnum::class)->prefixIcon('heroicon-o-identification'),

                    Forms\Components\Repeater::make('info')
                        ->label(__('Extra information'))
                        ->schema([
                            Forms\Components\TextInput::make('key'),
                            Forms\Components\TextInput::make('value'),
                        ])
                        ->columns()
                ])->columns(1),
        ];
    }

    public function getTableSchema(): array
    {
        return [
            TextColumn::make('name')->searchable(),
            TextColumn::make('mobile')->searchable(),
            TextColumn::make('address'),
            TextColumn::make('gender')->badge(),

            TextColumn::make('info')
                ->listWithLineBreaks()
                ->limitList(2)
                ->expandableLimitedList()
                ->formatStateUsing(fn(array $state) => collect($state)->implode(': ')),
        ];
    }

    public function getTitle(): string|Htmlable
    {
        return __('Teachers');
    }

    public static function getNavigationLabel(): string
    {
        return __('Teachers');
    }
}
