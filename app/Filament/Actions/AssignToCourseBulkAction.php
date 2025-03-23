<?php

namespace App\Filament\Actions;

use App\Models\Academy\AcademySection;
use App\Models\Academy\Course;
use App\Models\Academy\Section;
use App\Models\Academy\Student;
use Filament\Tables;
use Filament\Forms;
use Illuminate\Support\Collection;
use function App\Support\formComponentsConfiguration;
use function App\Support\saved;

class AssignToCourseBulkAction extends Tables\Actions\BulkAction
{
    public static function getDefaultName(): ?string
    {
        return 'assignToCourseBulkAction';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->modalWidth('2xl')
            ->label('Assign to course')
            ->icon('heroicon-o-book-open')
            ->color('info')
            ->form($this->actionForm())
            ->action($this->assignToCourse());
    }

    public function actionForm(): array
    {
        formComponentsConfiguration();

        return [
            Forms\Components\Select::make('academy_section_id')
                ->label(__('Academy section'))
                ->options(AcademySection::all()->pluck('name', 'id'))
                ->prefixIcon('heroicon-o-academic-cap')
                ->searchable()
                ->live()
                ->required()
                ->afterStateUpdated(function (Forms\Set $set) {
                    $set('course_id', null);
                    $set('section_id', null);
                }),

            Forms\Components\Select::make('course_id')
                ->label(__('Course'))
                ->options(
                    fn(Forms\Get $get) => Course::query()
                        ->where('academy_section_id', $get('academy_section_id'))
                        ->pluck('name', 'id')
                )
                ->prefixIcon('heroicon-o-book-open')
                ->searchable()
                ->live()
                ->required()
                ->afterStateUpdated(fn(Forms\Set $set) => $set('section_id', null)),

            Forms\Components\Select::make('section_id')->label('Section')
                ->prefixIcon('heroicon-o-clipboard')
                ->searchable()
                ->options(
                    fn(Forms\Get $get) => Section::query()
                        ->where('course_id', $get('course_id'))
                        ->pluck('name', 'id')
                ),
        ];
    }

    public function assignToCourse(): \Closure
    {
        return function (array $data, Collection $records, array $arguments) {
            $course = Course::query()->find($data['course_id']);

            $section =
                $course->sections()->find($data['section_id']) ??
                $course->sections()->latest()->first() ??
                $course->sections()->create(['name' => $course->sections()->latest()->count() + 1]);

            $currentSection = $arguments['section'];

            $records->each(function (Student $record) use ($course, $section, $currentSection) {
                if ($course->id == $currentSection->course_id) {
                    $record->sections()->updateExistingPivot(
                        $currentSection->id,
                        ['section_id' => $section->id]
                    );
                } else {
                    $record->sections()->syncWithoutDetaching($section->id);
                }
            });

            saved();

            $this->dispatch('students-assigned');
        };
    }
}
