<?php

namespace App\Filament\Pages\Section;

use App\Filament\Pages\Dashboard;
use App\Models\Academy\Course;
use App\Models\Academy\Section;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Livewire\{Attributes\Computed, Attributes\On, Attributes\Rule, Attributes\Url, Attributes\Validate, WithPagination};
use Illuminate\Pagination\LengthAwarePaginator;

class ListSections extends Page
{
    use WithPagination;

    protected static string  $view                     = 'filament.pages.section.list-sections';
    protected static ?string $slug                     = '{course:slug}/sections';
    protected static bool    $shouldRegisterNavigation = false;
    public Course            $course;
    public ?Section          $selected;
    public int|string        $perPage                  = 9;

    #[Validate('required|string|filled')]
    public string $name;

    #[Url(as: 'filter', keep: true)]
    public int|string $section;


    public function mount(Course $course): void
    {
        $this->course   = $course;
        $this->selected = $this->selected ?? $this->course->sections()->latest()->first() ?? new Section;
        $this->section  = $this->section ?? $this->selected->id;
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
            Notification::make()->success()->title(__('Successful'))->send();
            $this->dispatch('section-created', id: $section->id);
        }

        $this->dispatch('close-modal', id: 'create-section');
    }

    #[On('section-created'), On('section-deleted')]
    public function changeSection($id): void
    {
        $this->selected = $this->course->sections()->find($id);
        $this->section  = $this->selected->id;
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
            '3'                 => $this->selected->teacher?->name ?? 'مدرس نامشخص'
        ];
    }
}
