<?php

namespace App\Filament\Pages\User;

use App\Enums\UserRole;
use App\Filament\Pages\Dashboard;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables\Table;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Actions;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Attributes\On;

class ListUsers extends Page implements HasForms, HasTable
{
    use InteractsWithForms,
        InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static string  $view           = 'filament.pages.user.list-users';
    public ?array            $data           = [];
    public ?User             $record;

    public function mount(): void
    {
        $this->record = new User;


        $this->form->fill(
            $this->record->toArray()
        );
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(
                    fn() => $this->record->exists ? __('Edit user') : __('Create user')
                )
                    ->schema([
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
                            ->options(UserRole::class)
                            ->default(UserRole::USER)
                            ->hintIcon('heroicon-o-shield-exclamation'),

                        Forms\Components\Actions::make([
                            Forms\Components\Actions\Action::make('save')->submit('save'),
                            Forms\Components\Actions\Action::make('clear')
                                ->color('danger')
                                ->outlined()
                                ->visible(fn() => $this->record->exists)
                                ->action(fn() => $this->dispatch('record-cleared'))
                        ])
                    ])
                    ->headerActions([
                        Forms\Components\Actions\Action::make('reset_password')
                            ->form([
                                Forms\Components\TextInput::make('password')
                                    ->password()
                                    ->revealable()
                                    ->confirmed()
                                    ->required(),

                                Forms\Components\TextInput::make('password_confirmation')
                                    ->password()
                                    ->revealable()
                                    ->required(),
                            ])
                            ->action(function (array $data) {
                                $this->record
                                    ->forceFill(['password' => $data['password']])
                                    ->setRememberToken(Str::random(60));

                                $this->record->save();

                                Notification::make()->success()->title(__('Your password has been updated!'))->send();
                            })
                            ->icon('heroicon-o-arrow-path')
                            ->color('danger')
                            ->requiresConfirmation()
                            ->visible(fn() => $this->record->exists)
                    ])

            ])
            ->statePath('data');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(User::query())
            ->defaultPaginationPageOption(50)
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('email')->searchable()->description(fn($record) => verta($record->email_verified_at)->formatDate()),
                TextColumn::make('mobile')->searchable()->description(fn($record) => verta($record->mobile_verified_at)->formatDate()),
                TextColumn::make('role')->badge()->label(__('Access control level')),
                TextColumn::make('roles.name')->badge(),
            ])->actions([
                Actions\Action::make('edit')
                    ->icon('heroicon-o-pencil-square')
                    ->color('warning')
                    ->action(fn($record) => $this->dispatch('record-selected', record: $record)),

                Actions\DeleteAction::make(),
            ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $this->record->name     = $data['name'];
        $this->record->email    = $data['email'];
        $this->record->mobile   = $data['mobile'];
        $this->record->password = 'password';
        $this->record->role     = $data['role'];
        $this->record->save();

        Notification::make('saved')->success()->title(__('Successful'))->send();
    }

    #[On('record-selected'), On('record-cleared')]
    public function updatedRecord(?User $record): void
    {
        $this->record = $record;

        $this->form->fill(
            $record->toArray()
        );
    }

    public function getTitle(): string|Htmlable
    {
        return __('Users');
    }

    public static function getNavigationLabel(): string
    {
        return __('Users');
    }

    public function getBreadcrumbs(): array
    {
        return [
            Dashboard::getUrl() => 'داشبور',
            ListUsers::getUrl() => 'کاربران',
            '0'                 => 'لیست کاربران'
        ];
    }




}
