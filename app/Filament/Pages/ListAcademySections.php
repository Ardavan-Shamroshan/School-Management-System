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

class ListAcademySections extends Page implements HasForms, HasTable
{
    use InteractsWithForms,
        InteractsWithTable,
        WithFileUploads;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static string  $view           = 'filament.pages.list-academy-sections';
    public ?array            $data           = [];
    public ?AcademySection   $record;

    public function mount(): void
    {
        $this->record = new AcademySection;

        $this->form->fill(
            $this->record->toArray()
        );
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(
                    fn() => $this->record->exists ? __('Edit academy section') : __('Create academy section')
                )
                    ->schema([
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

                        Forms\Components\Actions::make([
                            Forms\Components\Actions\Action::make('save')->submit('save'),
                            Forms\Components\Actions\Action::make('clear')
                                ->color('danger')
                                ->outlined()
                                ->visible(fn() => $this->record->exists)
                                ->action(fn() => $this->dispatch('record-cleared'))
                        ])
                    ])
            ])
            ->statePath('data');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(AcademySection::query())
            ->defaultPaginationPageOption(50)
            ->columns([
                ImageColumn::make('image'),
                TextColumn::make('name')->searchable(),
                TextColumn::make('type')->searchable()->badge(),
                TextColumn::make('description')->searchable(),
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

        $this->record->forceFill([
            'image'       => $data['image'],
            'name'        => $data['name'],
            'type'        => $data['type'],
            'description' => $data['description'],
        ]);

        $this->record->save();

        Notification::make('saved')->success()->title(__('Successful'))->send();
    }

    #[On('record-selected'), On('record-cleared')]
    public function updatedRecord(?AcademySection $record): void
    {
        $this->record = $record;

        $this->form->fill(
            $record->toArray()
        );
    }

    public function getTitle(): string|Htmlable
    {
        return __('Academy sections');
    }

    public static function getNavigationLabel(): string
    {
        return __('Academy sections');
    }

    public function getBreadcrumbs(): array
    {
        return [
            Dashboard::getUrl()           => __('Dashboard'),
            ListAcademySections::getUrl() => __('Academy sections'),
            '0'                           => __('Academy sections list')
        ];
    }
}
