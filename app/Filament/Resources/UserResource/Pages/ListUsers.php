<?php

namespace App\Filament\Resources\UserResource\Pages;

use Filament\Actions;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Filament\Resources\UserResource;
use Filament\Forms\Components\Actions as FormsAction;
use Filament\Notifications\Notification;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Storage;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('ImportsStudents')
                ->label('Import Siswa')
                ->color('danger')
                ->icon('heroicon-o-document-arrow-down')
                ->form([
                    FormsAction::make([
                        FormsAction\Action::make('importFileExample')
                            ->label('Unduh contoh file')
                            ->icon('heroicon-o-document-arrow-down')
                            ->url(fn() => Storage::url('contoh-impor-siswa.xlsx')),
                    ]),
                    FileUpload::make('attachment')
                        ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel', 'text/csv']),
                ])
                ->action(function (array $data) {
                    try {
                        $filePath = Storage::disk('public')->path($data['attachment']);

                        Excel::import(new UsersImport, $filePath);

                        Notification::make()
                            ->title('Data berhasil diimpor')
                            ->success()
                            ->send();
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Gagal mengimpor data')
                            ->body('Terjadi kesalahan: ' . $e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }
}
