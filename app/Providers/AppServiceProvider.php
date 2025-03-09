<?php

namespace App\Providers;

use Filament\Actions\MountableAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\Entry;
use Filament\Tables\Columns\Column;
use Filament\Tables\Filters\BaseFilter;
use Filament\Tables\Filters\QueryBuilder\Constraints\Constraint;
use Filament\Tables\Table;
use Illuminate\Support\ServiceProvider;
use Filament\Tables;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureComponents();
    }

    public function configureComponents(): void
    {
        foreach ([Field::class, BaseFilter::class, Entry::class, Placeholder::class, Column::class, MountableAction::class, Constraint::class] as $component) {
            $component::configureUsing(fn($c) => $c->translateLabel());
        }

        foreach ([Column::class, Entry::class] as $component) {
            $component::configureUsing(fn($c) => $c->placeholder(__('No data')));
        }

        Table::configureUsing(function ($component) {
            $component
                ->deferLoading()
                ->striped()
                ->groupingSettingsInDropdownOnDesktop()
                ->groupingDirectionSettingHidden()
                ->defaultPaginationPageOption(50)
                ->actions([
                    Tables\Actions\ActionGroup::make([
                        Tables\Actions\EditAction::make(),
                        Tables\Actions\ViewAction::make(),

                        Tables\Actions\ActionGroup::make([
                            Tables\Actions\DeleteAction::make(),
                            Tables\Actions\ForceDeleteAction::make(),
                        ])->dropdown(false)
                    ])
                ])
                ->bulkActions([
                    Tables\Actions\BulkActionGroup::make([
                        Tables\Actions\DeleteBulkAction::make(),
                        Tables\Actions\ForceDeleteBulkAction::make(),
                        Tables\Actions\RestoreBulkAction::make(),
                    ])
                ]);
        });

        Section::configureUsing(fn($component) => $component->compact());
        DatePicker::configureUsing(fn($component) => $component->jalali()->default(now())->prefixIcon('heroicon-o-calendar-days'));
        DateTimePicker::configureUsing(fn($component) => $component->default(now())->prefixIcon('heroicon-o-calendar-days'));
        Column::configureUsing(fn($component) => $component->toggleable());
        TextInput::configureUsing(fn($component) => $component->maxLength(255));
        Select::configureUsing(fn($component) => $component->native(false)->searchable()->preload());

        // Tables\Columns\TextColumn::configureUsing(function ($component) {
        //     if (in_array($component->getName(), ['published_at', 'created_at', 'updated_at', 'deleted_at', 'email_verified_at', 'mobile_verified_at', 'last_login'])) {
        //         $component->jalaliDateTime();
        //     }
        // });
    }
}
