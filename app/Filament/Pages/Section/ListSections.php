<?php

namespace App\Filament\Pages\Section;

use App\Filament\Pages\Dashboard;
use App\Models\Academy\AcademySection;
use App\Models\Academy\Course;
use App\Models\Academy\Section;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Livewire\{Attributes\Computed, Attributes\On, Attributes\Url, Volt\Component, WithPagination};
use Illuminate\Pagination\LengthAwarePaginator;


class ListSections extends Page
{
    protected static string  $view                     = 'filament.pages.section.list-sections';
    protected static ?string $slug                     = '{record:slug}/sections';
    protected static bool    $shouldRegisterNavigation = false;
    public Course            $record;
    public AcademySection    $filterModel;
    public Section           $selectedSection;

    #[Url(keep: true)]
    public int|string $filter;

    #[Url(keep: true)]
    public int|string $section;


    public function mount(Course $record): void
    {
        $this->record = $record;

        $this->filterModel = AcademySection::query()->findOr(
            id      : $this->filter,
            callback: fn() => $this->redirectIntended(Dashboard::getUrl(), navigate: true)
        );

        $this->selectedSection = $this->selectedSection ?? $this->record->sections()->latest()->first() ?? new Section;
        $this->section         = $this->section ?? $this->selectedSection->id;

        // $this->dispatch('set-section', id: $this->selectedSection->id);
    }

    #[On('section-created'), On('section-updated'), On('section-deleted')]
    #[Computed]
    public function sections(): LengthAwarePaginator
    {
        return $this->record->sections()->latest()->paginate(10, pageName: 'sections-list-paginator');
    }

    #[On('set-section')]
    public function setSection($id): void
    {
        $this->selectedSection = $this->record->sections()?->find($id) ?? null;
        // $this->form->setSection(section: $this->selected, course: $this->course);
    }

    #[On('section-created'), On('section-deleted')]
    public function changeSection($id): void
    {
        $this->selectedSection = Section::query()->where(['course_id' => $this->record->id, 'id' => $id])->first();
        if ($this->selectedSection) {
            $this->setSection($this->selectedSection->id);
            $this->section = $this->selectedSection->id;

            $this->dispatch('section-changed', id: $this->selectedSection->id);
        } else {
            // $this->somethingWentWrong();
        }
    }

    public function getTitle(): string|Htmlable
    {
        return __('Sections list');
    }

    public function getBreadcrumbs(): array
    {
        return [
            Dashboard::getUrl() => __('Dashboard'),
            '0'                 => $this->filterModel->name,
            '1'                 => $this->record->name,
            '2'                 => __($this->getTitle()),
        ];
    }
}
