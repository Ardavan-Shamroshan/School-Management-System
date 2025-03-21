<?php

namespace App\Filament\Pages\Section;

use App\Filament\Components\Section\AddStudentForm;
use App\Filament\Components\Section\ListStudentsTable;
use App\Filament\Pages\Dashboard;
use App\Models\Academy\Course;
use App\Models\Academy\Section;
use App\Models\Academy\Teacher;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Contracts\Support\Htmlable;
use Livewire\{Attributes\Computed, Attributes\On, Attributes\Url, Attributes\Validate, WithPagination};
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Stringable;
use Illuminate\Validation\Rule;
use Filament\Forms;
use function App\Support\IRT;
use function App\Support\saved;

class ListSections extends Page implements HasForms, HasTable
{
    use InteractsWithForms,
        AddStudentForm,
        ListStudentsTable;

    protected static string  $view                     = 'filament.pages.section.list-sections';
    protected static ?string $slug                     = '{course:slug}/sections';
    protected static bool    $shouldRegisterNavigation = false;
    public Course            $course;
    public ?Section          $section;
    public int|string        $perPage                  = 9;
    public ?array            $data                     = [];

    #[Validate('required|string|filled')]
    public string $name;

    #[Url(as: 'filter', keep: true)]
    public int|string $filter;

    public function mount(Course $course): void
    {
        $this->course = $course;

        if ($this->filter) {
            $this->section = $this->course->sections()->find($this->filter) ?? null;
        }

        $this->section = $this->section ?? $this->course->sections()->latest()->first() ?? new Section;
        $this->filter  = $this->filter ?? $this->section->id;

        $this->form->fill($this->section->toArray());
        $this->addStudentForm->fill();
    }

    public function form(Form $form): Form
    {
        Forms\Components\Field::configureUsing(fn(Forms\Components\Component $component) => $component->inlineLabel(false));

        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->heading(fn() => str(__('Section information'))->when($this->section->name, fn(Stringable $string) => $string->append(': ')->append($this->section->name)))
                    ->schema([
                        Forms\Components\Select::make('teacher_id')
                            ->label('Teacher')
                            ->options(Teacher::all()->pluck('name', 'id'))
                            ->searchable()
                            ->rule(Rule::exists('teachers', 'id')),

                        Forms\Components\DatePicker::make('start_date'),

                        Forms\Components\Select::make('schedules')
                            ->multiple()
                            ->options([
                                'saturday'  => __('Saturday'),
                                'sunday'    => __('Sunday'),
                                'monday'    => __('Monday'),
                                'tuesday'   => __('Tuesday'),
                                'wednesday' => __('Wednesday'),
                                'thursday'  => __('Thursday'),
                                'friday'    => __('Friday'),
                            ]),

                        Forms\Components\Select::make('start_time')
                            ->native(false)
                            ->options([
                                0 => '09:00 - 10:30',
                                1 => '10:30 - 12:00',
                                2 => '17:00 - 18:30',
                                3 => '18:30 - 20:00',
                                4 => '20:00 - 21:30',
                                5 => '21:30 - 23:00',
                            ]),

                        Forms\Components\TextInput::make('price')->placeholder('375000')
                            ->numeric()
                            ->suffix(__('تومان'))
                            ->live()
                            ->hint(fn($state) => IRT($state)),
                    ])
                    ->icon('heroicon-o-calendar-days')
                    ->headerActions([
                        Forms\Components\Actions\Action::make('search')->color('warning')->icon('heroicon-o-magnifying-glass')->hiddenLabel()->tooltip(__('Search'))->extraAttributes(['class' => 'icon-btn']),
                        Forms\Components\Actions\Action::make('list')->color('info')->icon('heroicon-o-clipboard-document-list')->hiddenLabel()->tooltip(__('List'))->extraAttributes(['class' => 'icon-btn']),
                        Forms\Components\Actions\Action::make('print')->icon('heroicon-o-printer')->hiddenLabel()->tooltip(__('Print'))->extraAttributes(['class' => 'icon-btn']),
                        Forms\Components\Actions\Action::make('rename')->color('stone')->icon('heroicon-o-pencil-square')->hiddenLabel()->tooltip(__('Rename'))->extraAttributes(['class' => 'icon-btn']),
                        $this->deleteAction()
                    ])
                    ->footerActions([
                        Forms\Components\Actions\Action::make('save')->color('success')->submit('save'),
                    ])
                    ->collapsible()
                    ->columns(5)
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $this->section->update($data);

        saved();
    }

    public function deleteAction(): Forms\Components\Actions\Action
    {
        return Forms\Components\Actions\Action::make('remove')
            ->color('danger')
            ->icon('heroicon-o-trash')
            ->action('delete')
            ->hiddenLabel()
            ->tooltip(__('Remove'))
            ->extraAttributes(['class' => 'icon-btn'])
            ->requiresConfirmation()
            ->action(function () {
                $this->section->delete();

                saved();

                $lastSection = $this->course->sections()->latest()->first();
                $this->dispatch('section-deleted', id: $lastSection->id);
            });
    }

    #[On('section-created'), On('section-updated'), On('section-deleted')]
    #[Computed]
    public function sections(): LengthAwarePaginator
    {
        return $this->course->sections()->latest()->paginate($this->perPage);
    }

    public function create(): void
    {
        $data = $this->validate();

        $section = $this->course->sections()->create($data);

        if ($section) {
            saved();

            $this->dispatch('section-created', id: $section->id);

            $this->dispatch('close-modal', id: 'create-section');
        }
    }

    #[On('section-created'), On('section-deleted')]
    public function changeSection(int|string|null $id): void
    {
        $this->section = $this->course->sections()->find($id);

        $this->filter = $this->section->id;

        $this->form->fill($this->section->toArray());
        $this->addStudentForm->fill();

        $this->dispatch('refresh');
    }

    protected function getForms(): array
    {
        return [
            'form',
            'addStudentForm',
        ];
    }

    public function getTitle(): string|Htmlable
    {
        return __('Sections list');
    }

    public function getBreadcrumbs(): array
    {
        return [
            Dashboard::getUrl() => __('Dashboard'),
            '0'                 => $this->course->academySection->name,
            '1'                 => $this->course->name,
            '2'                 => __($this->getTitle()),
            '3'                 => $this->section->teacher?->name ?? 'مدرس نامشخص'
        ];
    }
}
