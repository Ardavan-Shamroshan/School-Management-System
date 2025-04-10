<?php

namespace App\Filament\Components\Section;

use App\Models\Academy\Student;
use Filament\Forms\Concerns\InteractsWithForms;
use Illuminate\Support\Stringable;
use Illuminate\Validation\Rule;
use App\Models\Academy\Teacher;
use Filament\Forms\Form;
use Filament\Forms;

use function App\Support\IRT;
use function App\Support\saved;

trait AddStudentForm
{
    public function addStudentForm(Form $form): Form
    {
        Forms\Components\Field::configureUsing(fn(Forms\Components\Component $component) => $component->inlineLabel(false));

        return $form
            ->schema([
                Forms\Components\Section::make()
                ->extraAttributes(['class' => 'green-header'])
                    ->heading(fn() => str(__('Add student')))
                    ->schema([
                        Forms\Components\TextInput::make('name')->label('Name')->reactive()->required()->prefixIcon('heroicon-o-user'),

                        Forms\Components\TextInput::make('mobile')->placeholder('09123456789')->required()->numeric()->prefixIcon('heroicon-o-phone'),
                        // Forms\Components\TextInput::make('second_mobile')->placeholder('09123456789')->reactive()->nullable()->numeric()->prefixIcon('heroicon-o-phone'),

                        Forms\Components\TextInput::make('paid')->placeholder('375000')
                            ->numeric()
                            ->suffix(__('تومان'))
                            ->live()
                            ->hint(fn($state) => IRT($state)),

                        Forms\Components\TextInput::make('note')->prefixIcon('heroicon-o-pencil')
                    ])
                    ->icon('heroicon-o-user')
                    ->footerActions([
                        Forms\Components\Actions\Action::make('save')->color('success')->submit('addStudent'),
                    ])
                    ->collapsible()
                    ->columns(4)
            ])
            ->statePath('data');
    }

    public function addStudent(): void
    {
        $data = $this->addStudentForm->getState();

        // $student = Student::query()->updateOrCreate(
        //     [
        //         'name'   => $data['name'],
        //         'mobile' => $data['mobile']
        //     ],
        //     ['second_mobile' => $data['second_mobile']]
        // );

        $student = Student::query()->updateOrCreate([
            'name'   => $data['name'],
            'mobile' => $data['mobile']
        ]);

        $student->sections()->syncWithoutDetaching([
            $this->section->id => [
                'note'     => $data['note'],
                'invoices' =>
                    [
                        [
                            'amount'  => $this->section->price,
                            'paid'    => $data['paid'],
                            'paid_at' => now()
                        ],
                    ],
                'paid'     => (
                    $this->section->price &&
                    $this->section->price <= $data['paid']
                ),
            ]
        ]);

        $this->addStudentForm->fill();

        saved();
    }
}