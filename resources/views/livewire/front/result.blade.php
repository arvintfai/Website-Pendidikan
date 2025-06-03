<!-- resources/views/quiz/result.blade.php -->
<div>
    <section id="products" class="max-w-5xl px-6 py-16 mx-auto my-10 mt-32 shadow-xl mb-36 bg-white/90 rounded-3xl">
        <h2>Hasil Kuis</h2>
        <p>Skor: {{ $score }} / {{ $total_questions }}</p>

        {{-- <ul>
            @foreach ($answers as $answer)
                <li>
                    {{ $answer->question->text }}<br>
                    Jawabanmu: {{ $answer->answer }} -
                    @if ($answer->is_correct)
                        <span style="color:green">Benar</span>
                    @else
                        <span style="color:red">Salah (Jawaban benar: {{ $answer->question->correct_answer }})</span>
                    @endif
                </li>
            @endforeach
        </ul> --}}
    </section>
</div>
