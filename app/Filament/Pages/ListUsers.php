<?php

namespace App\Filament\Pages;

use App\Enums\UserRole;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Str;

class ListUsers extends ListRecords
{

    protected static ?string $model                    = User::class;
    protected static ?string $navigationIcon           = 'heroicon-o-users';
    protected static bool    $shouldRegisterNavigation = true;

    public function getFormComponents(): array
    {
        return [
                Forms\Components\TextInput::make('name')->placeholder(__('Name'))->hiddenLabel()->prefixIcon('heroicon-o-user')->required(),
                Forms\Components\TextInput::make('email')->placeholder(__('Email'))->hiddenLabel()->prefixIcon('heroicon-o-envelope')->nullable()->email()->unique('users', 'email', ignorable: $this->record),
                Forms\Components\TextInput::make('mobile')->placeholder(__('Mobile'))->hiddenLabel()->prefixIcon('heroicon-o-phone')->nullable()->unique('users', 'mobile', ignorable: $this->record),

                Forms\Components\TextInput::make('password')
                    ->password()
                    ->revealable()
                    ->confirmed()
                    ->required()
                    ->prefixIcon('heroicon-o-key')
                    ->hiddenLabel()
                    ->placeholder(__('Password'))
                    ->hidden(fn() => $this->record->exists),

                Forms\Components\TextInput::make('password_confirmation')
                    ->password()
                    ->revealable()
                    ->required()
                    ->prefixIcon('heroicon-o-key')
                    ->hiddenLabel()
                    ->placeholder(__('Password confirmation'))
                    ->hidden(fn() => $this->record->exists),

                Forms\Components\Radio::make('role')
                    ->hiddenLabel(false)
                    ->options(UserRole::class)
                    ->default(UserRole::USER)
                    ->hintIcon('heroicon-o-shield-exclamation'),
            ];
    }

    public function getTableSchema(): array
    {
        return [
            TextColumn::make('name')->searchable(),
            TextColumn::make('email')->searchable()->description(fn($record) => verta($record->email_verified_at)->formatDate()),
            TextColumn::make('mobile')->searchable()->description(fn($record) => verta($record->mobile_verified_at)->formatDate()),
            TextColumn::make('role')->badge()->label(__('Access control level')),
            TextColumn::make('roles.name')->badge(),
        ];
    }

    public function getTitle(): string|Htmlable
    {
        return __('Users');
    }

    public static function getNavigationLabel(): string
    {
        return __('Users');
    }
}
