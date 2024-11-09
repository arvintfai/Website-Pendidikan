<?php

namespace App\Http\Controllers;

use App\Models\Option;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\Result;
use App\Models\StudentAnswer;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;

class StudentQuizController extends Controller
{
    public function store(Request $request, Quiz $quiz)
    {
        $studentId = auth()->id();

        $scorePerQuestion = 100 / Question::where('quiz_id', $quiz->id)->count();
        $score = 0;
        // Simpan jawaban siswa
        foreach ($request->input('answers') as $questionId => $optionId) {
            $option = Option::where('id', $optionId)->get('is_correct');

            if ($option[0]->is_correct) {
                $score = $score + $scorePerQuestion;
            }

            StudentAnswer::create([
                'quiz_id' => $quiz->id,
                'user_id' => $studentId,
                'question_id' => $questionId,
                'option_id' => $optionId,
            ]);
        }

        Result::create([
            'quiz_id' => $quiz->id,
            'user_id' => $studentId,
            'score' => $score,
        ]);

        Notification::make()
            ->title('Berhasil Submit')
            ->body('Anda sudah berhasil menjawab kuis!')
            ->success()
            ->send();

        return redirect()->route('filament.belajar.resources.results.index');
    }
}
