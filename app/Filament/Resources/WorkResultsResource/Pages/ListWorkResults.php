<?php

namespace App\Filament\Resources\WorkResultsResource\Pages;

use App\Filament\Resources\WorkResultsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWorkResults extends ListRecords
{
    protected static string $resource = WorkResultsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
