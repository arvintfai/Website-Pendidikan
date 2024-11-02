<?php

namespace App\Filament\Resources\SubjectMatterResource\Pages;

use App\Filament\Resources\SubjectMatterResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSubjectMatter extends EditRecord
{
    protected static string $resource = SubjectMatterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
