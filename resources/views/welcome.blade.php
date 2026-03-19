<x-app-layout>
    <div class="text-white font-cairo relative z-10" dir="rtl">
        {{-- Hero Banner Section --}}
        @if ($sliders->isNotEmpty())
            <section
                class="hero-slider relative w-full h-[400px] sm:h-[500px] md:h-[600px] lg:h-[700px] xl:h-[800px] overflow-hidden mt-0">
                {{-- Slides --}}
                @foreach ($sliders as $index => $slide)
                    <div class="absolute left-0 top-0 w-full h-full z-0 transition-opacity duration-700 {{ $index === 0 ? 'opacity-100' : 'opacity-0' }}"
                        data-slide data-choice="{{ $slide->choice }}" data-title="{{ $slide->title }}"
                        data-type="{{ $slide->type }}" data-duration="{{ $slide->duration }}"
                        data-duration-ms="{{ $slide->duration_ms ?? 5000 }}" data-year="{{ $slide->year }}"
                        data-quality="{{ $slide->quality }}" data-episodes="{{ $slide->episodes }}"
                        data-description="{{ $slide->description }}"
                        data-link="{{ route('anime.show', ['mal_id' => $slide->mal_id]) }}">

                        <div class="absolute inset-0 bg-no-repeat bg-center bg-cover"
                            style="background-image: url('{{ asset('storage/' . $slide->image) }}');">
                        </div>



                        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-transparent to-black/80">
                        </div>
                        <div class="absolute inset-0 bg-gradient-to-b from-transparent via-transparent to-black/70">
                        </div>
                    </div>
                @endforeach

                {{-- Dynamic Content + Link --}}
                <style>
                    /* Tambahan untuk slide visibility */
                    [data-slide] {
                        opacity: 0;
                        transition: opacity 0.7s ease-in-out;
                        pointer-events: none;
                    }

                    [data-slide].active {
                        opacity: 1;
                        pointer-events: auto;
                    }
                </style>
                {{-- Dynamic Content + Link Container --}}
                <div class="absolute inset-0 block w-full h-full z-10">
                    {{-- The actual clickable link covering the whole banner --}}
                    <a id="hero-link" href="{{ route('anime.show', ['mal_id' => $sliders->first()->mal_id]) }}" class="absolute inset-0 w-full h-full z-10 focus:outline-none"></a>

                    {{-- The content overlay (pointer-events-none so clicks fall through to the 'a' tag) --}}
                    <div class="relative w-full h-full flex items-center justify-start px-4 sm:px-6 md:px-8 lg:px-12 xl:px-20 overflow-hidden pointer-events-none">
                        {{-- Responsive Background Image Overlay --}}
                        <div class="absolute inset-0 z-0 w-full h-full pointer-events-none">
                            <img src="https://i.imgur.com/7ycA95r.png" referrerpolicy="no-referrer" class="w-full h-full object-cover object-center block md:hidden" alt="Overlay Mobile">
                            <img src="https://i.imgur.com/oHLteLX.png" referrerpolicy="no-referrer" class="w-full h-full object-cover object-center hidden md:block lg:hidden" alt="Overlay Tablet">
                            <img src="https://i.imgur.com/mmbcsXE.png" referrerpolicy="no-referrer" class="w-full h-full object-cover object-center hidden lg:block xl:hidden" alt="Overlay Laptop">
                            <img src="https://i.imgur.com/J94lkDN.png" referrerpolicy="no-referrer" class="w-full h-full object-cover object-center hidden xl:block 2xl:hidden" alt="Overlay Desktop">
                            <img src="https://i.imgur.com/IVDQwc3.png" referrerpolicy="no-referrer" class="w-full h-full object-cover object-center hidden 2xl:block" alt="Overlay 4K">
                        </div>

                        <div
                            class="relative z-10 w-full md:w-[60%] lg:w-[50%] xl:w-[40%] max-w-2xl space-y-3 sm:space-y-4 md:space-y-6 text-right pointer-events-none">
                            <span id="slide-choice" class="inline-block text-xs sm:text-sm text-white/80 font-medium">
                                {{ $sliders->first()->choice }}
                            </span>
                            <h1 id="slide-title"
                                class="text-xl sm:text-2xl md:text-3xl lg:text-4xl xl:text-5xl font-black leading-tight text-white drop-shadow-2xl">
                                {{ $sliders->first()->title }}
                            </h1>
                            <div
                                class="flex flex-wrap gap-1 sm:gap-2 md:gap-3 text-xs font-medium items-center text-white justify-start">
                                <span class="px-2 py-1 sm:px-3 rounded-lg flex items-center gap-1 bg-black/40">
                                    <img src="/img/icon/play.png" alt="Type" class="w-3 h-3 sm:w-4 sm:h-4" />
                                    <span id="slide-type">{{ $sliders->first()->type ?? 'TV' }}</span>
                                </span>
                                <span class="px-2 py-1 sm:px-3 rounded-lg flex items-center gap-1 bg-black/40">
                                    <img src="/img/icon/time.png" alt="Duration" class="w-3 h-3 sm:w-4 sm:h-4" />
                                    <span id="slide-duration">{{ $sliders->first()->duration ?? '24m' }}</span>
                                </span>
                                <span
                                    class="px-2 py-1 sm:px-3 rounded-lg shadow-lg flex items-center gap-1 bg-black/40">
                                    <img src="/img/icon/date.png" alt="Year" class="w-3 h-3 sm:w-4 sm:h-4" />
                                    <span id="slide-year">{{ $sliders->first()->year ?? '2024' }}</span>
                                </span>
                                <span id="slide-quality"
                                    class="bg-red-600 px-2 py-1 sm:px-3 rounded-lg shadow-lg flex items-center gap-1">
                                    {{ $sliders->first()->quality ?? 'HD' }}
                                </span>
                                <span
                                    class="bg-blue-600 px-2 py-1 sm:px-3 rounded-lg shadow-lg flex items-center gap-1">
                                    <img src="/img/icon/eps.png" alt="Episodes" class="w-3 h-3 sm:w-4 sm:h-4" />
                                    <span id="slide-episodes">{{ $sliders->first()->episodes ?? '12 حلقة' }}</span>
                                </span>
                            </div>
                            <p id="slide-description"
                                class="text-xs sm:text-sm leading-relaxed text-white/90 max-w-lg line-clamp-2 sm:line-clamp-3 md:line-clamp-4">
                                {{ $sliders->first()->description }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Navigation --}}
                            <div
                                class="absolute bottom-8 sm:bottom-12 md:bottom-16 lg:bottom-20 pb-4 sm:pb-8 md:pb-12 lg:pb-16 right-4 sm:right-8 md:right-12 lg:right-16 xl:right-20 flex gap-2 z-20">
                                <button id="next-slide"
                                    class="bg-white/20 hover:bg-white/30 p-1 sm:p-2 md:p-3 rounded-full backdrop-blur-sm transition-all duration-300">
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4 md:w-5 md:h-5" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path
                                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" />
                                    </svg>
                                </button>
                                <button id="prev-slide"
                                    class="bg-white/20 hover:bg-white/30 p-1 sm:p-2 md:p-3 rounded-full backdrop-blur-sm transition-all duration-300">
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4 md:w-5 md:h-5" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path
                                            d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" />
                                    </svg>
                                </button>
                            </div>

            </section>

            {{-- JavaScript --}}
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    const slides = document.querySelectorAll('[data-slide]');
                    const choice = document.getElementById('slide-choice');
                    const title = document.getElementById('slide-title');
                    const type = document.getElementById('slide-type');
                    const duration = document.getElementById('slide-duration');
                    const year = document.getElementById('slide-year');
                    const quality = document.getElementById('slide-quality');
                    const episodes = document.getElementById('slide-episodes');
                    const description = document.getElementById('slide-description');
                    const heroLink = document.getElementById('hero-link');
                    const nextBtn = document.getElementById('next-slide');
                    const prevBtn = document.getElementById('prev-slide');

                    let currentIndex = 0;
                    let slideInterval;
                    let currentDuration = parseInt(slides[0]?.getAttribute('data-duration-ms')) || 5000;

                    function showSlide(index) {
                        // Hentikan interval sebelumnya
                        clearInterval(slideInterval);

                        // Update semua slide
                        slides.forEach((slide, i) => {
                            slide.style.opacity = i === index ? '1' : '0';
                            slide.classList.toggle('active', i === index);
                        });

                        const activeSlide = slides[index];

                        // Update konten
                        if (activeSlide) {
                            title.textContent = activeSlide.getAttribute('data-title');
                            choice.textContent = activeSlide.getAttribute('data-choice');
                            type.textContent = activeSlide.getAttribute('data-type');
                            duration.textContent = activeSlide.getAttribute('data-duration');
                            year.textContent = activeSlide.getAttribute('data-year');
                            quality.textContent = activeSlide.getAttribute('data-quality');
                            episodes.textContent = activeSlide.getAttribute('data-episodes');
                            description.textContent = activeSlide.getAttribute('data-description');

                            // Update link
                            const link = activeSlide.getAttribute('data-link');
                            if (heroLink) {
                                heroLink.setAttribute('href', link);
                            }

                            // Update durasi saat ini
                            currentDuration = parseInt(activeSlide.getAttribute('data-duration-ms')) || 5000;
                        }

                        // Mulai interval baru
                        startSlideInterval();
                    }

                    function startSlideInterval() {
                        slideInterval = setInterval(() => {
                            currentIndex = (currentIndex + 1) % slides.length;
                            showSlide(currentIndex);
                        }, currentDuration);
                    }

                    // Navigasi manual
                    nextBtn.addEventListener('click', (e) => {
                        e.preventDefault();
                        currentIndex = (currentIndex + 1) % slides.length;
                        showSlide(currentIndex);
                    });

                    prevBtn.addEventListener('click', (e) => {
                        e.preventDefault();
                        currentIndex = (currentIndex - 1 + slides.length) % slides.length;
                        showSlide(currentIndex);
                    });

                    // Inisialisasi
                    showSlide(currentIndex);
                });
            </script>
        @endif


        {{-- 📦 Main Content Section --}}
        <section class="hero-main w-full mx-auto grid grid-cols-1 lg:grid-cols-4 gap-6 px-4 relative z-10">
            {{-- Background Pattern Layer --}}
            <img src="https://i.imgur.com/TzlC4Be.png" referrerpolicy="no-referrer" class="absolute inset-0 w-full h-full object-cover -z-10 pointer-events-none" style="object-position: top;" alt="Background Pattern">

            {{-- Latest Releases --}}
            <div class="lg:col-span-3 order-1 lg:order-1">
                <h2 class="text-lg md:text-xl font-bold mb-6 pl-2 text-white">أحدث الإصدارات</h2>

                {{-- Background blur container --}}
                <div class="bg-white/5 bg-opacity-30 backdrop-blur-lg rounded-xl p-6 border border-white/10 shadow-2xl">
                    <div class="grid grid-cols-1 gap-4 md:gap-6">
                        @forelse ($latestReleases as $anime)
                            <a href="{{ route('anime.show', ['mal_id' => $anime['mal_id']]) }}"
                                class="block hover:bg-gray-700 hover:bg-opacity-50 transition-all duration-300 rounded-lg group">
                                <div class="p-4 flex items-center gap-4 relative overflow-hidden">
                                    {{-- Anime Image --}}
                                    <div
                                        class="relative z-10 w-20 md:w-28 h-28 md:h-40 overflow-hidden rounded-lg flex-shrink-0 shadow-lg order-1">
                                        <img src="{{ $anime['images']['jpg']['image_url'] ?? '/img/default-poster.jpg' }}"
                                            class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                                            alt="{{ $anime['title'] }}">
                                        <div
                                            class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent">
                                        </div>
                                    </div>

                                    {{-- Anime Info --}}
                                    <div class="flex-1 relative z-10 min-w-0 space-y-3 order-2">
                                        {{-- Title + Score --}}
                                        <div class="flex items-center justify-between gap-4">
                                            <p
                                                class="text-lg group-hover:text-blue-400 transition-colors duration-300 leading-tight">
                                                <span class="font-bold text-base text-white">
                                                    {{ $anime['title'] }}
                                                </span>
                                                @if (!empty($anime['title_english']) && $anime['title_english'] !== $anime['title'])
                                                    <br>
                                                    <span class="font-normal text-base text-white/70">
                                                        {{ $anime['title_english'] }}
                                                    </span>
                                                @endif
                                            </p>

                                            <div class="flex items-center gap-2 text-xs flex-shrink-0">
                                                <div>
                                                    <div
                                                        class="bg-yellow-500 rounded px-2 py-1 text-center min-w-[50px] text-black font-bold text-[15px] tracking-wide">
                                                        IMDb</div>
                                                    <div class="text-white text-center font-bold text-sm">
                                                        {{ $anime['imdb_score'] ?? 'N/A' }}</div>
                                                </div>
                                                <div>
                                                    <div
                                                        class="bg-blue-600 rounded px-2 py-1 text-center min-w-[50px] text-white font-bold text-[15px] tracking-wide">
                                                        MAL</div>
                                                    <div class="text-white text-center font-bold text-sm">
                                                        {{ $anime['score'] ?? 'N/A' }}</div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Meta --}}
                                        <div class="flex flex-wrap gap-2 text-sm text-white/70">
                                            <span class="flex items-center gap-1">عدد الحلقات:
                                                {{ $anime['episodes'] ?? '؟' }}</span>
                                        </div>
                                        <div class="flex flex-wrap gap-2 text-sm text-white/70">
                                            <span class="flex items-center gap-1">النوع:
                                                {{ $anime['type'] ?? '-' }}</span>
                                        </div>

                                        {{-- Genres --}}
                                        <div class="flex flex-wrap gap-2">
                                            <span class="text-sm text-white/70 shadow-sm">التصنيفات:</span>
                                            @foreach (array_slice($anime['genres'] ?? [], 0, 3) as $genre)
                                                <span class="text-sm text-white/70 shadow-sm">
                                                    @if (!$loop->first)
                                                        ,
                                                    @endif
                                                    {{ is_array($genre) ? $genre['name'] ?? ($genre['title'] ?? '') : $genre }}
                                                </span>
                                            @endforeach

                                        </div>

                                        {{-- Batch Info --}}
                                        <p class="text-sm text-white/70 leading-relaxed">
                                            الإصدار: {{ $anime['latest_batch_name'] ?? '-' }}
                                        </p>
                                    </div>
                                </div>
                            </a>

                            {{-- ✨ Line Separator --}}
                            @if (!$loop->last)
                                <div
                                    class="w-full h-px bg-gradient-to-r from-transparent via-gray-400 to-transparent opacity-50">
                                </div>
                            @endif
                        @empty
                            <div class="text-white/70">لا توجد بيانات.</div>
                        @endforelse
                    </div>

                    {{-- Pagination --}}
                    @if ($latestReleases->hasPages())
                        <div class="flex justify-center mt-10" dir="rtl">
                            <nav class="flex items-center space-x-2 rtl:space-x-reverse">
                                @if (!$latestReleases->onFirstPage())
                                    <a href="{{ $latestReleases->url(1) }}"
                                        class="w-10 h-10 flex items-center justify-center rounded-full bg-white hover:bg-gray-300 text-black text-lg transition"
                                        title="الصفحة الأولى">
                                        &laquo;
                                    </a>
                                    <a href="{{ $latestReleases->previousPageUrl() }}"
                                        class="w-10 h-10 flex items-center justify-center rounded-full bg-white hover:bg-gray-300 text-black text-lg transition"
                                        title="السابقة">
                                        &lsaquo;
                                    </a>
                                @endif

                                @foreach ($latestReleases->getUrlRange(max(1, $latestReleases->currentPage() - 2), min($latestReleases->lastPage(), $latestReleases->currentPage() + 2)) as $page => $url)
                                    <a href="{{ $url }}"
                                        class="w-10 h-10 flex items-center justify-center rounded-full text-sm transition
                           {{ $latestReleases->currentPage() == $page ? 'bg-red-500 text-white font-bold' : 'bg-white text-black hover:bg-gray-300' }}"
                                        title="صفحة {{ $page }}">
                                        {{ $page }}
                                    </a>
                                @endforeach

                                @if ($latestReleases->hasMorePages())
                                    <a href="{{ $latestReleases->nextPageUrl() }}"
                                        class="w-10 h-10 flex items-center justify-center rounded-full bg-white hover:bg-gray-300 text-black text-lg transition"
                                        title="التالي">
                                        &rsaquo;
                                    </a>
                                    <a href="{{ $latestReleases->url($latestReleases->lastPage()) }}"
                                        class="w-10 h-10 flex items-center justify-center rounded-full bg-white hover:bg-gray-300 text-black text-lg transition"
                                        title="الأخيرة">
                                        &raquo;
                                    </a>
                                @endif
                            </nav>
                        </div>

                        <div class="text-center mt-4 text-gray-400 text-sm" dir="rtl">
                            صفحة {{ $latestReleases->currentPage() }} من {{ $latestReleases->lastPage() }}
                            ({{ $latestReleases->total() }} نتيجة)
                        </div>
                    @endif
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-8 order-2 lg:order-2 ml-10">
                {{-- Most Visited Section --}}
                <div>
                    <h2 class="text-xl font-bold mb-6 text-white pl-3 relative z-10">
                        الأكثر زيارة
                    </h2>
                    <div class="bg-white/5 backdrop-blur-md rounded-lg border-white/10">
                        <div class="space-y-5 z-10 rounded-xl p-6 relative ">
                            <div class="rounded-lg">
                                @foreach ($mostVisited as $index => $anime)
                                    <a href="{{ route('anime.show', ['mal_id' => $anime['mal_id']]) }}"
                                        class="block duration-300 rounded-lg group">
                                        <div
                                            class="flex items-center p-3 gap-4 relative overflow-hidden bg-gradient-to-r transition-all duration-300">
                                            {{-- Purple line accent --}}
                                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b"></div>

                                            {{-- Ranking Number --}}
                                            <div
                                                class="text-white font-black text-2xl w-10 relative z-10 flex-shrink-0 text-center order-1">
                                                <span
                                                    class="bg-gradient-to-br from-white to-white bg-clip-text text-transparent">
                                                    {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                                    <div
                                                        class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-8 h-0.5 bg-gradient-to-r from-red-400">
                                                    </div>
                                                </span>
                                            </div>

                                            {{-- Anime Image --}}
                                            <div
                                                class="relative z-10 w-12 h-16 overflow-hidden rounded-lg flex-shrink-0 shadow-md order-2">
                                                <img src="{{ $anime['images']['jpg']['image_url'] ?? '/img/default-poster.jpg' }}"
                                                    class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                                                    alt="{{ $anime['title'] }}">
                                                <div
                                                    class="absolute inset-0 bg-gradient-to-t from-purple-900/50 via-transparent to-transparent">
                                                </div>
                                            </div>

                                            {{-- Anime Info --}}
                                            <div class="flex-1 relative z-10 min-w-0 order-3">
                                                <div
                                                    class="text-sm font-bold text-white group-hover:text-purple-300 transition-colors duration-300 mb-1 leading-tight">
                                                    {{ $anime['title'] }}
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Random Collections Section --}}
                <div>
                    <h2 class="text-xl font-bold text-white pl-3 mb-6 relative z-10">
                        السلاسل
                    </h2>
                    <div class="bg-white/5 backdrop-blur-md rounded-lg border-white/10">
                    <div class="space-y-4 z-10 rounded-xl p-6 relative">
                        <div class="rounded-lg">
                            @foreach ($randomCollections as $index => $collection)
                                <a href="{{ route('collections.show', $collection->slug) }}"
                                    class="block duration-300 rounded-lg group mb-4 last:mb-0">
                                    <div
                                        class="flex items-center p-3 gap-4 relative overflow-hidden bg-gradient-to-r transition-all duration-300">

                                        {{-- Image --}}
                                        <div
                                            class="relative z-10 w-12 h-16 overflow-hidden rounded-lg flex-shrink-0 shadow-md">
                                            <img src="{{ $collection->poster_url }}" alt="{{ $collection->title }}"
                                                class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                                            <div
                                                class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent">
                                            </div>
                                        </div>

                                        {{-- Info --}}
                                        <div class="flex-1 relative z-10 min-w-0">
                                            <div
                                                class="text-sm font-bold text-white group-hover:text-blue-300 transition-colors duration-300 mb-1 leading-tight">
                                                {{ $collection->title }}
                                            </div>
                                            <div class="text-xs text-gray-400 flex items-center gap-2">
                                                <span>{{ $collection->animeLinks->count() }} {{ $collection->animeLinks->count() <= 10 ? 'أعمال' : 'عمل' }}</span>
                                                <span class="text-white/20">•</span>
                                                <span>{{ \Carbon\Carbon::parse($collection->created_at)->locale('ar')->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                        {{-- See All Button --}}
                        <div class="mt-4 text-center">
                            <a href="{{ route('collections.index') }}"
                                class="inline-flex items-center gap-2 text-sm text-blue-400 hover:text-blue-300 transition-colors duration-300">
                                <span>عرض الكل</span>
                            </a>
                        </div>
                        </div>
                    </div>
                </div>

                {{-- Latest Comments Section (Sidebar) --}}
                @if (isset($latestComments) && $latestComments->isNotEmpty())
                    <div>
                        <h2 class="text-xl font-bold text-white pl-3 mb-6 relative z-10">
                            أحدث التعليقات
                        </h2>
                        <div class="bg-white/5 backdrop-blur-md rounded-lg border-white/10">
                            <div class="space-y-4 z-10 rounded-xl p-4 sm:p-6 relative">
                                <div id="latest-comments-scroll" class="rounded-lg space-y-3 overflow-y-auto custom-scroll pr-2" style="max-height: 200px;">
                                    @foreach ($latestComments as $comment)
                                        <a href="{{ route('anime.show', $comment->animeLink->mal_id) }}#comments-section"
                                            class="block bg-white/5 p-3 rounded-xl border border-white/5 hover:bg-white/10 hover:border-white/10 transition-all duration-300 group">
                                            <div class="flex items-start gap-3">
                                                {{-- User Avatar --}}
                                                <img src="{{ $comment->user->profile_photo_url ?? 'https://i.imgur.com/vSKdWqp.png' }}"
                                                    alt="{{ $comment->user->name ?? 'User' }}"
                                                    referrerpolicy="no-referrer"
                                                    class="w-8 h-8 rounded-full object-cover border border-white/20">

                                                <div class="flex-1 min-w-0">
                                                    <div class="flex justify-between items-center mb-1">
                                                        <span class="text-xs font-bold text-blue-300 truncate group-hover:text-blue-400 transition-colors">
                                                            {{ $comment->user->name ?? 'مستخدم' }}
                                                        </span>
                                                        <span class="text-[10px] text-gray-500 flex-shrink-0">
                                                            {{ \Carbon\Carbon::parse($comment->created_at)->locale('ar')->diffForHumans() }}
                                                        </span>
                                                    </div>

                                                    <h3 class="text-xs font-semibold text-white group-hover:text-red-400 transition-colors mb-1 truncate">
                                                        {{ $comment->animeLink->title ?? 'Unknown Anime' }}
                                                    </h3>

                                                    <p class="text-xs text-gray-300 line-clamp-2 leading-relaxed">
                                                        {{ $comment->body }}
                                                    </p>
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Donation Card (Sidebar) --}}
                <div x-data="{
                    openDonationModal: false,
                    openFundingModal: false,
                    selectedMethod: null,
                    activeCoin: null,
                    mouseX: 0.5,
                
                    selectMethod(method) {
                        if (typeof method.options === 'string') {
                            try { method.options = JSON.parse(method.options); } catch (e) {}
                        }
                        
                        this.selectedMethod = method;
                        this.activeCoin = null;
                
                        if (method.type === 'crypto' && method.options && method.options.coins && method.options.coins.length > 0) {
                            this.activeCoin = method.options.coins[0];
                        }
                    },
                
                    copyToClipboard(text) {
                        navigator.clipboard.writeText(text).then(() => {
                            alert('تم النسخ بنجاح! / Copied successfully!');
                        });
                    },
                
                    init() {
                        if (new URLSearchParams(window.location.search).has('donate')) {
                            this.openDonationModal = true;
                            window.history.replaceState({}, document.title, '/');
                        }
                    }
                }">

                    <div
                        class="bg-white/5 backdrop-blur-md rounded-lg border-white/10 p-6 relative overflow-hidden group mb-8">
                        <div
                            class="absolute inset-0 bg-red-600/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        </div>

                        <div class="relative z-10 space-y-4 text-center">
                            <p class="text-gray-300 text-sm leading-relaxed">
                                دعمكم يعني لنا الكثير!
                            </p>

                            <button
                                x-ref="fundingBtn"
                                @mousemove="
                                    const rect = $refs.fundingBtn.getBoundingClientRect();
                                    mouseX = Math.max(0, Math.min(1, ($event.clientX - rect.left) / rect.width));
                                "
                                @mouseleave="mouseX = 0.5"
                                @click="mouseX >= 0.5 ? openDonationModal = true : openFundingModal = true"
                                class="w-full py-3 relative overflow-hidden focus:outline-none text-white font-bold rounded-2xl transition-transform hover:scale-[1.02] flex items-center justify-center group shadow-lg shadow-black/50 border border-white/5"
                            >
                                <div class="absolute inset-0 bg-red-700 transition-opacity duration-200" :class="mouseX === 0.5 ? 'opacity-100' : 'opacity-0'"></div>
                                <div class="absolute inset-0 bg-red-500 transition-opacity duration-200" :class="mouseX > 0.5 ? 'opacity-100' : 'opacity-0'"></div>
                                <div class="absolute inset-0 bg-blue-500 transition-opacity duration-200" :class="mouseX < 0.5 ? 'opacity-100' : 'opacity-0'"></div>
                                
                                <div class="relative z-10 w-full flex items-center justify-center gap-2 text-lg drop-shadow-md pointer-events-none">
                                    <span class="transition-all duration-300" :class="mouseX === 0.5 ? 'opacity-100' : (mouseX >= 0.5 ? 'opacity-100 scale-110' : 'opacity-50')">دعم</span>
                                    <span class="text-white/50 transition-opacity duration-300" :class="mouseX !== 0.5 ? 'opacity-30' : 'opacity-70'">|</span>
                                    <span class="transition-all duration-300" :class="mouseX === 0.5 ? 'opacity-100' : (mouseX < 0.5 ? 'opacity-100 scale-110' : 'opacity-50')">تمويل</span>
                                </div>
                            </button>
                        </div>
                    </div>
                    <!-- Donation Modal Overlay -->
                    <template x-teleport="body">
                        <div x-show="openDonationModal" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        class="fixed inset-0 z-[100] flex items-center justify-center bg-black/80 backdrop-blur-sm p-4">

                        <!-- Modal Content -->
                        <div @click.away="openDonationModal = false; selectedMethod = null"
                            :class="selectedMethod ? 'max-w-5xl h-[90vh] md:h-[80vh]' : 'max-w-2xl h-auto'"
                            class="z-999 glass-panel w-full rounded-2xl shadow-2xl overflow-hidden relative text-white transition-all duration-300">

                            <!-- Header -->
                            <div x-show="!selectedMethod"
                                class="p-6 flex justify-between items-center bg-gradient-to-r from-black via-red-950/30 to-black">
                                <h2
                                    class="text-2xl font-bold text-red-600 drop-shadow-md">
                                    ادعمنا
                                </h2>
                                <button @click="openDonationModal = false; selectedMethod = null"
                                    class="text-gray-400 hover:text-white transition transform hover:rotate-90">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>

                            <!-- Body -->
                            <div :class="selectedMethod ? 'p-0 h-full' : 'p-6'">
                                <!-- Method Selection Grid -->
                                <div x-show="!selectedMethod"
                                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach ($paymentMethods as $method)
                                        <div @click="@if ($method->type === 'paypal') window.open('{{ $method->content }}', '_blank') @else selectMethod({{ json_encode($method) }}) @endif"
                                            class="cursor-pointer group relative p-6 rounded-xl bg-black/60 border border-red-900/30 hover:border-red-600 transition-all duration-300 hover-neon flex flex-col items-center gap-4 text-center">

                                            @if ($method->icon)
                                                <img src="/storage/{{ $method->icon }}"
                                                    class="w-16 h-16 object-contain drop-shadow-lg group-hover:scale-110 transition-transform duration-300"
                                                    alt="{{ $method->name }}">
                                            @else
                                                <div
                                                    class="w-16 h-16 rounded-full bg-gray-700 flex items-center justify-center text-2xl group-hover:bg-red-900/50 transition-colors">
                                                    🎁</div>
                                            @endif

                                            <h3 class="font-bold text-lg group-hover:text-red-400 transition-colors">
                                                {{ $method->name }}</h3>

                                            @if ($method->type === 'link')
                                                <span class="text-xs text-gray-400 flex items-center gap-1">
                                                    فتح الرابط <svg class="w-3 h-3" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                                        </path>
                                                    </svg>
                                                </span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Method Detail View -->
                                <template x-if="selectedMethod">
                                    <div class="animate-fadeIn w-full h-full flex flex-col md:flex-row gap-0">
                                        <button @click="selectedMethod = null"
                                            class="absolute top-4 left-4 z-50 md:hidden p-2 bg-black/50 rounded-full text-white hover:bg-red-600 transition-colors">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>

                                        <!-- Sidebar (Visual / Selection) -->
                                        <div
                                            class="w-full md:w-1/3 bg-black/40 border-b md:border-b-0 md:border-l border-white/5 p-6 md:p-8 flex flex-col items-center justify-center text-center relative overflow-hidden shrink-0">
                                            <div
                                                class="absolute inset-0 bg-gradient-to-b from-red-900/10 to-transparent">
                                            </div>

                                            <div class="relative z-10 w-full">
                                                <template x-if="selectedMethod.icon">
                                                    <img :src="'/storage/' + selectedMethod.icon"
                                                        class="w-16 h-16 md:w-24 md:h-24 object-contain mx-auto mb-4 md:mb-6 drop-shadow-2xl">
                                                </template>
                                                <h2 class="text-xl md:text-2xl font-bold text-white mb-2"
                                                    x-text="selectedMethod.name"></h2>
                                                <p class="text-xs md:text-sm text-gray-400 mb-6 px-4"
                                                    x-text="selectedMethod.type === 'crypto' ? 'حول العملات الرقمية بأمان وسرعة' : (selectedMethod.type === 'stc_pay' ? 'الدفع عبر STC Pay' : 'الدفع المحلي المباشر')">
                                                </p>

                                                <template
                                                    x-if="selectedMethod.type === 'crypto' && selectedMethod.options && selectedMethod.options.coins && selectedMethod.options.coins.length > 0">
                                                    <div class="grid grid-cols-2 gap-2 w-full">
                                                        <template x-for="coin in selectedMethod.options.coins">
                                                            <button @click="activeCoin = coin"
                                                                :class="activeCoin === coin ?
                                                                    'bg-red-600 text-white border-red-500 shadow-lg shadow-red-900/50' :
                                                                    'bg-white/5 text-gray-400 border-white/10 hover:bg-white/10'"
                                                                class="py-2 px-3 md:py-3 md:px-4 rounded-xl border font-bold text-xs md:text-sm transition-all duration-300 flex items-center justify-center gap-2">
                                                                <span x-text="coin.coin_name"></span>
                                                            </button>
                                                        </template>
                                                    </div>
                                                </template>
                                                <template
                                                    x-if="selectedMethod.type === 'crypto' && (!selectedMethod.options || !selectedMethod.options.coins || selectedMethod.options.coins.length === 0)">
                                                    <div
                                                        class="text-center p-4 bg-red-900/20 rounded-xl border border-red-500/30">
                                                        <p class="text-red-400 text-xs">No coins configured yet.</p>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>

                                        <!-- Main Content Area -->
                                        <div
                                            class="flex-1 w-full md:w-2/3 p-6 md:p-10 overflow-y-auto custom-scroll bg-gradient-to-br from-neutral-900 to-black relative">
                                            <button @click="selectedMethod = null"
                                                class="absolute top-4 left-4 hidden md:flex items-center text-sm text-gray-400 hover:text-white transition">
                                                <svg class="w-4 h-4 ml-1 rotate-180" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                </svg>
                                                العودة
                                            </button>

                                            <!-- STC Pay Layout -->
                                            <template x-if="selectedMethod.type === 'stc_pay'">
                                                <div class="space-y-8 animate-fadeIn mt-8">
                                                    <div
                                                        class="bg-white/5 rounded-2xl p-6 border border-white/10 text-center">
                                                        <p
                                                            class="text-gray-400 text-sm mb-4 uppercase tracking-widest">
                                                            رقم الحساب</p>
                                                        <div
                                                            class="flex items-center justify-center gap-4 bg-black/50 p-4 rounded-xl border border-gray-700 mx-auto max-w-sm group focus-within:border-red-500 transition-colors">
                                                            <span class="text-2xl font-mono text-white tracking-wider"
                                                                x-text="selectedMethod.content"></span>
                                                            <button @click="copyToClipboard(selectedMethod.content)"
                                                                class="text-gray-500 hover:text-white transition-colors">
                                                                <svg class="w-6 h-6" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z">
                                                                    </path>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <template x-if="selectedMethod.qr_code">
                                                        <div class="text-center">
                                                            <p class="text-gray-400 text-xs mb-4">مسح الرمز QR للإرسال
                                                                السريع</p>
                                                            <div
                                                                class="bg-white p-4 rounded-xl inline-block shadow-xl">
                                                                <img :src="'/storage/' + selectedMethod.qr_code"
                                                                    class="w-48 h-48 object-contain">
                                                            </div>
                                                        </div>
                                                    </template>
                                                </div>
                                            </template>

                                            <!-- Crypto Layout -->
                                            <template x-if="selectedMethod.type === 'crypto' && activeCoin">
                                                <div class="space-y-6 animate-fadeIn mt-8">
                                                    <div class="flex items-center justify-between mb-2">
                                                        <h3
                                                            class="text-xl font-bold text-white flex items-center gap-2">
                                                            <span class="text-red-500">عملة</span>
                                                            <span x-text="activeCoin.coin_name"></span>
                                                        </h3>
                                                        <span
                                                            class="text-xs text-gray-500 bg-black/50 px-3 py-1 rounded-full border border-gray-800">تأكد
                                                            من اختيار الشبكة الصحيحة</span>
                                                    </div>

                                                    <template x-for="network in activeCoin.networks">
                                                        <div
                                                            class="bg-white/5 rounded-2xl p-0 border border-white/10 overflow-hidden hover:border-red-500/30 transition-colors">
                                                            <div
                                                                class="bg-black/30 px-6 py-3 border-b border-white/5 flex justify-between items-center">
                                                                <span class="font-mono text-blue-400 font-bold"
                                                                    x-text="network.network_name"></span>
                                                                <span
                                                                    class="text-[10px] text-gray-500 uppercase tracking-wider">Network</span>
                                                            </div>
                                                            <div
                                                                class="p-6 flex flex-col md:flex-row gap-6 items-center">
                                                                <!-- QR -->
                                                                <template x-if="network.qr_image">
                                                                    <div class="bg-white p-2 rounded-lg flex-shrink-0">
                                                                        <img :src="'/storage/' + network.qr_image"
                                                                            class="w-24 h-24 object-contain">
                                                                    </div>
                                                                </template>

                                                                <!-- Address -->
                                                                <div class="flex-1 w-full min-w-0">
                                                                    <label
                                                                        class="block text-xs text-gray-400 mb-2">Wallet
                                                                        Address</label>
                                                                    <div class="flex items-center gap-3 bg-black/50 p-3 rounded-lg border border-gray-700 group cursor-pointer hover:border-gray-500 transition-colors"
                                                                        @click="copyToClipboard(network.wallet_address)">
                                                                        <p class="text-xs md:text-sm font-mono text-gray-200 truncate select-all"
                                                                            x-text="network.wallet_address"></p>
                                                                        <button
                                                                            class="ml-auto text-gray-500 group-hover:text-white transition-colors">
                                                                            <svg class="w-5 h-5" fill="none"
                                                                                stroke="currentColor"
                                                                                viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round"
                                                                                    stroke-linejoin="round"
                                                                                    stroke-width="2"
                                                                                    d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z">
                                                                                </path>
                                                                            </svg>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </template>
                                                </div>
                                            </template>

                                            <!-- Legacy Manual/Generic Layout -->
                                            <template
                                                x-if="selectedMethod.type !== 'crypto' && selectedMethod.type !== 'stc_pay'">
                                                <div class="space-y-6 animate-fadeIn mt-8">
                                                    <div>
                                                        <label class="block text-sm text-gray-400 mb-2">Details /
                                                            العنوان</label>
                                                        <div
                                                            class="flex items-center gap-2 bg-black/50 p-3 rounded-lg border border-gray-700 group focus-within:border-red-500 transition">
                                                            <input type="text" readonly
                                                                :value="selectedMethod.content"
                                                                class="bg-transparent border-none text-white w-full focus:ring-0 font-mono text-sm">
                                                            <button @click="copyToClipboard(selectedMethod.content)"
                                                                class="p-2 text-gray-400 hover:text-white hover:bg-white/10 rounded-lg transition"
                                                                title="Copy">
                                                                <svg class="w-5 h-5" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3">
                                                                    </path>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <template x-if="selectedMethod.qr_code">
                                                        <div class="text-center">
                                                            <p class="text-gray-400 text-xs mb-4">QR Code</p>
                                                            <div
                                                                class="bg-white p-4 rounded-xl inline-block shadow-xl">
                                                                <img :src="'/storage/' + selectedMethod.qr_code"
                                                                    class="w-48 h-48 object-contain">
                                                            </div>
                                                        </div>
                                                    </template>
                                                </div>
                                            </template>

                                            <!-- Instructions Block (Common) -->
                                            <template x-if="selectedMethod.instruction">
                                                <div class="mt-8 pt-8 border-t border-white/10">
                                                    <h4 class="text-lg font-bold text-white mb-4">تعليمات هامة</h4>
                                                    <div class="prose prose-invert prose-sm max-w-none text-gray-400 bg-black/20 p-6 rounded-xl border border-white/5"
                                                        x-html="selectedMethod.instruction"></div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </template>

                <!-- Funding Modal Overlay -->
                <template x-teleport="body">
                    <div x-show="openFundingModal" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="fixed inset-0 z-[100] flex items-center justify-center bg-black/80 backdrop-blur-sm p-4"
                    style="display: none;">

                        <!-- Modal Content -->
                        <div @click.away="openFundingModal = false"
                            class="z-999 glass-panel max-w-2xl w-full rounded-2xl shadow-2xl overflow-hidden relative text-white transition-all duration-300">

                            <!-- Header -->
                            <div class="p-6 border-b border-green-900/40 flex justify-between items-center bg-gradient-to-r from-black via-green-950/30 to-black">
                                <h2 class="text-2xl font-bold text-green-500 drop-shadow-md">
                                    التمويل
                                </h2>
                                <button @click="openFundingModal = false"
                                    class="text-gray-400 hover:text-white transition transform hover:rotate-90">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>

                            <!-- Body -->
                            <div class="p-6 md:p-8 bg-gradient-to-br from-neutral-900 to-black max-h-[80vh] overflow-y-auto custom-scroll prose prose-invert prose-green max-w-none text-right" dir="rtl">
                                {!! getFundingInfo() ?: '<p class="text-center text-gray-400 mt-0">لا توجد معلومات للتمويل حالياً.</p>' !!}
                            </div>
                        </div>
                    </div>
                </template>

                </div>




            </div>
        </section>




        @php
            $type = request('type', 'work_in_progress');

            $firstWork = $animes->where('type', 'work_in_progress')->first();
            $firstRec = $animes->where('type', 'recommendation')->first();

            $bgWork = $firstWork ? asset('storage/' . $firstWork->background) : '';
            $bgRec = $firstRec ? asset('storage/' . $firstRec->background) : '';

            $defaultBackground = $type === 'recommendation' ? $bgRec : $bgWork;
        @endphp

        <section class="max-w-7xl mx-auto mt-20 p-6 text-black rounded-lg relative overflow-hidden"
            x-data="{
                tab: '{{ $type }}',
                bg: '{{ $defaultBackground }}',
                showBg: true,
                defaultBackgrounds: {
                    work_in_progress: '{{ $bgWork }}',
                    recommendation: '{{ $bgRec }}'
                }
            }">
            <div class="relative flex flex-col md:flex-row rounded-lg overflow-hidden">

                <!-- Background -->
                <div x-show="showBg" x-transition:enter="transition-opacity duration-500"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="transition-opacity duration-500" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="absolute top-0 left-0 bottom-0 w-full md:w-2/3 bg-no-repeat bg-left bg-white"
                    :style="`background-image: url('${bg}'); background-size: cover; background-position: left center;`">
                </div>

                <!-- Gradient Overlay -->
                <div x-show="showBg" x-transition:enter="transition-opacity duration-300"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="transition-opacity duration-300" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="absolute inset-0 md:right-1/3 z-10 bg-gradient-to-l from-white/100 via-white/5 to-transparent pointer-events-none">
                </div>

                <!-- Konten -->
                <div class="relative z-10 bg-white p-6 space-y-6 transition-all duration-500 ease-in-out"
                    :class="showBg ? 'md:ml-auto md:w-1/3' : 'md:mx-auto md:w-2/3'">
                    <!-- Tabs -->
                    <div class="flex justify-start pb-4 mb-6 space-x-reverse space-x-4">
                        <button @click="tab = 'work_in_progress'; bg = defaultBackgrounds.work_in_progress"
                            class="pb-1 border-b-2 mr-1"
                            :class="tab === 'work_in_progress'
                                ?
                                'text-red-600 text-xl font-bold border-red-600' :
                                'text-gray-500 text-sm border-transparent'">
                            الأعمال الجارية
                        </button>
                        <button @click="tab = 'recommendation'; bg = defaultBackgrounds.recommendation"
                            class="pb-1 border-b-2"
                            :class="tab === 'recommendation'
                                ?
                                'text-red-600 text-xl font-bold border-red-600' :
                                'text-gray-500 text-sm border-transparent'">
                            اقتراحاتُنا
                        </button>
                    </div>

                    <!-- Work In Progress -->
                    <div x-show="tab === 'work_in_progress'" class="space-y-4">
                        @forelse ($animes->where('type', 'work_in_progress') as $anime)
                            <a href="{{ route('anime.show', $anime->mal_id) }}" class="block">
                                <div class="flex flex-row-reverse items-center gap-4 p-4 hover:bg-gray-100 rounded-lg transition cursor-pointer"
                                    @mouseenter="bg = '{{ asset('storage/' . $anime->background) }}'"
                                    @mouseleave="bg = defaultBackgrounds[tab]">
                                    <div class="flex-1 text-right">
                                        <h3 class="font-semibold text-lg">{{ $anime->title }}</h3>
                                        <p class="text-xs text-black mb-1">
                                            {{ is_array($anime->genres) ? collect($anime->genres)->pluck('name')->join(', ') : $anime->genres }}
                                        </p>
                                        <div class="w-full bg-gray-200 rounded-full h-2 mb-1">
                                            <div class="bg-red-500 h-2 rounded-full transition-all duration-300"
                                                style="width: {{ $anime->progress ?? 0 }}%;"></div>
                                        </div>
                                        <p class="text-xs text-black text-left rtl:text-right">
                                            {{ $anime->progress ?? 0 }}%
                                        </p>
                                    </div>
                                    <img src="{{ $anime->poster }}" alt="{{ $anime->title }}"
                                        class="w-14 h-20 rounded-lg object-cover border" />
                                </div>
                            </a>
                        @empty
                            <p class="text-center text-black">لا توجد بيانات</p>
                        @endforelse
                    </div>

                    <!-- Recommendation -->
                    <div x-show="tab === 'recommendation'" class="space-y-4">
                        @forelse ($animes->where('type', 'recommendation') as $anime)
                            <a href="{{ route('anime.show', $anime->mal_id) }}" class="block">
                                <div class="flex flex-row-reverse items-center gap-4 p-4 hover:bg-gray-100 rounded-lg transition cursor-pointer"
                                    @mouseenter="bg = '{{ asset('storage/' . $anime->background) }}'"
                                    @mouseleave="bg = defaultBackgrounds[tab]">
                                    <div class="flex-1 text-right">
                                        <h3 class="font-semibold text-lg">{{ $anime->title }}</h3>
                                        <p class="text-xs text-black mb-1">
                                            {{ is_array($anime->genres) ? collect($anime->genres)->pluck('name')->join(', ') : $anime->genres }}
                                        </p>
                                        <div class="flex items-center gap-1 mt-1">
                                            <svg class="w-3 h-3 text-yellow-400" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                            <span class="text-xs">{{ $anime->score }}</span>
                                        </div>
                                        <p class="text-xs text-black mb-1">
                                            عدد الحلقات: {{ $anime->episodes }}
                                        </p>
                                    </div>
                                    <img src="{{ $anime->poster }}" alt="{{ $anime->title }}"
                                        class="w-14 h-20 rounded-lg object-cover border" />
                                </div>
                            </a>
                        @empty
                            <p class="text-center text-black">لا توجد بيانات</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </section>

    </div>
</x-app-layout>
