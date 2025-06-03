<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WorkResultsResource\Pages;
use App\Filament\Resources\WorkResultsResource\RelationManagers;
use App\Models\work_results;
use App\Models\WorkResults;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;

class WorkResultsResource extends Resource
{
    protected static ?string $model = work_results::class;

    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';

    protected static ?string $label = "Karya Siswa";

    /**
     * Function to controll access this menu
     *
     * @return bool
     */
    public static function canAccess(): bool
    {
        return auth()->user()->hasRole(['teacher']);
    }

    /**
     * Function to modify QueryBuilder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->orderBy('created_at', 'desc');
    }

    public static function canCreate(): bool
    {
        return false;
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
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul'),
                Tables\Columns\TextColumn::make('paragraph')
                    ->label('Paragraf')
                    ->wrap()
                    ->html(),
                Tables\Columns\ImageColumn::make('photo_path')
                    ->label('Foto')
                    ->getStateUsing(fn($record) => str_replace('/storage', '', $record->photo_path))
                    ->url(fn($record) => Storage::url(str_replace('/storage/', '', $record->photo_path))),
                Tables\Columns\TextColumn::make('video_path')
                    ->badge()
                    ->label('Video')
                    ->getStateUsing(fn($record) => $record->video_path ? 'Lihat video' : 'Tidak memiliki video')
                    ->url(fn($record) => $record->video_path ? Storage::url(str_replace('/storage/', '', $record->video_path)) : false),
                Tables\Columns\ToggleColumn::make('showAtFront')->label('Tampilkan di halaman depan?')
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
            'index' => Pages\ListWorkResults::route('/'),
            // 'create' => Pages\CreateWorkResults::route('/create'),
            // 'edit' => Pages\EditWorkResults::route('/{record}/edit'),
        ];
    }
}
