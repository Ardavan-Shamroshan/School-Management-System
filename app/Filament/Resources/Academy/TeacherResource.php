<?php

namespace App\Filament\Resources\Academy;

use App\Filament\Resources\Academy\StudentResource\Pages\ListStudents;
use App\Filament\Resources\Academy\TeacherResource\Pages\ListTeachers;
use App\Models\Academy\Student;
use App\Models\Academy\Teacher;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;

class TeacherResource extends Resource
{
    protected static ?string $model                    = Teacher::class;
    protected static bool    $shouldRegisterNavigation = false;
    protected static ?string $recordTitleAttribute     = 'name';

    public static function getGlobalSearchResultUrl(Model $record): ?string
    {
        return ListTeachers::getUrl(['filter' => $record->id]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTeachers::route('/'),
        ];
    }

    public static function getModelLabel(): string
    {
        return __('Teacher');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Teachers');
    }
}
