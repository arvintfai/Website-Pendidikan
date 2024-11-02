<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Actions\Modal\Actions\ButtonAction;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use Filament\Tables\Grouping\Group;


class UserResource extends Resource
{
    /**
     * Define string variable
     *
     * @var string|null
     */
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $modelLabel = null;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationGroup = null;

    /**
     * Function to controll access this menu
     *
     * @return bool
     */
    public static function canAccess(): bool
    {
        // static::$slug = auth()->user()->isTeacher() ? 'siswa' : null;

        static::$modelLabel = auth()->user()->isTeacher() ? 'siswa' : null;

        static::$navigationGroup = auth()->user()->isTeacher() ? null : 'Settings';

        return auth()->user()->hasRole(['administrator', 'teacher']);
    }

    /**
     * Function to modify QueryBuilder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        if (auth()->user()->hasRole('teacher')) {
            return parent::getEloquentQuery()->where('id', '!=', Auth::id())->role('student');
        } else {
            return parent::getEloquentQuery()->where('id', '!=', Auth::id());
        }
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
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->hiddenOn('edit'),
                Forms\Components\Select::make('role')
                    ->label('Roles')
                    ->relationship('roles', 'name')
                    ->preload()
                    ->searchable()
                    ->visible(auth()->user()->isAdmin()),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->required()
                    ->hiddenOn('edit'),
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
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('roles.name')->label('Role')->badge()->colors([
                    'danger' => 'guest',
                    'success' => 'administrator',
                    'primary' => 'student',
                    'warning' => 'teacher',
                ])->visible(auth()->user()->isAdmin()),
                Tables\Columns\ViewColumn::make('email_verified_at')->view('tables.columns.status-switcher')
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
                    Tables\Actions\BulkAction::make('Change Role')
                        ->label('Change Role')
                        ->action(fn(Collection $records) => static::changeRole($records))
                        ->form([
                            Forms\Components\Select::make('role')
                                ->label('Pilih Role')
                                ->relationship('roles', 'name')
                                // ->preload()
                                ->searchable()
                                ->required(),
                        ])
                        ->requiresConfirmation()
                        ->color('success')
                        ->icon('heroicon-o-user'),
                ]),
            ])
            ->defaultSort('name')
            // ->groups([Group::make('roles.name')
            //     ->label('Role')])
        ;
    }

    /**
     * Change Role Users with using Tables\Actions\BulkAction
     *
     * @param Collection $users
     *
     * @return void
     */
    protected static function changeRole(Collection $users): void
    {
        foreach ($users as $user) {
            $user->syncRoles('student'); // Pastikan 'role' adalah nama kolom role di tabel users
            $user->save();
        }
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
