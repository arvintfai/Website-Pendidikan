<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use App\Filament\Resources\AssigmentResource\Pages;
use App\Filament\Resources\AssigmentResource\RelationManagers;
use App\Models\Assigment;
use App\Models\User;
use Filament\Actions\StaticAction;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Grouping\Group;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AssigmentResource extends Resource
{
    /**
     * Define string variable
     *
     * @var string|null
     */
    protected static ?string $model = Assigment::class;

    protected static ?string $modelLabel = 'Tugas';

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document';

    /**
     * Function to controll permission access this menu
     *
     * @return bool
     */
    public static function canAccess(): bool
    {
        static::$modelLabel = auth()->user()->isStudent() ? 'Nilai Tugas' : 'Tugas';

        return auth()->user()->hasRole(['administrator', 'teacher', 'student']);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    /**
     * Function to modify QueryBuilder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        if (Auth::user()->isStudent()) {
            return parent::getEloquentQuery()->where('user_id', auth()->user()->id);
        } else {
            return parent::getEloquentQuery();
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
                //
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
                Tables\Columns\TextColumn::make('user.name')->label('Siswa')->searchable(!Auth::user()->isStudent())->sortable()->hidden(Auth::user()->isStudent()),
                Tables\Columns\TextColumn::make('user.student_classes.name')->label('Kelas')->badge()->sortable()->color(Color::Blue)->hidden(Auth::user()->isStudent()),
                Tables\Columns\TextColumn::make('subject_matter.name')->label('Materi'),
                Tables\Columns\TextColumn::make('created_at')->label('Dikumpulkan pada')
                    ->getStateUsing(fn($record) => Carbon::parse($record->created_at)
                        ->locale('id')
                        ->translatedFormat('l, d M Y H:i'))
                    ->color(function ($record) {
                        return strtotime($record->created_at) > strtotime($record->subject_matter->due_to) ? 'danger' : null;
                    }),

                // Tables\Columns\ColumnGroup::make('created_at', [
                //     Tables\Columns\TextColumn::make('day')->label('Hari')
                //         // ->dateTime('l', 'Asia/Jakarta')
                //         ->getStateUsing(fn($record) => Carbon::parse($record->created_at)
                //             ->locale('id')
                //             ->translatedFormat('l')),
                //     Tables\Columns\TextColumn::make('created_at')->label('Tanggal')
                //         ->dateTime('d-m-Y', 'Asia/Jakarta')
                //         ->getStateUsing(fn($record) => $record->created_at)
                //         ->sinceTooltip()
                //         ->sortable(),
                //     Tables\Columns\TextColumn::make('time')->label('Waktu')
                //         ->getStateUsing(fn($record) => $record->created_at)
                //         ->dateTime('H:i', 'Asia/Jakarta'),
                // ])
                //     ->label('Dikumpulkan pada'),
                Tables\Columns\TextColumn::make('scores')
                    ->label('Nilai')
                    ->placeholder('Belum ada Nilai')
                    ->badge()
                    ->color(function ($state) {
                        if ($state >= 75) {
                            return Color::Green;
                        } elseif ($state < 75 && $state >= 50) {
                            return Color::Yellow;
                        } elseif ($state < 50) {
                            return Color::Red;
                        }
                    }),
            ])
            ->filters([
                Tables\Filters\Filter::make('scored')->label('Sudah dinilai')->baseQuery(fn(Builder $query) => $query->where('scores', '!=', null)),
                Tables\Filters\Filter::make('hasNoScore')->label('Belum dinilai')->baseQuery(fn(Builder $query) => $query->where('scores', '=', null)),
            ])
            ->actions([
                Tables\Actions\Action::make('viewFile')
                    ->label('Lihat Tugas')
                    ->modalHeading(fn($record) => $record->user->name . ' - ' . $record->user->student_classes[0]->name . ' - ' . $record->subject_matter->name)
                    ->modalWidth('xl')
                    ->modalContent(fn($record) => view('components.show-file', ['file' => Storage::disk('public')->url($record->file_name)]))
                    ->modalSubmitAction(false)
                    // ->modalCancelAction(fn(StaticAction $action) => $action->label('Close'))
                    ->modalCancelAction(false),
                Tables\Actions\Action::make('scores')
                    ->label(fn(Assigment $record) => $record->scores ? 'Perbarui Nilai' : 'Tambahkan nilai')
                    ->icon('heroicon-o-pencil-square')
                    ->fillForm(fn(Assigment $record) => ['scores' => $record->scores])
                    ->form([
                        Forms\Components\TextInput::make('scores')
                            ->label('Nilai')
                            ->numeric()
                            ->inputMode('decimal')
                            ->minValue(1)
                            ->maxValue(100)
                            ->required()
                    ])
                    ->action(function ($record, $data) {
                        $record->update($data);

                        Notification::make()
                            ->title('Berhasil!')
                            ->body('Nilai tugas telah diperbarui!')
                            ->success()
                            ->send();
                    })->hidden(Auth::user()->isStudent()),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->hidden(Auth::user()->isStudent()),
                    Tables\Actions\BulkAction::make('empty_score')
                        ->label('Kosongi nilai')
                        ->action(function (Collection $records) {
                            $records->each->update(['scores' => null]);
                            Notification::make()
                                ->title('Peringatan!')
                                ->body('Nilai dari ' . count($records) . ' data telah dihapus!')
                                ->warning()
                                ->send();
                        })
                        ->icon('heroicon-o-minus-circle')
                        ->color('warning')
                        ->hidden(Auth::user()->isStudent()),

                ]),
            ])
            ->groups([
                Group::make('subject_matter.name')
                    ->label('Materi'),
                // Group::make('student_classes.name')
                //     ->label('Kelas'),
                Group::make('user.name')
                    ->label('Nama Siswa')
            ])
            ->defaultSort('created_at', 'desc')
            ->searchPlaceholder('cari Nama Siswa')
            ->paginated(!Auth::user()->isStudent());
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
            'index' => Pages\ListAssigments::route('/'),
            // 'create' => Pages\CreateAssigment::route('/create'),
            // 'edit' => Pages\EditAssigment::route('/{record}/edit'),
        ];
    }
}
