<?php

namespace App\Filament\Pages;

use App\Enums\GenderEnum;
use App\Models\Academy\Student;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables\Actions;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;

class ListRecords extends Page implements HasForms, HasTable
{
    use InteractsWithForms,
        InteractsWithTable,
        WithFileUploads;

    protected static ?string $navigationIcon           = 'heroicon-o-briefcase';
    protected static string  $view                     = 'filament.pages.list-records';
    protected static bool    $shouldRegisterNavigation = false;
    public ?array            $data                     = [];
    protected static ?string $model;
    public ?Model            $record;

    public function mount(): void
    {
        $this->record = new static::$model;

        $this->form->fill(
            $this->record->toArray()
        );
    }

    public function getModel(): string
    {
        return static::$model;
    }

    public function getModelInstance(): Model
    {
        return app(static::$model);
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
}
