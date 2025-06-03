<section class="max-w-5xl px-6 py-16 mx-auto my-10 shadow-xl bg-white/90 rounded-3xl">
    <h2 class="mb-8 text-3xl font-bold text-center text-indigo-900">Hasil Karya Siswa</h2>
    <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 md:grid-cols-3">
        <!-- Product Card 1 -->

        <article
            class="flex flex-col overflow-hidden transition-shadow duration-300 bg-white rounded-lg shadow-md hover:shadow-lg">
            <img src="https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=600&q=80"
                alt="Article 1" class="object-cover w-full h-48">
            <div class="flex flex-col flex-grow p-6">
                <h3 class="mb-2 text-2xl font-semibold cursor-pointer hover:text-indigo-600">Understanding
                    Tailwind CSS</h3>
                <p class="mb-4 text-sm text-gray-500">By Jane Doe &bull; Jan 10, 2024</p>
                <p class="flex-grow mb-6 text-gray-700">Tailwind CSS is a utility-first CSS framework packed
                    with classes that can be composed to build any design, directly in your markup.</p>
                <a href="{{ route('work_result') }}"
                    class="inline-block mt-auto font-semibold text-indigo-600 hover:underline">Read More
                    &rarr;</a>
            </div>
        </article>
    </div>
    <div class="mt-8 text-center">
        <a href="{{ route('work_results') }}" class="text-xl font-semibold text-indigo-900 hover:underline">Lihat lebih
            banyak
            &raquo;</a>
    </div>
</section>
