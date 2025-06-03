<x-filament-panels::page>
    <div class="flex items-center justify-center p-4">
        <div class="w-full p-6 bg-white rounded-lg shadow-lg dark:bg-gray-900">
            <h2 class="mb-2 text-lg font-bold text-center text-yellow-600 md:text-2xl">{{ $this->getQuiz()->tittle }}
            </h2>
            <p class="mb-6 text-sm text-center md:text-base">{{ $this->getQuiz()->description }}</p>

            <form action="{{ route('student.quiz.store', $this->getQuiz()) }}" method="POST" class="space-y-6">
                @csrf
                @foreach ($this->getQuestion($this->getQuiz()) as $question)
                    <div class="p-4 mb-6 bg-white rounded shadow dark:bg-gray-800">
                        <h2 class="text-base font-semibold md:text-lg">{{ $question->question_text }}</h2>

                        <div class="mt-2">
                            @foreach ($question->options as $option)
                                <div class="flex items-center mb-2">
                                    <input type="radio" name="answers[{{ $question->id }}]"
                                        value="{{ $option->id }}" id="option-{{ $option->id }}" class="form-radio">
                                    <label for="option-{{ $option->id }}" class="mx-3 text-sm md:text-base">
                                        {{ $option->option_text }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                <!-- Tombol Submit -->
                <button type="submit"
                    class="w-full px-4 py-2 font-semibold text-white rounded-lg bg-primary-500 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-75">
                    Submit Quiz
                </button>
            </form>
        </div>
    </div>
</x-filament-panels::page>
