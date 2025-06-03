<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use App\Exports\UsersExport;
use App\Models\StudentClass;
use Filament\Resources\Resource;
use Spatie\Permission\Models\Role;
use Filament\Tables\Grouping\Group;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Validation\Rules\File;
use App\Filament\Exports\UserExporter;
use App\Filament\Imports\UserImporter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use App\Filament\Resources\UserResource\Pages;
use Filament\Actions\Exports\Enums\ExportFormat;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Pages\Actions\Modal\Actions\ButtonAction;
use App\Filament\Resources\UserResource\RelationManagers;

class UserResource extends Resource
{
    /**
     * Define string variable
     *
     * @var string|null
     */
    protected static ?string $model = User::class;

    protected static ?string $slug = 'users';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $modelLabel = null;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationGroup = null;

    public static function getSlug(): string
    {
        // return static::isTeacher() ? 'siswa' : 'users';
        return self::$slug;
    }

    /**
     * Function to controll access this menu
     *
     * @return bool
     */
    public static function canAccess(): bool
    {
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
                    ->label('Nama')
                    ->required(),
                Forms\Components\TextInput::make('email')
                    ->label('Nama pengguna')
                    // ->email()
                    ->required(),

                Forms\Components\Select::make('roles')
                    ->label('Roles')
                    ->relationship('roles', 'name')
                    ->required()
                    ->preload()
                    ->options(
                        function () {
                            if (auth()->user()->isTeacher())
                                return Role::where('name', 'student')->pluck('name', 'id')->toArray();
                            else
                                return Role::all()->pluck('name', 'id')->toArray();
                        }
                    )
                    ->default(
                        fn() => Role::where('name', 'student')->pluck('id')->first()
                    )
                    // ->default('4')
                    ->visible(fn() => auth()->user()->hasRole(['administrator', 'teacher']))
                    ->dehydrated()
                    ->dehydratedWhenHidden()
                    ->hiddenOn('edit'),

                Forms\Components\TextInput::make('password')
                    ->password()
                    ->required()
                    ->hiddenOn('edit'),

                Forms\Components\TextInput::make('remember_token')
                    ->required(function (callable $get) {
                        return !$get('remember_token') ? false : true;
                    })
                    ->disabled()
                    ->visible(function (callable $get, $set) {
                        if (!$get('remember_token')) {
                            $set('remember_token', Str::random(10));
                        }
                        return auth()->user()->hasRole(['administrator']);
                    })
                    ->dehydrated()
                    ->dehydratedWhenHidden()
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
            ->headerActions([
                // Tables\Actions\ExportAction::make()
                //     ->exporter(UserExporter::class)
                // Tables\Actions\ImportAction::make()
                //     ->importer(UserImporter::class)
                //     ->fileRules([
                //         File::types(['csv', 'xls'])
                //     ])
            ])
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->label('Nama'),
                Tables\Columns\TextColumn::make('email')->label('Nama Pengguna'),
                Tables\Columns\TextColumn::make('student_classes.name')->label('Kelas')->hidden(auth()->user()->isAdmin())->placeholder('Tidak terikat kelas manapun'),
                Tables\Columns\TextColumn::make('roles.name')->label('Role')->badge()->colors([
                    'danger' => 'guest',
                    'success' => 'administrator',
                    'primary' => 'student',
                    'warning' => 'teacher',
                ])->visible(auth()->user()->isAdmin()),
                Tables\Columns\ViewColumn::make('email_verified_at')->view('tables.columns.status-switcher')->visible(false)
            ])
            ->filters([
                Tables\Filters\Filter::make('Kelas')
                    ->label('Filter Kelas')
                    ->form([
                        Forms\Components\Select::make('kelas')
                            ->options(function () {
                                $kelas = StudentClass::all()->pluck('name', 'id')->toArray();
                                return $kelas + ['has_no_class' => 'Tanpa Kelas'];
                            })
                    ])
                    // ->relationship('student_classes', 'name')
                    ->query(function ($data, Builder $query) {
                        if ($data['kelas'] === 'has_no_class') {
                            return $query->whereDoesntHave('student_classes');
                        } elseif ($data['kelas'] === '1' || $data['kelas'] === '2') {
                            return $query->whereHas('student_classes', function (Builder $query) use ($data) {
                                $query->where('student_class_id', $data);
                            })->pluck('name', 'id');
                        } else {
                            return $query;
                        }
                    }),
                // Tables\Filters\Filter::make('has_no_class')->label('Belum punya kelas')->baseQuery(fn(Builder $query) => $query->whereDoesntHave('student_classes')),
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
                        ->icon('heroicon-o-user')
                        ->hidden(auth()->user()->isTeacher()),
                    Tables\Actions\BulkAction::make('export')
                        ->label('Export to Excel')
                        ->icon('heroicon-o-document-arrow-down')
                        ->action(
                            function (Collection $records) {
                                return Excel::download(new UsersExport($records), 'student-' . now() . '.xlsx');
                            }
                        )
                ]),
                // Tables\Actions\ExportBulkAction::make()->exporter(UserExporter::class)
            ])
            ->defaultPaginationPageOption(25)
            ->extremePaginationLinks()
            ->defaultSort('name')
            ->emptyStateHeading('Kosong')
            ->emptyStateDescription('Data tidak ada')
            ->emptyStateIcon('heroicon-o-users')
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
