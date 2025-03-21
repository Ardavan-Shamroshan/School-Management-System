<?php

namespace App\Filament\Components\Section;

use App\Models\Academy\Student;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Table;
use Filament\Forms;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Livewire\Attributes\On;
use function App\Support\IRT;

trait ListStudentsTable
{
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->modelLabel(__('Student'))
            ->pluralModelLabel(__('Students'))
            ->query(Student::query()
                ->whereHas(
                    'sections',
                    fn($query) => $query->where('sections.id', $this->filter)
                ))
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('mobile'),
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
                    ->state(fn($record) => $this->resolveStudentSection($record)?->pivot?->note)
                    ->icon('heroicon-o-pencil'),
            ])
            ->actions([
                Tables\Actions\Action::make('invoice')->color('info')->icon('heroicon-o-document-text')->button()->hiddenLabel()->tooltip(__('Invoice'))->extraAttributes(['class' => 'icon-btn']),
                Tables\Actions\Action::make('sendSms')->color('warning')->icon('heroicon-o-chat-bubble-bottom-center-text')->button()->hiddenLabel()->tooltip(__('Send sms'))->extraAttributes(['class' => 'icon-btn']),
                Tables\Actions\Action::make('delete')->color('danger')->requiresConfirmation()->icon('heroicon-o-trash')->button()->hiddenLabel()->tooltip(__('Delete'))->extraAttributes(['class' => 'icon-btn']),
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

    #[On('refresh')]
    public function refreshTable(): void
    {
        $this->resetTable();
    }
}