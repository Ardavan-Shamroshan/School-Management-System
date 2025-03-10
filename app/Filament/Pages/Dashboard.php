<?php

namespace App\Filament\Pages;

use App\Enums\AcademySectionType;
use App\Models\Academy\AcademySection;
use App\Models\Academy\Course;
use BackedEnum;
use Filament\Forms\Components\ToggleButtons;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Resources\Concerns\HasTabs;
use Filament\Support\Enums\MaxWidth;
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
use Illuminate\Support\Str;

class Dashboard extends BaseDashboard implements HasForms, HasTable
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
                'xl' => 7
            ])
            ->columns([
                Grid::make()
                    ->columns(1)
                    ->schema([
                        ImageColumn::make('image')->height('100%')->width('100%')
                            ->extraAttributes(['class' => 'rounded-md overflow-hidden transition-all duration-200 ease-in-out hover:scale-110']),
                        Stack::make([
                            TextColumn::make('name')
                                ->searchable()
                                ->limit(15)
                                ->wrap(false)
                                ->tooltip(fn($state) => $state)
                                ->extraAttributes(['class' => 'justify-center']),

                            // TextColumn::make('academySection.name')
                            //     ->searchable()
                            //     ->limit(15)
                            //     ->wrap(false)
                            //     ->tooltip(fn($state) => $state)
                            //     ->extraAttributes(['class' => 'justify-center']),

                            TextColumn::make('enroll')
                                ->default(fn() => new HtmlString(
                                    Blade::render('<x-filament::button href="#" tag="a">'. __('Enroll') .'</x-filament::button>')
                                ))
                                ->extraAttributes(['class' => 'justify-center'])
                        ])
                    ])
            ])
            ->recordUrl('/')
            ->filters([
                Tables\Filters\Filter::make('academy_section')
                    ->form([
                        ToggleButtons::make('type')
                            ->options(AcademySectionType::class)
                            ->default(AcademySectionType::BOYS)
                            ->inline(),
                    ])
                    ->query(function (Builder $query, array $data) {
                        if ($data['type'] == AcademySectionType::BOTH) {
                            return $query;
                        }

                        return $query
                            ->whereHas(
                                'academySection',
                                fn(Builder $query) => $query->where('type', $data['type'])
                            );
                    })
                    ->indicateUsing(function (array $data): ?string {
                        $type = $data['type'];

                        if (! $type) {
                            return null;
                        }

                        if (! ($data['type'] instanceof BackedEnum)) {
                            $type = AcademySectionType::getBy($type);
                        }

                        return str(__('Academy section'))->append(': ')->append($type->getLabel());
                    }),

                // SelectFilter::make('academySection')
                //     ->relationship('academySection', 'name')
                //     ->default(AcademySection::query()->first()->id)
                //     ->native(false)
                //     ->preload()
            ])
            ->filtersFormWidth(MaxWidth::Small)
            ->actions([])
            ->bulkActions([]);
    }

    protected function getTableQuery(): Builder
    {
        return Course::query();
    }

    public function getBreadcrumbs(): array
    {
        return [
            '/' => __('Home'),
            '0' => __('Dashboard'),
        ];
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
