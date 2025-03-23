<?php

namespace App\Filament\Components\Section;

use App\Filament\Actions\AssignToCourseBulkAction;
use App\Models\Academy\Course;
use App\Models\Academy\Section;
use App\Models\Academy\Student;
use Blade;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Table;
use Filament\Forms;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Arr;
use Illuminate\Support\HtmlString;
use Livewire\Attributes\On;
use function App\Support\IRT;
use function App\Support\saved;

trait ListStudentsTable
{
    use InteractsWithTable;

    protected function getTableQuery(): Builder|Relation|null
    {
        return Student::query()
            ->whereHas(
                'sections',
                fn($query) => $query
                    ->where('sections.id', $this->filter)
                    ->orWhere('sections.slug', $this->filter)
            );
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->modelLabel(__('Student'))
            ->pluralModelLabel(__('Students'))
            ->paginated(fn() => $this->getTableQuery()->count() > 25)
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('mobile')->searchable(),
                Tables\Columns\TextColumn::make('payment')
                    ->color(function ($record) {
                        $student = $this->resolveStudentSection($record);
                        $paid    = collect($student?->pivot?->invoices)->sum('paid');

                        if ($this->section->price > $paid) {
                            return 'danger';
                        }

                        return 'success';
                    })
                    ->weight('bold')
                    ->icon('heroicon-o-credit-card')
                    ->action($this->paymentAction())
                    ->state(function ($record) {

                        $student = $this->resolveStudentSection($record);
                        $paid    = collect($student?->pivot?->invoices)->sum('paid');

                        if ($this->section->price == 0) {
                            return IRT($paid);
                        }

                        if ($this->section->price == $paid) {
                            return IRT($paid);
                        }

                        return IRT($this->section->price - $paid);
                    }),

                Tables\Columns\IconColumn::make('paid')
                    ->action($this->togglePaid())
                    ->boolean()
                    ->state(fn($record) => $this->resolveStudentSection($record)?->pivot?->paid),

                Tables\Columns\TextColumn::make('note')
                    ->action($this->noteAction())
                    ->placeholder(new HtmlString(Blade::render("<x-filament::link color='gray' tooltip='" . __('Write note') . "' icon='heroicon-o-pencil'>" . __('No data') . "</x-filament:link >")))
                    ->color('gray')
                    ->state(fn($record) => $this->resolveStudentSection($record)?->pivot?->note)
                    ->icon('heroicon-o-pencil'),
            ])
            ->actions([
                Tables\Actions\Action::make('invoice')->color('info')->icon('heroicon-o-document-text')->button()->hiddenLabel()->tooltip(__('Invoice'))->extraAttributes(['class' => 'icon-btn']),
                Tables\Actions\Action::make('sendSms')->color('warning')->icon('heroicon-o-chat-bubble-bottom-center-text')->button()->hiddenLabel()->tooltip(__('Send sms'))->extraAttributes(['class' => 'icon-btn']),
                Tables\Actions\Action::make('delete')
                    ->action(function ($record) {
                        $record->sections()->detach($this->section->id);
                        saved();
                    })
                    ->requiresConfirmation()
                    ->color('danger')
                    ->icon('heroicon-o-trash')
                    ->button()
                    ->hiddenLabel()
                    ->tooltip(__('Delete'))
                    ->extraAttributes(['class' => 'icon-btn']),
            ])
            ->bulkActions([
                AssignToCourseBulkAction::make()->arguments(['section' => $this->section]),
            ]);
    }

    public function paymentAction()
    {
        return Tables\Actions\Action::make('payment')
            ->modalWidth('xl')
            ->form(function (Student $record) {

                Forms\Components\Field::configureUsing(fn(Forms\Components\Component $component) => $component->inlineLabel(false));

                $student     = $this->resolveStudentSection($record);
                $invoices    = collect($student?->pivot?->invoices)->except('pay');
                $paid        = $invoices->sum('paid');
                $left        = max($this->section->price - $paid, 0);
                $paidColumns = [];

                foreach ($invoices as $key => $invoice) {
                    if (is_array($invoice)) {
                        $paidColumns[] = Forms\Components\TextInput::make($key)
                            ->label(__('Paid'))
                            ->default((int) $invoice['paid'])
                            ->live(onBlur: true)
                            ->suffix(__('IRT'))
                            ->hint(fn($state) => IRT($invoice['paid']))
                            ->helperText(fn() => verta($invoice['paid_at']));
                    }
                }

                $paidColumns[] = Forms\Components\TextInput::make('pay')
                    ->placeholder($left)
                    ->numeric()
                    ->live(onBlur: true)
                    ->suffix(__('IRT'))
                    ->hint(fn($state) => IRT($state))
                    ->helperText(fn() => verta());

                return $paidColumns;
            })
            ->action(function ($data, Student $record) {
                $student  = $this->resolveStudentSection($record);
                $invoices = collect($student?->pivot?->invoices)->except('pay');

                if ($invoices->isNotEmpty()) {
                    foreach ($invoices as $id => $invoice) {
                        foreach (array_filter($data) as $key => $value) {
                            if ($key == $id) {
                                $data[$key] = [
                                    'paid'    => $value,
                                    'paid_at' => $invoice['paid_at'],
                                    'amount'  => $this->section->price
                                ];
                            }
                        }
                    }
                }

                if ($data['pay']) {
                    $data[] = [
                        'paid'    => $data['pay'],
                        'paid_at' => now(),
                        'amount'  => $this->section->price
                    ];
                }

                $record->sections()->updateExistingPivot($this->section->id, [
                    'invoices' => Arr::except($data, 'pay'),
                    'paid'     => (
                        $this->section->price &&
                        $this->section->price <= collect($data)->sum('paid')
                    ),
                ]);

                // $this->dispatch('payment-updated')->to(StudentsList::class);
            });
    }

    public function resolveStudentSection(Student $record)
    {
        return $record->sections()
            ?->where('section_id', $this->section->id)
            ?->first();
    }

    public function noteAction()
    {
        return Tables\Actions\Action::make('note')
            ->modalWidth('xl')
            ->form([
                Forms\Components\TextInput::make('note')
                    ->default(fn($record) => $this->resolveStudentSection($record)?->pivot?->note),
            ])
            ->action(fn(array $data, $record) => $this->section->students()->updateExistingPivot($record->id, $data));
    }

    public function togglePaid(): \Closure
    {
        return function ($record) {
            $student = $this->resolveStudentSection($record);

            $this->section->students()->updateExistingPivot($record->id,
                ['paid' => ! $student?->pivot?->paid]
            );
        };
    }

    #[On('resetTable')]
    public function refreshTable(): void
    {
        $this->resetTable();
    }
}