<x-filament-panels::page>
    <div class="flex items-center justify-center p-4">
        <div class="w-full p-6 bg-white rounded-lg shadow-lg dark:bg-gray-900">
            <h2 class="mb-2 text-2xl font-bold text-center text-yellow-600">{{ $this->getQuiz()->tittle }}</h2>
            <p class="mb-6 text-center">{{ $this->getQuiz()->description }}</p>

            <form action="{{ route('student.quiz.store', $this->getQuiz()) }}" method="POST" class="space-y-6">
                @csrf
                @foreach ($this->getQuestion($this->getQuiz()) as $question)
                    <div class="p-4 mb-6 bg-white rounded shadow dark:bg-black">
                        <h2 class="text-lg font-semibold">{{ $question->question_text }}</h2>

                        <div class="mt-2">
                            @foreach ($question->options as $option)
                                <div class="flex items-center mb-2">
                                    <input type="radio" name="answers[{{ $question->id }}]"
                                        value="{{ $option->id }}" id="option-{{ $option->id }}" class="form-radio">
                                    <label for="option-{{ $option->id }}"
                                        class="ml-2">{{ $option->option_text }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                <!-- Tombol Submit -->
                <button type="submit"
                    class="w-full px-4 py-2 font-semibold text-white bg-yellow-600 rounded-lg hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-75">
                    Submit Quiz
                </button>
            </form>
        </div>
    </div>
</x-filament-panels::page>
