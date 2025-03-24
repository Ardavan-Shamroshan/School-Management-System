<?php

namespace App\Filament\Resources\Academy\StudentResource\Pages;

use App\Enums\GenderEnum;
use App\Filament\Pages\Dashboard;
use App\Filament\Pages\ResourceListRecords;
use App\Filament\Resources\Academy\StudentResource;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Tables\Actions;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;

class ListStudents extends ResourceListRecords
{
    protected static string $resource = StudentResource::class;
    protected static string $view     = 'filament.pages.list-records';
    public ?array           $data     = [];
    public ?Model           $record;

    public function getFormComponents(): array
    {
        return [
            Forms\Components\TextInput::make('name')->prefixIcon('heroicon-o-user')->required(),
            Forms\Components\TextInput::make('mobile')->prefixIcon('heroicon-o-phone'),

            Forms\Components\Section::make(__('Optional information'))
                ->collapsible()
                ->schema([
                    Forms\Components\TextInput::make('father_name')->prefixIcon('heroicon-o-user'),
                    Forms\Components\TextInput::make('second_mobile')->placeholder(__('Mobile'))->label(__('Mobile'))->prefixIcon('heroicon-o-phone'),
                    Forms\Components\TextInput::make('address')->prefixIcon('heroicon-o-map-pin'),
                    Forms\Components\Select::make('gender')->options(GenderEnum::class)->prefixIcon('heroicon-o-identification'),

                    Forms\Components\Repeater::make('info')
                        ->label(__('Extra information'))
                        ->schema([
                            Forms\Components\TextInput::make('key'),
                            Forms\Components\TextInput::make('value'),
                        ])
                        ->columns()
                ])->columns(1),
        ];
    }

    public function getTableSchema(): array
    {
        return [
            TextColumn::make('name')->searchable(),
            TextColumn::make('mobile')->searchable(),
            TextColumn::make('address'),
            TextColumn::make('gender')->badge(),

            TextColumn::make('info')
                ->listWithLineBreaks()
                ->limitList(2)
                ->expandableLimitedList()
                ->formatStateUsing(fn(array $state) => collect($state)->implode(': ')),
        ];
    }
}
