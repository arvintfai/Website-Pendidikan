<?php

namespace App\Filament\Resources;

use Filament\Actions;
use App\Models\Assigment;
use Filament\Support\Colors\Color;
use App\Filament\Resources\SubjectMatterResource\Pages;
use App\Filament\Resources\SubjectMatterResource\RelationManagers;
use App\Models\SubjectMatter;
use App\Models\User;
use Closure;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Filament\Tables\Grouping\Group;

class SubjectMatterResource extends Resource
{
    /**
     * Define string variable
     *
     * @var string|null
     */
    protected static ?string $model = SubjectMatter::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $modelLabel = 'Materi Pembelajaran';

    /**
     * Function to control access feature create new data
     *
     * @return bool
     */
    public static function canCreate(): bool
    {
        return !Auth::user()->isStudent();
    }

    /**
     * Function to control access feature edit data
     *
     * @return bool
     */
    public static function canEdit(Model $record): bool
    {
        return !Auth::user()->isStudent();
    }

    /**
     * Function to modify QueryBuilder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        if (auth()->user()->isStudent()) {
            return parent::getEloquentQuery()->whereHas('student_classes', function ($query) {
                return $query->where('student_class_id', Auth::user()->student_classes->first()->id);
            });
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
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('Nama Materi Pelajaran'),

                Forms\Components\FileUpload::make('file_path')
                    ->label('File PDF')
                    ->nullable()
                    ->deletable()
                    ->acceptedFileTypes(['application/pdf'])
                    ->directory('subject_matters'),

                Forms\Components\Grid::make(1) // Membuat grid dengan 3 kolom
                    ->schema([
                        Forms\Components\Toggle::make('use_text_input')
                            ->label('Gunakan Link Teks sebagai Input Video')
                            ->reactive()
                            ->afterStateUpdated(function (callable $set, $get) {
                                $get('use_text_input') ? $set('video_path', '') : $set('video_link', '');
                            })
                            ->dehydrated(false),

                        Forms\Components\FileUpload::make('video_path')
                            ->label('Upload Video')
                            ->nullable()
                            ->deletable()
                            ->directory('videos')
                            ->visibility('public')
                            ->reactive()
                            ->acceptedFileTypes(['video/mp4', 'video/avi', 'video/quicktime'])
                            ->visibility('public')
                            ->hidden(function (callable $set, $get) {
                                if ($get('use_text_input')) {
                                    return true;
                                } elseif ($get('video_link')) {
                                    if ($get('use_text_input') !== 1) {
                                        $set('use_text_input', 1);
                                    }
                                    return true;
                                } else {
                                    return false;
                                }
                            })
                            ->afterStateUpdated(
                                fn(callable $set) =>
                                $set('video_link', '')
                            )
                            ->dehydratedWhenHidden(true),

                        Forms\Components\TextInput::make('video_link')
                            ->label('Link Video')
                            ->reactive()
                            ->hidden(fn($get) => !$get('use_text_input'))
                            ->afterStateUpdated(
                                fn(callable $set, $record) =>
                                $set('video_path', '')
                            )
                            ->dehydrated(true)
                            ->dehydratedWhenHidden(true),
                    ]),

                Forms\Components\Select::make('subject_matter_has_class')
                    ->label('Materi untuk Kelas')
                    ->relationship('student_classes', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable()
                    ->required(),

                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\Checkbox::make('is_has_assigment')
                            ->label('Apakah materi ini memiliki tugas untuk siswa?')
                            ->reactive()
                            ->afterStateUpdated(
                                function (callable $get, $set) {
                                    if (!$get('is_has_assigment')) {
                                        $set('content_assigment', '');
                                    }
                                }
                            )
                            ->dehydrated(true),
                    ]),
                Forms\Components\TextArea::make('assigment_content')
                    ->label('Keterangan Tugas')
                    ->autosize()
                    ->dehydratedWhenHidden(true)
                    ->visible(function (callable $get, $set) {
                        return $get('is_has_assigment');
                    }),
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
        if (Auth::user()->isStudent()) {
            return $table
                ->columns([
                    Tables\Columns\TextColumn::make('name')
                        ->label('Nama')
                        ->wrap()
                        ->description(fn($record) => $record->assigment_content ? 'Tugas : ' . $record->assigment_content : null)
                        ->color(Color::Violet),

                    Tables\Columns\TextColumn::make('wasOpened')
                        ->label('Status')
                        ->badge()
                        ->getStateUsing(function ($record) {
                            return auth()->user()->wasOpened->contains($record->id)
                                ? 'Sudah Dipelajari'
                                : 'Belum Dipelajari';
                        })
                        ->action(function ($record) {
                            $user = auth()->user();
                            if (!$user->wasOpened->contains($record->id)) {
                                $user->wasOpened()->attach($record->id, ['opened_at' => now()]);
                                Notification::make()
                                    ->title('Status Diperbarui')
                                    ->body('Status telah diubah menjadi "Sudah Dipelajari".')
                                    ->success()
                                    ->send();
                            } else {
                                $user->wasOpened()->detach($record->id);
                                Notification::make()
                                    ->title('Status Diperbarui')
                                    ->body('Status telah diubah menjadi "Belum Dipelajari".')
                                    ->success()
                                    ->send();
                            }
                        })
                        ->colors([
                            'success' => 'Sudah Dipelajari',
                            'danger' => 'Belum Dipelajari',
                        ]),

                    Tables\Columns\TextColumn::make('assigment.file_name')
                        ->label('Tugas')
                        ->url(function ($record) {
                            if ($record->is_has_assigment) {
                                $assigment = Assigment::where('subject_matter_id', $record->id)->where('user_id', auth()->user()->id)->first();
                                if ($assigment)
                                    return Storage::url($assigment->file_name);
                            }
                        })

                        ->getStateUsing(function ($record) {
                            $assigment = Assigment::where('subject_matter_id', $record->id)->where('user_id', auth()->user()->id)->first();
                            if ($record->is_has_assigment)
                                return $assigment ? 'Lihat Tugas Saat ini' : 'Belum mengumpulkan';
                        })
                        ->color(function ($record) {
                            $assigment = Assigment::where('subject_matter_id', $record->id)->where('user_id', auth()->user()->id)->first();
                            return $assigment ? 'success' : 'danger';
                        })
                        ->icon(function ($record) {
                            $assigment = Assigment::where('subject_matter_id', $record->id)->where('user_id', auth()->user()->id)->first();
                            if ($record->is_has_assigment)
                                return $assigment ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle';
                        })
                        ->iconColor(function ($record) {
                            $assigment = Assigment::where('subject_matter_id', $record->id)->where('user_id', auth()->user()->id)->first();
                            return $assigment ? 'success' : 'danger';
                        })
                ])
                ->filters([
                    //
                ])
                ->actions([
                    Tables\Actions\Action::make('store_assigment')
                        ->label(function ($record) {
                            $assigment = Assigment::where('subject_matter_id', $record->id)->where('user_id', auth()->user()->id)->first('file_name');
                            return $assigment ? 'Perbarui Tugas' : 'Kumpulkan Tugas';
                        })
                        ->form([
                            Forms\components\FileUpload::make('file_name')
                                ->label(function ($record) {
                                    $assigment = Assigment::where('subject_matter_id', $record->id)->where('user_id', auth()->user()->id)->first('file_name');
                                    return $assigment ? 'Perbarui Tugas' : 'Unggah Tugas';
                                })
                                ->visibility('public')
                                ->directory('assigment')
                                ->acceptedFileTypes(['application/pdf'])
                                ->required(),
                        ])
                        ->action(function ($record, array $data) {
                            $filePath = $data['file_name'];
                            $user = auth()->user();
                            $assigment = Assigment::where('subject_matter_id', $record->id)->where('user_id', $user->id)->first();
                            if ($assigment) {
                                Storage::disk('public')->delete($assigment->file_name);
                                $assigment->update(['file_name' => $filePath]);
                                Notification::make()
                                    ->title('Tugas berhasil diperbarui!')
                                    ->success()
                                    ->send();
                            } else {
                                $assigment = Assigment::query();
                                $assigment->create([
                                    'subject_matter_id' => $record->id,
                                    'user_id' => $user->id,
                                    'file_name' => $filePath,
                                ]);
                                Notification::make()
                                    ->title('Tugas berhasil diunggah!')
                                    ->success()
                                    ->send();
                            }
                        })
                        ->color(Color::Green)
                        ->visible(fn($record) => $record->is_has_assigment),

                    Tables\Actions\ActionGroup::make([
                        Tables\Actions\Action::make('open_pdf')
                            ->label('Buka PDF')
                            ->action(function ($record) {
                                $user = auth()->user();
                                if (!$user->wasOpened->contains($record->id)) {
                                    $user->wasOpened()->attach($record->id, ['opened_at' => now()]);
                                }
                            })
                            ->modalHeading(fn($record) => $record->name)
                            ->modalWidth('xl')
                            ->modalContent(fn($record) => view('components.show-file', ['file' => Storage::disk('public')->url($record->file_path)]))
                            ->modalSubmitAction(false)
                            ->modalCancelAction(false)
                            ->color(Color::Red)
                            ->hidden(fn($record) => !$record->file_path),

                        Tables\Actions\Action::make('open_video')
                            ->label('Buka Video')
                            ->action(function ($record) {
                                $user = auth()->user();
                                if (!$user->wasOpened->contains($record->id)) {
                                    $user->wasOpened()->attach($record->id, ['opened_at' => now()]);
                                }
                                if ($record->video_path) {
                                    return redirect(Storage::disk('public')->url($record->video_path));
                                } elseif ($record->video_link) {
                                    return redirect($record->video_link);
                                }
                            })
                            ->color(Color::Blue)
                            ->hidden(fn($record) => !$record->video_path && !$record->video_link),
                    ])
                        ->icon('')
                        ->button()
                        ->link()
                        ->label('Lihat Materi'),
                ])
                ->bulkActions([
                    //
                ])
                ->paginated(false)
            ;
        } else {
            return $table
                ->columns([
                    Tables\Columns\TextColumn::make('name')->sortable()->searchable()->label('Nama')->wrap()->description(fn($record) => $record->assigment_content ? 'Tugas : ' . $record->assigment_content : null),
                    Tables\Columns\TextColumn::make('student_classes.name')->label('Kelas')->badge(),
                ])
                ->filters([
                    //
                ])
                ->actions([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\ActionGroup::make([
                        Tables\Actions\Action::make('open_pdf')
                            ->label('Buka PDF')
                            ->modalHeading(fn($record) => $record->name)
                            ->modalWidth('xl')
                            ->modalContent(fn($record) => view('components.show-file', ['file' => Storage::disk('public')->url($record->file_path)]))
                            ->modalSubmitAction(false)
                            ->modalCancelAction(false)
                            ->color(Color::Red)
                            ->hidden(fn($record) => !$record->file_path),
                        Tables\Actions\Action::make('open_video')
                            ->label('Buka Video')
                            ->action(function ($record) {
                                if ($record->video_path) {
                                    return redirect(Storage::url($record->video_path));
                                } elseif ($record->video_link) {
                                    return redirect($record->video_link);
                                }
                            })
                            ->color(Color::Blue)
                            ->hidden(fn($record) => !$record->video_path && !$record->video_link),
                    ])
                        ->label('Materi')
                        // ->button()
                        ->link()
                        ->color('info'),
                ])
                ->bulkActions([
                    Tables\Actions\BulkActionGroup::make([
                        Tables\Actions\DeleteBulkAction::make(),
                    ]),
                ]);
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
            'index' => Pages\ListSubjectMatters::route('/'),
            'create' => Pages\CreateSubjectMatter::route('/create'),
            'edit' => Pages\EditSubjectMatter::route('/{record}/edit'),
        ];
    }
}
