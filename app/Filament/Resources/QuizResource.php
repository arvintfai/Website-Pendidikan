<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuizResource\Pages;
use App\Filament\Resources\QuizResource\RelationManagers;
use App\Models\Quiz;
use Closure;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class QuizResource extends Resource
{
    /**
     * Define string variable
     *
     * @var string|null
     */
    protected static ?string $model = Quiz::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';

    /**
     * Function to controll access this menu
     *
     * @return bool
     */
    public static function canAccess(): bool
    {
        return auth()->user()->hasRole(['administrator', 'teacher']);
    }

    /**
     * Create form for create and edit data
     *
     * @param Form $form
     *
     * @return Form
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('tittle')
                    ->label('Judul Quiz')
                    ->required(),

                Forms\Components\TextArea::make('description')
                    ->label('Deskripsi singkat')
                    ->rows(3),

                Forms\Components\Toggle::make('is_avaible')
                    ->label('Apakah quiz masih bisa dikerjakan?'),

                Forms\Components\TextInput::make('access_code')
                    ->label('Kode Akses')
                    // ->state(fn() => strtoupper(Str::random(10)))
                    ->visible(fn($get, $set) => !$get('access_code') ? $set('access_code', strtoupper(Str::random(8))) : true)
                    ->readonly(),

                Forms\Components\Grid::make(1)
                    ->schema([
                        Forms\Components\Repeater::make('questions')
                            ->label('Soal')
                            ->relationship('questions')
                            ->schema([
                                Forms\Components\TextInput::make('question_text')
                                    ->label('Pertanyaan')
                                    ->required(),

                                Forms\Components\Repeater::make('options')
                                    ->relationship('options')
                                    ->label('Jawaban')
                                    ->schema([
                                        Forms\Components\TextInput::make('option_text')
                                            ->label('Opsi Jawaban')
                                            ->required(),

                                        Forms\Components\Toggle::make('is_correct')
                                            ->label('Jawaban benar')
                                            ->distinct()
                                            ->inline(false),
                                    ])
                                    ->columns(2)
                                    ->minItems(1)
                                    ->reorderable()
                                    ->reorderableWithButtons()
                            ])
                            ->minItems(1)
                            ->reorderable()
                            ->reorderableWithButtons()
                            ->cloneable()
                            ->grid(2)
                        // ->itemLabel(fn($get) => 'Soal ' . $get('client_id'))
                    ]),
            ]);
    }

    /**
     * Table builder for create table more faster
     *
     * @param Table $table
     *
     * @return Table
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tittle')
                    ->label('Judul')
                    ->description(fn($record) => $record->description),
                Tables\Columns\TextColumn::make('access_code')
                    ->label('Kode Akses')
                    // ->formatStateUsing(fn($state) => Str::snake($state, '-'))
                    ->badge()
                    ->color('info'),
                Tables\Columns\CheckboxColumn::make('is_avaible')
                    ->label('Apakah tersedia?'),
                Tables\Columns\TextColumn::make('questions')
                    ->label('Jumlah soal')
                    ->formatStateUsing(fn($record) => count($record->questions))
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Kosong')
            ->emptyStateDescription('Data tidak ada');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    /**
     * Define route list for filament CRUD
     *
     * @return array
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuizzes::route('/'),
            'create' => Pages\CreateQuiz::route('/create'),
            'edit' => Pages\EditQuiz::route('/{record}/edit'),
        ];
    }
}
