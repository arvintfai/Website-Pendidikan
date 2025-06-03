<?php

namespace App\Filament\Resources\ResultResource\Pages;

use App\Filament\Resources\ResultResource;
use App\Models\Quiz;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;

class ListResults extends ListRecords
{
    /**
     * Define variable with data type string
     *
     * @var string
     */
    protected static string $resource = ResultResource::class;

    /**
     * Schema for set the Header Action on page list
     *
     * @return array
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('Join Quiz')
                ->form([
                    Forms\Components\TextInput::make('access_code')
                        ->label('Masukkan Kode Akses')
                        ->autocapitalize('characters')
                        ->length(8)
                        ->required(),
                ])
                ->action(
                    function (array $data, Form $form) {
                        $quizzes = Quiz::all();
                        foreach ($quizzes as $quiz) {
                            if ($quiz->access_code === strtoupper($data['access_code'])) {
                                return redirect()->route('filament.belajar.pages.quiz', ['kode_akses' => $quiz->access_code]);
                            }
                        }

                        Notification::make()
                            ->title('Gagal bergabung')
                            ->body('Kuis tidak ditemukan!')
                            ->danger()
                            ->send();
                    }
                )
        ];
    }
}
