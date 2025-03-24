<?php

namespace App\Filament\Resources\Academy;

use App\Filament\Pages\Section\ListSections;
use App\Filament\Resources\Academy\StudentResource\Pages\ListStudents;
use App\Models\Academy\Student;
use Filament\Actions\Action;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;

class StudentResource extends Resource
{
    protected static ?string $model                    = Student::class;
    protected static bool    $shouldRegisterNavigation = false;
    protected static ?string $recordTitleAttribute     = 'name';

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'mobile'];
    }

    public static function getGlobalSearchResultUrl(Model $record): ?string
    {
        return ListStudents::getUrl(['filter' => $record->id]);
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            ''                 => '',
            __('Mobile')       => $record->mobile,
            __('Last course')  => $record->lastSection()?->course->name,
            __('Last section') => $record->lastSection()?->name,
            __('Teacher')      => $record->lastSection()?->teacher?->name
        ];
    }

    public static function getGlobalSearchResultActions(Model $record): array
    {
        return [
            Action::make('goToSection')
                ->url(ListSections::getUrl(['course' => $record->lastSection()->course, 'filter' => $record->lastSection()->slug]))
                ->link()
                ->icon('heroicon-m-arrow-long-left')
                ->iconPosition('after')
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStudents::route('/'),
            // 'edit'   => Pages\EditStudent::route('/{record}/edit'),
            // 'create' => Pages\CreateStudent::route('/create'),
            // 'index' => Pages\ListStudents::route('/'),
        ];
    }

    public static function getModelLabel(): string
    {
        return __('Student');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Students');
    }
}
