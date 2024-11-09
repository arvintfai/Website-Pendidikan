<?php

namespace App\Filament\Pages;

use App\Models\Quiz as ModelsQuiz;
use Filament\Pages\Page;
use Illuminate\Http\Request;
use Filament\Forms;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;

use function PHPUnit\Framework\isEmpty;
use function PHPUnit\Framework\isNull;

class Quiz extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament.pages.quiz';

    protected static string $quiz_id;

    public function mount(Request $request)
    {
        if (!$request['kode_akses']) {
            Notification::make()
                ->title('Peringatan!')
                ->body('Kode akses tidak ditemukan')
                ->warning()
                ->send();

            return redirect()->route('filament.belajar.resources.results.index');
        }

        $quiz = ModelsQuiz::where('access_code', $request['kode_akses'])->get('id');

        if (count($quiz) === 0) {
            Notification::make()
                ->title('Peringatan!')
                ->body('Kode akses tidak cocok')
                ->warning()
                ->send();

            return redirect()->route('filament.belajar.resources.results.index');
        }
        static::$quiz_id = $quiz[0]->id;
    }

    public static function getQuiz()
    {
        return ModelsQuiz::find(static::$quiz_id);
    }

    public static function getQuestion(ModelsQuiz $quiz)
    {
        return $quiz->questions()->with('options')->get();
    }
}
