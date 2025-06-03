<x-front-layout>
    <!-- Hero Section -->
    <header class="flex flex-col items-center justify-center flex-grow max-w-4xl px-6 pt-48 mx-auto text-center pb-36">
        <h1 class="text-5xl font-extrabold leading-tight text-white md:text-6xl animate__animated animate__fadeInDown">
            Buka Potensi Anda Dengan <span class="text-pink-400">Pengalaman</span> Baru
        </h1>
        <p class="max-w-2xl mt-6 text-lg text-indigo-200 delay-1000 md:text-xl animate__animated animate__fadeInUp">
            @auth()
                Selamat datang <span class="font-semibold text-pink-400">{{ auth()->user()->name }}</span>. Belajar dengan
                menyenangkan dan mudah bersama DKV e-Learning.
                Semangat Selalu ya!
            @else
                Yuk Login untuk mulai pengalaman yang menyenangkan dalam proses belajar Anda!
            @endauth
        </p>

        @auth
            <div class="flex gap-4 mt-10">

                <button @click="window.location.href='{{ route('filament.belajar.pages.dashboard') }}'"
                    class="px-8 py-3 text-lg font-semibold text-white transition transform rounded-full shadow-lg bg-violet-500 hover:bg-violet-600 hover:scale-105 focus:outline-none">
                    Go to dashboard
                </button>
                @if (auth()->user()->isStudent())
                    <button @click="showModal = true"
                        class="px-8 py-3 text-lg font-semibold text-white transition transform bg-pink-500 rounded-full shadow-lg hover:bg-pink-600 hover:scale-105 focus:outline-none">
                        Join Quiz
                    </button>
                @endif
            </div>
        @else
            <div class="flex gap-4 mt-10">

                <button @click="window.location.href='{{ route('login') }}'"
                    class="px-8 py-3 mt-10 text-lg font-semibold text-white transition transform bg-pink-500 rounded-full shadow-lg hover:bg-pink-600 hover:scale-105 focus:outline-none animate__animated animate__pulse animate__infinite">
                    Sign In
                </button>
            </div>
        @endauth
    </header>

    @auth
        @if (auth()->user()->isStudent())
            <!-- Announcement Section -->
            <livewire:Front.Announcement />
            <!-- Learning Classes Section -->
            <livewire:Front.LearningClass />
        @else
            <!-- Features Section -->
            <x-front.feature-section />
        @endif
    @else
        <!-- Features Section -->
        <x-front.feature-section />
    @endauth



    <!-- About Section -->
    <section class="max-w-5xl px-6 mx-auto mt-16 space-y-6 text-center text-white">
        <h2 class="text-3xl font-bold">Tentang Kami</h2>
        <p class="max-w-3xl mx-auto text-lg leading-relaxed">
            {{-- We believe web experiences should be engaging, fast, and delightful. Our toolkit combines the power of
            Tailwind CSS, Animate.css, and Alpine.js to help developers and creators bring their vision to life
            effortlessly. --}}
            Produk digital hasil karya mahasiswi STKIP PGRI Pacitan, Rezza Marta Siregar prodi Pendidikan Informatika.
            Menyajikan pengalaman belajar baru yang lebih mudah, interaktif dan menyenangkan.
        </p>
    </section>

    <!-- Work Results Section -->
    <livewire:Front.WorkResults />

    @auth
        <!-- Work Form Section -->
        <x-front.work-form />
    @endauth


    <x-front.modal :title="'Gabung Quiz'">
        <form action="{{ route('quizIndex') }}" method="post">
            @csrf
            <input id="kode_akses" name="kode_akses" type="input"
                class="w-full px-4 py-2 mb-4 border border-indigo-300 rounded focus:outline-none focus:ring-2 focus:ring-pink-400"
                required placeholder="Masukkan kode" maxlength="8" minlength="8" autocomplete="off" />
            <button
                class="px-4 py-2 font-semibold text-white bg-indigo-600 rounded hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-300"
                type="submit">Submit</button>
        </form>
    </x-front.modal>
</x-front-layout>
