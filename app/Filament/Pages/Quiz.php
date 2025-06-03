<?php

namespace App\Filament\Pages;

use App\Models\Quiz as ModelsQuiz;
use App\Models\Result;
use Filament\Pages\Page;
use Illuminate\Http\Request;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Notifications;
use Filament\Support\Colors\Color;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;

use function PHPUnit\Framework\isEmpty;
use function PHPUnit\Framework\isNull;

class Quiz extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament.pages.quiz';

    protected static string $quiz_id;

    public function getHeading(): string|Htmlable
    {
        return '';
    }

    public function mount(Request $request)
    {
        if (!$request['kode_akses']) {
            Notification::make()
                ->title('Peringatan!')
                ->body('Kode akses tidak ditemukan')
                ->danger()
                ->send();

            return redirect()->route('filament.belajar.resources.results.index');
        }

        $quiz = ModelsQuiz::where('access_code', $request['kode_akses'])->get();

        // dd($quiz);

        if (count($quiz) === 0) {
            Notification::make()
                ->title('Peringatan!')
                ->body('Kode akses tidak cocok')
                ->warning()
                ->send();

            return redirect()->route('filament.belajar.resources.results.index');
        } else {
            if (!$quiz[0]->is_avaible) {
                Notification::make()
                    ->title('Information')
                    ->body('Kuis yang ingin Anda akses sedang tidak tersedia')
                    ->info()
                    ->send();

                return redirect()->route('filament.belajar.resources.results.index');
            }
            $is_student_was_submit = Result::where('quiz_id', $quiz[0]->id)->where('user_id', auth()->user()->id)->get();
            if (count($is_student_was_submit) > 0 && !$request['kerjakan_ulang']) {
                Notification::make()
                    ->title('Anda sudah pernah mengerjakan kuis ini!')
                    ->body('Ingin mengerjakan lagi?')
                    ->actions([
                        Notifications\Actions\Action::make('Kerjakan lagi')
                            ->button()
                            ->color(Color::Blue)
                            ->url(route('filament.belajar.pages.quiz', ['kode_akses' => $request['kode_akses'], 'kerjakan_ulang' => true])),
                        Notifications\Actions\Action::make('undo')
                            ->button()
                            ->color('gray')
                            ->close()
                    ])
                    ->persistent()
                    ->info()
                    ->send();

                return redirect()->route('filament.belajar.resources.results.index');
            }
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
