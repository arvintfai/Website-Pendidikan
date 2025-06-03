<?php

namespace App\Filament\Widgets;

use App\Models\Announcement as ModelsAnnouncement;
use Filament\Infolists\Infolist;
use Filament\Infolists;
use Filament\Tables;
use Filament\Tables\Columns\Layout\Panel;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Contracts\Support\Htmlable;

class Announcement extends BaseWidget
{
    /**
     * Define string variable
     *
     * @var string|null
     */
    public static ?string $heading = 'Pengumuman';

    /**
     * Function to define is the table can be loading lazy
     *
     * @return bool
     */
    public static function isLazy(): bool
    {
        return false;
    }

    /**
     * Table builder to make table more faster
     *
     * @param Table $table
     *
     * @return Table
     */
    public function table(Table $table): Table
    {
        return $table
            ->query(
                ModelsAnnouncement::query()->whereDate('valid_until', '>=', now())
            )
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul'),
                Panel::make([
                    Tables\Columns\TextColumn::make('announcement_text')
                        ->label('')
                        ->html(),
                ])->collapsible()
            ])
            ->paginated(false)
            ->emptyStateHeading('Nothing')
            ->emptyStateDescription('Belum ada pengumuman')
            ->emptyStateIcon('heroicon-o-megaphone')
            ->striped()
            ->deferLoading()
        ;
    }

    /**
     * Set the width of widget by column
     *
     * @return int
     */
    public function getColumnSpan(): int|string|array
    {
        return 2;
    }
}
