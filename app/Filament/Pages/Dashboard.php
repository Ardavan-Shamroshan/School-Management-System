<?php

namespace App\Filament\Pages;

use App\Models\Academy\AcademySection;
use App\Models\Academy\Course;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Resources\Concerns\HasTabs;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Grid;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;

class Dashboard extends BaseDashboard  implements HasForms, HasTable
{
    use InteractsWithForms, HasTabs;
    use Tables\Concerns\InteractsWithTable {
        makeTable as makeBaseTable;
    }

    protected static string $view = 'filament.pages.dashboard';

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->defaultPaginationPageOption(50)
            ->contentGrid([
                'xl' => 6
            ])
            ->columns([
                Grid::make()
                    ->columns(1)
                    ->schema([
                        ImageColumn::make('image')->height('100%')->width('100%')->extraAttributes(['class' => 'rounded-md overflow-hidden']),
                        Stack::make([
                            TextColumn::make('name')
                                ->searchable()
                                ->weight('bold')
                                ->limit(20)
                                ->wrap(false)
                                ->tooltip(fn($state) => $state)
                                ->extraAttributes(['class' => 'justify-center']),

                            TextColumn::make('enroll')
                                ->default(fn() => new HtmlString(
                                    Blade::render('<x-filament::button href="#" tag="a">Enroll</x-filament::button>')
                                ))
                                ->extraAttributes(['class' => 'justify-center'])
                        ])
                    ])
            ])
            ->filters([
                SelectFilter::make('academySection')
                    ->relationship('academySection', 'name')
                    ->default(AcademySection::query()->first()->id)
                    ->native(false)
                    ->preload()
            ])
            ->persistFiltersInSession()
            ->actions([])
            ->bulkActions([]);
    }

    protected function getTableQuery(): Builder
    {
        return Course::query();
    }

    // public function getTabs(): array
    // {

    //     $tabs = [];

    //     foreach (AcademySection::all() as $academySection) {
    //         $tabs[$academySection->id] = Tab::make($academySection->name)
    //             ->badge(Course::query()->whereBelongsTo($academySection)->count())
    //             ->modifyQueryUsing(fn($query) => $query->whereBelongsTo($academySection));
    //     }

    //     return $tabs;
    // }

    // public function getDefaultActiveTab(): string | int | null
    // {
    //     return AcademySection::query()->first()->id;
    // }

    // protected function makeTable(): Table
    // {
    //     return $this->makeBaseTable()
    //         ->query(fn(): Builder => $this->getTableQuery())
    //         ->modifyQueryUsing($this->modifyQueryWithActiveTab(...))
    //         ->recordAction(function (Model $record, Table $table): ?string {
    //             foreach (['view', 'edit'] as $action) {
    //                 $action = $table->getAction($action);

    //                 if (! $action) {
    //                     continue;
    //                 }

    //                 $action->record($record);

    //                 if ($action->isHidden()) {
    //                     continue;
    //                 }

    //                 if ($action->getUrl()) {
    //                     continue;
    //                 }

    //                 return $action->getName();
    //             }

    //             return null;
    //         })
    //         ->recordUrl($this->getTableRecordUrlUsing() ?? function (Model $record, Table $table): ?string {
    //             foreach (['view', 'edit'] as $action) {
    //                 $action = $table->getAction($action);

    //                 if (! $action) {
    //                     continue;
    //                 }

    //                 $action->record($record);

    //                 if ($action->isHidden()) {
    //                     continue;
    //                 }

    //                 $url = $action->getUrl();

    //                 if (! $url) {
    //                     continue;
    //                 }

    //                 return $url;
    //             }
    //             return null;
    //         });
    // }
}
