<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AnnouncementResource\Pages;
use App\Filament\Resources\AnnouncementResource\RelationManagers;
use App\Models\Announcement;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AnnouncementResource extends Resource
{
    /**
     * Define string variable
     *
     * @var string|null
     */
    protected static ?string $model = Announcement::class;

    protected static ?string $modelLabel = 'Pengumuman';

    protected static ?string $navigationIcon = 'heroicon-o-megaphone';

    /**
     * Function to controll permission access this menu
     *
     * @return bool
     */
    public static function canAccess(): bool
    {
        // static::$modelLabel = auth()->user()->isStudent() ? 'Nilai Tugas' : 'Tugas';

        return auth()->user()->hasRole(['administrator', 'teacher']);
    }

    /**
     * Form builder for create and edit data
     *
     * @param Form $form
     *
     * @return Form
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Judul')
                    ->required(),
                Forms\Components\DatePicker::make('valid_until')
                    ->label('Berlaku sampai')
                    // ->mask('')
                    ->required(),
                Forms\Components\RichEditor::make('announcement_text')
                    ->label('isi pengumuman')
                    ->disableToolbarButtons([
                        'attachFiles',
                        'blockquote',
                        'codeBlock',
                    ])
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    /**
     * Table builder to create table more faster
     *
     * @param Table $table
     *
     * @return Table
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->searchable(),
                Tables\Columns\TextColumn::make('announcement_text')
                    ->html()
                    ->label('Isi pengumuman'),
                Tables\Columns\TextColumn::make('valid_until')
                    ->label('berlaku sampai')
                    ->formatStateUsing(fn($record) => Carbon::parse($record->valid_until)
                        ->locale('id')
                        ->translatedFormat('l, d M Y'))
                    ->sortable(),
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
     * Define a route list for filament
     *
     * @return array
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAnnouncements::route('/'),
            'create' => Pages\CreateAnnouncement::route('/create'),
            'edit' => Pages\EditAnnouncement::route('/{record}/edit'),
        ];
    }
}
