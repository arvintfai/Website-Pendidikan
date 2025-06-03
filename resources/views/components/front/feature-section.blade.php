<section x-data="{ activeFeature: 0, setFeature(i) { this.activeFeature = i; }, clearFeature() { this.activeFeature = 0; } }" id="features"
    class="max-w-5xl px-6 pt-12 pb-16 mx-auto space-y-10 shadow-xl bg-white/90 rounded-3xl">
    <h2 class="mb-8 text-3xl font-bold text-center text-indigo-900">Features</h2>
    <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
        <div class="p-6 transition border border-indigo-200 rounded-lg cursor-pointer hover:shadow-xl"
            @mouseenter="setFeature(1)" @mouseleave="clearFeature()">
            <div class="mb-3 text-4xl text-pink-500">⚡</div>
            <h3 class="mb-2 text-xl font-semibold text-indigo-900">Lightning Fast</h3>
            <p class="text-sm text-indigo-700">Experience blazing-fast page loads enhanced with smooth animations.
            </p>
        </div>
        <div class="p-6 transition border border-indigo-200 rounded-lg cursor-pointer hover:shadow-xl"
            @mouseenter="setFeature(2)" @mouseleave="clearFeature()">
            <div class="mb-3 text-4xl text-pink-500">🎨</div>
            <h3 class="mb-2 text-xl font-semibold text-indigo-900">Stunning Design</h3>
            <p class="text-sm text-indigo-700">Modern, clean aesthetics powered by Tailwind CSS and beautiful
                animations.</p>
        </div>
        <div class="p-6 transition border border-indigo-200 rounded-lg cursor-pointer hover:shadow-xl"
            @mouseenter="setFeature(3)" @mouseleave="clearFeature()">
            <div class="mb-3 text-4xl text-pink-500">🤖</div>
            <h3 class="mb-2 text-xl font-semibold text-indigo-900">Interactive UI</h3>
            <p class="text-sm text-indigo-700">Engage users with responsive components powered by Alpine.js.</p>
        </div>
    </div>
    <div class="max-w-3xl p-6 mx-auto mt-12 bg-indigo-100 rounded-lg animate__animated"
        :class="{
            'animate__fadeIn': activeFeature !== 0,
            'hidden': activeFeature === 0
        }"
        key="feature-detail">
        <template x-if="activeFeature === 1">
            <p class="text-lg text-center text-indigo-900">
                Lightning-fast performance means users never wait. Your pages load in an instant with perfectly
                timed transitions.
            </p>
        </template>
        <template x-if="activeFeature === 2">
            <p class="text-lg text-center text-indigo-900">
                Designed to impress with vibrant colors, sleek typography, and modern minimalism, everything matches
                your style.
            </p>
        </template>
        <template x-if="activeFeature === 3">
            <p class="text-lg text-center text-indigo-900">
                Components react instantly to user input, giving an intuitive, dynamic experience on any device.
            </p>
        </template>
    </div>
</section>
