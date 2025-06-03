<div>
    <section id="products" class="max-w-5xl px-6 py-16 mx-auto my-10 mt-32 shadow-xl mb-36 bg-white/90 rounded-3xl">
        <div class="max-w-xl p-6 mx-auto shadow-md bg-pink-50 rounded-2xl">
            @if (!$quizEnded)
                <h2 class="mb-4 text-2xl font-bold text-indigo-600">Pertanyaan {{ $currentQuestionIndex + 1 }}</h2>

                <p class="mb-6 text-lg text-gray-800">{{ $question->question_text }}</p>

                <div class="space-y-3">
                    @foreach ($question->options as $option)
                        <label
                            class="flex items-center p-3 transition-all bg-white border rounded-lg shadow-sm cursor-pointer hover:border-indigo-500">
                            <input name="answer" type="radio" wire:model="selectedAnswer" value="{{ $option->id }}"
                                class="mr-3 text-indigo-600 form-radio">
                            <span class="text-gray-700">{{ $option->option_text }}</span>
                        </label>
                    @endforeach
                </div>

                <div class="mt-6 text-right">
                    <button wire:click="submitAnswer" @disabled(!$selectedAnswer)"
                        class="px-6 py-2 text-white transition bg-indigo-600 rounded-xl hover:bg-indigo-700 disabled:opacity-50">
                        Selanjutnya
                    </button>
                </div>
            @else
                <p class="mb-6 text-xl text-gray-800">
                    Skor kamu:
                    <span class="font-semibold text-indigo-600">{{ $score }}</span>
                    dari
                    <span class="font-semibold text-indigo-600">{{ $total_questions }}</span>
                </p>

                {{-- {{ $studentAnswers }} --}}

                <ul class="space-y-4">
                    @foreach ($studentAnswers as $answer)
                        <li
                            class="p-4 bg-white rounded-lg shadow border-l-4
                                   {{ $answer->options->is_correct ? 'border-green-400' : 'border-red-400' }}">
                            <p class="font-semibold text-gray-900">{{ $answer->questions->question_text }}</p>
                            <p>
                                Jawabanmu:
                                <span
                                    class="{{ $answer->options->is_correct ? 'text-green-600' : 'text-red-600' }}  font-medium">
                                    {{ $answer->options->option_text }}
                                </span>
                            </p>
                            @if (!$answer->options->is_correct)
                                <p class="text-sm text-gray-600">
                                    Jawaban benar: <span class="font-semibold text-green-600"
                                        x-text="$wire.getTrueAnswer({{ $answer->questions->id }})"></span>
                                    <span wire:loading wire:target="getTrueAnswer">
                                        Loading ...
                                    </span>
                                </p>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

    </section>
</div>
