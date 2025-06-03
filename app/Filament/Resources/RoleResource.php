<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoleResource\Pages;
use App\Filament\Resources\RoleResource\RelationManagers;
use App\Models\Role;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Spatie\Permission\Models\Role as ModelsRole;

class RoleResource extends Resource
{
    /**
     * Define string variable
     *
     * @var string|null
     */
    protected static ?string $model = ModelsRole::class;

    protected static ?string $modelLabel = 'Role';

    protected static ?string $navigationIcon = 'heroicon-m-briefcase';

    protected static ?string $navigationGroup = 'Settings';

    /**
     * Function to controll access this menu
     *
     * @return bool
     */
    public static function canAccess(): bool
    {
        return auth()->user()->isAdmin();
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
                    ->required(),
                Forms\Components\Select::make('permisson')
                    ->label('Izin')
                    ->relationship('permissions', 'name')
                    ->multiple()
                    ->preload()
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
                Tables\Columns\TextColumn::make('permissions.name')->label('Nama Izin')->badge(),
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
     * Routes maker for CRUD
     *
     * @return array
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }
}
