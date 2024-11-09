<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentClassResource\Pages;
use App\Filament\Resources\StudentClassResource\RelationManagers;
use App\Models\StudentClass;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StudentClassResource extends Resource
{
    /**
     * Define string variable
     *
     * @var string|null
     */
    protected static ?string $model = StudentClass::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $modelLabel = 'Kelas';

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
     * Create Form for create and edit
     *
     * @param Form $form
     *
     * @return Form
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama')
                    ->required(),
                Forms\Components\Select::make('user')
                    ->label('Siswa')
                    ->relationship('users', 'name') // Relasi 'roles' dengan nama 'name'
                    ->options(function () {
                        return User::whereHas('roles', function ($query) {
                            $query->where('name', 'student');
                        })
                            ->whereDoesntHave('student_classes')
                            ->pluck('name', 'id');
                    })
                    ->multiple() // Memungkinkan memilih beberapa role sekaligus
                    ->preload() // Preload options untuk mempercepat pencarian
                    ->searchable(),
            ]);
    }

    /**
     * Create table faster
     *
     * @param Table $table
     *
     * @return Table
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->label('Nama'),
                Tables\Columns\TextColumn::make('users.name')->label('Siswa')->badge(),
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
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    /**
     * Routes maker for CRUD
     *
     * @return array
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudentClasses::route('/'),
            'create' => Pages\CreateStudentClass::route('/create'),
            'edit' => Pages\EditStudentClass::route('/{record}/edit'),
        ];
    }
}
