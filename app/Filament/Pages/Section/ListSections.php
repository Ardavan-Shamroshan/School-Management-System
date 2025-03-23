<?php

namespace App\Filament\Pages\Section;

use App\Enums\ScheduleEnum;
use App\Filament\Components\Section\AddStudentForm;
use App\Filament\Components\Section\ListStudentsTable;
use App\Filament\Pages\Dashboard;
use App\Models\Academy\Course;
use App\Models\Academy\Schedule;
use App\Models\Academy\Section;
use App\Models\Academy\Teacher;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Stringable;
use Illuminate\Validation\Rule;
use Livewire\{Attributes\Computed, Attributes\On, Attributes\Url};
use function App\Support\IRT;
use function App\Support\saved;

class ListSections extends Page implements HasForms, HasTable, HasActions
{
    use InteractsWithActions,
        InteractsWithForms,
        AddStudentForm,
        ListStudentsTable;

    protected static string  $view                     = 'filament.pages.section.list-sections';
    protected static ?string $slug                     = '{course:slug}/sections';
    protected static bool    $shouldRegisterNavigation = false;
    public Course            $course;
    public ?Section          $section;
    public int|string        $perPage                  = 10;
    public ?array            $data                     = [];

    #[Url(keep: true)]
    public int|string $filter = '';

    public function mount(Course $course): void
    {
        $this->course = $course;

        $this->section = $this->resolveSection($this->filter);

        $this->filter = $this->resolveFilter();

        $this->form->fill($this->getSection()->toArray());

        $this->addStudentForm->fill();
    }

    public function form(Form $form): Form
    {
        Forms\Components\Field::configureUsing(fn(Forms\Components\Component $component) => $component->inlineLabel(false));

        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->heading(fn() => str(__('Section information'))->when($this->getSection()->name, fn(Stringable $string) => $string->append(': ')->append($this->getSection()->name)))
                    ->schema([
                        Forms\Components\Select::make('teacher_id')
                            ->label('Teacher')
                            ->options(Teacher::all()->pluck('name', 'id'))
                            ->searchable()
                            ->rule(Rule::exists('teachers', 'id')),

                        Forms\Components\DatePicker::make('start_date'),

                        Forms\Components\Select::make('schedules')
                            ->multiple()
                            ->options(ScheduleEnum::class),

                        Forms\Components\Select::make('schedule_id')
                            ->label(__('Start time'))
                            ->options(Schedule::all()->pluck('time', 'id'))
                            ->searchable()
                            ->rule(Rule::exists('schedules', 'id')),

                        Forms\Components\TextInput::make('price')->placeholder('375000')
                            ->numeric()
                            ->suffix(__('تومان'))
                            ->live()
                            ->hint(fn($state) => IRT($state)),
                    ])
                    ->icon('heroicon-o-calendar-days')
                    ->headerActions([
                        Forms\Components\Actions\Action::make('list')->color('info')->icon('heroicon-o-clipboard-document-list')->hiddenLabel()->tooltip(__('List'))->extraAttributes(['class' => 'icon-btn']),
                        Forms\Components\Actions\Action::make('print')->icon('heroicon-o-printer')->hiddenLabel()->tooltip(__('Print'))->extraAttributes(['class' => 'icon-btn']),
                        Forms\Components\Actions\Action::make('rename')
                            ->modalWidth('xl')
                            ->form([
                                Forms\Components\TextInput::make('name')
                                    ->default($this->getSection()->name)
                            ])
                            ->action(fn(array $data) => $this->getSection()->update($data))
                            ->color('warning')
                            ->icon('heroicon-o-pencil-square')
                            ->hiddenLabel()
                            ->tooltip(__('Rename'))
                            ->extraAttributes(['class' => 'icon-btn']),

                        Forms\Components\Actions\Action::make('remove')
                            ->color('danger')
                            ->icon('heroicon-o-trash')
                            ->action('delete')
                            ->hiddenLabel()
                            ->tooltip(__('Remove'))
                            ->extraAttributes(['class' => 'icon-btn'])
                            ->requiresConfirmation()
                            ->action(function () {
                                $this->getSection()->delete();

                                saved();

                                $lastSection = $this->course->sections()->latest()->first();
                                $this->dispatch('section-deleted', id: $lastSection?->id);
                            })
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

        $this->getSection()->update($data);

        saved();
    }

    #[On('section-created'), On('section-updated'), On('section-deleted'), On('students-assigned')]
    #[Computed]
    public function sections(): LengthAwarePaginator
    {
        return $this
            ->course
            ->sections()
            ->latest()
            ->paginate($this->perPage, pageName: 'sectionsPage');
    }

    public function createSectionAction(): Action
    {
        return Action::make('createSection')
            ->form([
                Forms\Components\TextInput::make('name'),
            ])
            ->action(function (array $data) {
                $section = $this->course->sections()->create($data);

                if ($section) {
                    saved();

                    $this->dispatch('section-created', id: $section->id);

                    $this->dispatch('close-modal', id: 'create-section');
                }
            })
            ->label(__('Type section name'))
            ->hiddenLabel()
            ->icon('heroicon-o-plus')
            ->modalWidth('xl')
            ->outlined()
            ->extraAttributes([
                'class' => 'w-full min-h-28 max-h-28 dark:!border-primary-600
                dark:hover:!bg-gray-900 !bg-transparent hover:scale-95 scale-75 hover:!bg-primary-100
                !ring-0 !border-2 !border-dashed !border-primary-500 dark:!border-gray-600
                !transition-all duration-200 ease-in-out cursor-pointer !focus:outline-none'
            ]);
    }

    #[On('section-created'), On('section-deleted')]
    public function changeSection(int|string|null $id): void
    {
        $this->section = $this->resolveSection($id);

        $this->filter = $this->resolveFilter();

        $this->form->fill($this->getSection()->toArray());

        $this->addStudentForm->fill();

        $this->dispatch('resetTable');
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

    public function getSection(): Section
    {
        return $this->section ?? new Section;
    }

    public function resolveSection(int|string|null $id): ?Section
    {
        return $this
            ->course
            ->sections()
            ->where('id', $id)
            ->orWhere('slug', $id)
            ->firstOr(fn() => $this->course->sections()->latest()->first())
            ?? new Section;
    }

    public function resolveFilter()
    {
        return $this->section->slug ?? $this->section->id;
    }
}
