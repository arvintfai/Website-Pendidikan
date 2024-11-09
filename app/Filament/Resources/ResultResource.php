<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ResultResource\Pages;
use App\Filament\Resources\ResultResource\RelationManagers;
use App\Models\Result;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ResultResource extends Resource
{
    protected static ?string $model = Result::class;

    protected static ?string $modelLabel = 'Quiz';

    protected static ?string $navigationIcon = 'heroicon-o-star';

    /**
     * Function to controll access this menu
     *
     * @return bool
     */
    public static function canAccess(): bool
    {
        return auth()->user()->isStudent();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('quiz.tittle')
                    ->label('Kuis')
                    ->wrap()
                    ->description(fn($record) => $record->quiz->description ? 'Deskripsi : ' . $record->quiz->description : null),
                Tables\Columns\TextColumn::make('score')
                    ->color(function ($state) {
                        if ($state >= 80)
                            return 'success';
                        if ($state < 80 && $state >= 50)
                            return 'warning';
                        if ($state < 50)
                            return 'danger';
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu Submit')
                    ->formatStateUsing(fn($record) => Carbon::parse($record->created_at)
                        ->locale('id')
                        ->translatedFormat('l, d M Y H:i'))
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListResults::route('/'),
            // 'create' => Pages\CreateResult::route('/create'),
            // 'edit' => Pages\EditResult::route('/{record}/edit'),
        ];
    }
}
