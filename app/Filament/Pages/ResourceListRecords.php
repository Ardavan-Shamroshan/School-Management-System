<?php

namespace App\Filament\Pages;

use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Tables\Actions;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\WithFileUploads;

class ResourceListRecords extends Page implements HasForms, HasTable
{
    use InteractsWithForms,
        InteractsWithTable,
        WithFileUploads;

    protected static string $resource;
    protected static string $view = 'filament.pages.list-records';
    public ?array           $data = [];
    public ?Model           $record;

    #[Url(keep: true)]
    public ?string $filter = '';

    public function mount(): void
    {
        $this->record = new (static::getResource()::getModel());

        if ($this->filter) {
            $this->updatedRecord($this->filter);
        }

        $this->form->fill(
            $this->record->toArray()
        );
    }

    public function form(Form $form): Form
    {
        Forms\Components\Field::configureUsing(fn(Forms\Components\Component $component) => $component->hiddenLabel());

        return $form
            ->schema([
                Section::make(
                    fn() => $this->record->exists ? __('Edit') : __('Create')
                )
                    ->schema($this->getFormComponents())
                    ->headerActions($this->getSectionHeaderActions())
                    ->footerActions([
                        Forms\Components\Actions\Action::make('save')->submit('save'),
                        Forms\Components\Actions\Action::make('clear')
                            ->color('danger')
                            ->outlined()
                            ->visible(fn() => $this->record->exists)
                            ->action(fn() => $this->dispatch('record-cleared'))
                    ])
            ])
            ->statePath('data');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(app($this->getModel())::query()->latest())
            ->defaultPaginationPageOption(50)
            ->columns($this->getTableSchema())
            ->filters($this->getFilters())
            ->modelLabel(static::getResource()::getModelLabel())
            ->pluralModelLabel(static::getResource()::getPluralModelLabel())
            ->actions([
                Actions\Action::make('edit')
                    ->icon('heroicon-o-pencil-square')
                    ->color('warning')
                    ->action(fn($record) => $this->dispatch('record-selected', id: $record->id)),

                Actions\DeleteAction::make(),
            ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $this->record->forceFill($data);

        $this->record->save();

        Notification::make('saved')->success()->title(__('Successful'))->send();
    }

    #[On('record-selected'), On('record-cleared')]
    public function updatedRecord($id = null): void
    {
        $record = $this->getModelInstance()->find($id);

        $this->record = $record ?? $this->getModelInstance();

        $this->form->fill(
            $record?->toArray()
        );
    }

    public function getModelInstance(): Model
    {
        return new (static::getResource()::getModel());
    }

    public function getSectionHeaderActions(): array
    {
        return [];
    }

    public function getFilters(): array
    {
        return [];
    }

    public function getTableSchema(): array
    {
        return [];
    }

    public function getFormComponents(): array
    {
        return [];
    }

    public function getBreadcrumbs(): array
    {
        return [
            Dashboard::getUrl() => __('Dashboard'),
            '0'                 => __($this->getTitle()),
        ];
    }

    public function getTitle(): string|Htmlable
    {
        return static::getResource()::getModelLabel();
    }

    public static function getNavigationLabel(): string
    {
        return static::getResource()::getPluralModelLabel();
    }
}
