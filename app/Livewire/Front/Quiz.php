<?php

namespace App\Livewire\Front;

use App\Models\Option;
use App\Models\Quiz as ModelsQuiz;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Quiz extends Component
{
    protected $access_code;
    public $quiz;
    public $questions;
    public $currentQuestionIndex = 0;
    public $answers = [];
    public $selectedAnswer = null;
    public $score = 0;
    public $total_questions;
    public $quizEnded = false;
    public $studentAnswers = [];

    public function mount($quiz, $access_code)
    {
        $this->access_code = $access_code;
        $this->quiz = $quiz;

        // Ambil semua pertanyaan dari database, misalnya dari model Question
        $this->questions = $quiz->questions;
        $this->total_questions = count($quiz->questions);
    }

    public function submitAnswer()
    {
        $questionId = $this->questions[$this->currentQuestionIndex]->id;
        $this->answers[$questionId] = $this->selectedAnswer;
        $this->selectedAnswer = null;

        if ($this->currentQuestionIndex < count($this->questions) - 1) {
            $this->currentQuestionIndex++;
        } else {
            $this->finishQuiz();
        }
    }

    public function finishQuiz()
    {
        // Simpan hasil ke database, logika penilaian, dsb
        // Contoh penyimpanan:
        foreach ($this->answers as $questionId => $answer) {
            $isCorrect = \App\Models\Option::where('id', $answer)->first('is_correct')->is_correct;
            $studendAnswered = \App\Models\StudentAnswer::where('quiz_id', $this->quiz->id)->where('user_id', auth()->user()->id)->where('question_id', $questionId)->first();

            if (!$studendAnswered) {
                \App\Models\StudentAnswer::create([
                    'quiz_id' => $this->quiz->id,
                    'user_id' => auth()->user()->id,
                    'question_id' => $questionId,
                    'option_id' => $answer,
                ]);
            } else {
                $studendAnswered->option_id = $answer;
                $studendAnswered->save();
            }

            if ($isCorrect)
                $this->score++;
        }

        // Simpan skor total (opsional)
        // \App\Models\Result::create([
        //     'user_id' => auth()->user()->id,
        //     'score' => $this->score,
        //     'quiz_id' => $this->quiz->id,
        // ]);

        $this->quizEnded = true;
    }

    public function getTrueAnswer($question_id)
    {
        return Option::where('question_id', $question_id)->where('is_correct', true)->first('option_text')->option_text;
    }

    public function booted() {}

    public function render()
    {
        if ($this->quizEnded)
            $this->studentAnswers = \App\Models\StudentAnswer::where('quiz_id', $this->quiz->id)->where('user_id', auth()->user()->id)->with(['questions', 'options'])->latest()->get();

        return view('livewire.front.quiz', ['question' => $this->questions[$this->currentQuestionIndex], 'studentAnswers' => $this->studentAnswers]);
    }
}
