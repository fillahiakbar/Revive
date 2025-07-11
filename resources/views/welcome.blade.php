<x-app-layout>
    <div class="text-white font-cairo relative z-10" dir="rtl">
        {{-- Hero Banner Section --}}
        @if($sliders->isNotEmpty())
           <section class="hero-slider relative w-full h-[400px] sm:h-[500px] md:h-[600px] lg:h-[700px] xl:h-[800px] overflow-hidden">
    {{-- Slides --}}
    @foreach ($sliders as $index => $slide)
        <div class="absolute left-0 top-0 w-full h-full z-0 transition-opacity duration-700 {{ $index === 0 ? 'opacity-100' : 'opacity-0' }}"
            data-slide
            data-choice="{{ $slide->choice }}"
            data-title="{{ $slide->title }}"
            data-type="{{ $slide->type }}"
            data-duration="{{ $slide->duration }}"
            data-year="{{ $slide->year }}"
            data-quality="{{ $slide->quality }}"
            data-episodes="{{ $slide->episodes }}"
            data-description="{{ $slide->description }}"
            data-link="{{ route('anime.show', ['mal_id' => $slide->mal_id]) }}"
        >
            <div class="w-full h-full bg-cover bg-center bg-no-repeat
            md:bg-[size:90%] lg:bg-[size:85%] xl:bg-[size:80%] 2xl:bg-[size:75%] 3xl:bg-[size:70%]
            md:bg-left"
     style="background-image: url('{{ asset('storage/' . $slide->image) }}');"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-transparent to-black/80"></div>
            <div class="absolute inset-0 bg-gradient-to-b from-transparent via-transparent to-black/70"></div>
        </div>
    @endforeach

    {{-- Dynamic Content + Link --}}
    <a id="hero-link" href="{{ route('anime.show', ['mal_id' => $sliders->first()->mal_id]) }}">
       <div class="relative z-10 h-full flex items-center justify-start px-4 sm:px-6 md:px-8 lg:px-12 xl:px-20">
  <style>
    /* Base style (mobile first) */
    #hero-link > div {
      background-image: url('{{ asset('img/overlayer-mobile.png') }}');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
    }

    /* Tablet */
    @media (min-width: 768px) {
      #hero-link > div {
        background-image: url('{{ asset('img/overlayer-tablet.png') }}');
      }
    }

    @media (min-width: 960px) {
      #hero-link > div {
        background-image: url('{{ asset('img/overlayer-tablet.png') }}');
      }
    }

    /* Laptop */
    @media (min-width: 1024px) {
      #hero-link > div {
        background-image: url('{{ asset('img/overlayer-laptop.png') }}');
      }
    }

    /* Desktop */
    @media (min-width: 1280px) {
      #hero-link > div {
        background-image: url('{{ asset('img/overlayer-dekstop.png') }}');
      }
    }

    /* 4K/Ultra Wide */
    @media (min-width: 1920px) {
      #hero-link > div {
        background-image: url('{{ asset('img/overlayer-4k.png') }}');
        background-size: cover;
      }
    }
  </style>


            
            <div class="w-full md:w-[60%] lg:w-[50%] xl:w-[40%] max-w-2xl space-y-3 sm:space-y-4 md:space-y-6 text-right">
                <span id="slide-choice" class="inline-block text-xs sm:text-sm text-white/80 font-medium">
                    {{ $sliders->first()->choice }}
                </span>
                <h1 id="slide-title" class="text-xl sm:text-2xl md:text-3xl lg:text-4xl xl:text-5xl font-black leading-tight text-white drop-shadow-2xl">
                    {{ $sliders->first()->title }}
                </h1>
                <div class="flex flex-wrap gap-1 sm:gap-2 md:gap-3 text-xs font-medium items-center text-white justify-start">
                    <span class="px-2 py-1 sm:px-3 rounded-lg flex items-center gap-1 bg-black/40">
                        <img src="/img/icon/play.png" alt="Type" class="w-3 h-3 sm:w-4 sm:h-4" />
                        <span id="slide-type">{{ $sliders->first()->type ?? 'TV' }}</span>
                    </span>
                    <span class="px-2 py-1 sm:px-3 rounded-lg flex items-center gap-1 bg-black/40">
                        <img src="/img/icon/time.png" alt="Duration" class="w-3 h-3 sm:w-4 sm:h-4" />
                        <span id="slide-duration">{{ $sliders->first()->duration ?? '24m' }}</span>
                    </span>
                    <span class="px-2 py-1 sm:px-3 rounded-lg shadow-lg flex items-center gap-1 bg-black/40">
                        <img src="/img/icon/date.png" alt="Year" class="w-3 h-3 sm:w-4 sm:h-4" />
                        <span id="slide-year">{{ $sliders->first()->year ?? '2024' }}</span>
                    </span>
                    <span id="slide-quality" class="bg-red-600 px-2 py-1 sm:px-3 rounded-lg shadow-lg flex items-center gap-1">
                        {{ $sliders->first()->quality ?? 'HD' }}
                    </span>
                    <span class="bg-blue-600 px-2 py-1 sm:px-3 rounded-lg shadow-lg flex items-center gap-1">
                        <img src="/img/icon/eps.png" alt="Episodes" class="w-3 h-3 sm:w-4 sm:h-4" />
                        <span id="slide-episodes">{{ $sliders->first()->episodes ?? '12 حلقة' }}</span>
                    </span>
                </div>
                <p id="slide-description" class="text-xs sm:text-sm leading-relaxed text-white/90 max-w-lg line-clamp-2 sm:line-clamp-3 md:line-clamp-4">
                    {{ $sliders->first()->description }}
                </p>
            </div>
        </div>
    </a>

    {{-- Navigation --}}
    <div class="absolute bottom-8 sm:bottom-12 md:bottom-16 lg:bottom-20 pb-4 sm:pb-8 md:pb-12 lg:pb-16 right-4 sm:right-8 md:right-12 lg:right-16 xl:right-20 flex gap-2 z-20">
        <button id="next-slide" class="bg-white/20 hover:bg-white/30 p-1 sm:p-2 md:p-3 rounded-full backdrop-blur-sm transition-all duration-300">
            <svg class="w-3 h-3 sm:w-4 sm:h-4 md:w-5 md:h-5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" />
            </svg>
        </button>
        <button id="prev-slide" class="bg-white/20 hover:bg-white/30 p-1 sm:p-2 md:p-3 rounded-full backdrop-blur-sm transition-all duration-300">
            <svg class="w-3 h-3 sm:w-4 sm:h-4 md:w-5 md:h-5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" />
            </svg>
        </button>
    </div>
</section>

            {{-- JavaScript --}}
            <script>
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

    let currentIndex = 0;

    function showSlide(index) {
        slides.forEach((slide, i) => {
            slide.style.opacity = i === index ? '1' : '0';
        });

        const slide = slides[index];
        title.textContent = slide.getAttribute('data-title');
        choice.textContent = slide.getAttribute('data-choice');
        type.textContent = slide.getAttribute('data-type');
        duration.textContent = slide.getAttribute('data-duration');
        year.textContent = slide.getAttribute('data-year');
        quality.textContent = slide.getAttribute('data-quality');
        episodes.textContent = slide.getAttribute('data-episodes');
        description.textContent = slide.getAttribute('data-description');

        const link = slide.getAttribute('data-link');
        if (heroLink) {
            heroLink.setAttribute('href', link);
        }
    }

    // Navigasi manual
    document.getElementById('next-slide').addEventListener('click', () => {
        currentIndex = (currentIndex + 1) % slides.length;
        showSlide(currentIndex);
    });

    document.getElementById('prev-slide').addEventListener('click', () => {
        currentIndex = (currentIndex - 1 + slides.length) % slides.length;
        showSlide(currentIndex);
    });

    // ⏱️ Tambahkan auto-play setiap 5 detik (5000ms)
    setInterval(() => {
        currentIndex = (currentIndex + 1) % slides.length;
        showSlide(currentIndex);
    }, 5000); // 5000ms = 5 detik

</script>
        @endif

        {{-- 📦 Main Content Section --}}
        <section class="hero-main w-full mx-auto grid grid-cols-1 lg:grid-cols-4 gap-6 px-4 pt-2 relative z-10 mb-2" 
         style="background-image: url('{{ asset('img/layerbottom.png') }}'); 
                background-repeat: repeat-x; 
                background-position: top;
                background-size: auto 100%;">
            {{-- Latest Releases --}}
{{-- Latest Releases --}}
<div class="lg:col-span-3 order-1 lg:order-1 mt-12 sm:mt-16 md:mt-20 lg:mt-24 ml-4 sm:ml-6 md:ml-8 lg:ml-10">
    <h2 class="text-base sm:text-lg md:text-xl font-bold mb-4 sm:mb-6 pl-2 text-white">أحدث الإصدارات</h2>
    
    {{-- Background blur container --}}
    <div class="bg-white/5 bg-opacity-30 backdrop-blur-lg rounded-lg sm:rounded-xl p-4 sm:p-6 border border-white/10">
        <div class="grid grid-cols-1 gap-3 sm:gap-4 md:gap-6">
            @foreach ($latestReleases as $anime)
                <a href="{{ route('anime.show', ['mal_id' => $anime['mal_id']]) }}" class="block hover:bg-gray-700 hover:bg-opacity-50 transition-all duration-300 rounded-md sm:rounded-lg group">
                    <div class="p-3 sm:p-4 flex items-center gap-3 sm:gap-4 relative overflow-hidden">
                        {{-- Anime Image --}}
                        <div class="relative z-10 w-16 sm:w-20 md:w-24 lg:w-28 h-24 sm:h-28 md:h-36 lg:h-40 overflow-hidden rounded-md sm:rounded-lg flex-shrink-0 shadow-md sm:shadow-lg">
                            <img src="{{ $anime['images']['jpg']['image_url'] }}"
                                class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                                alt="{{ $anime['title'] }}">
                            <div class="absolute inset-0 bg-gradient-to-t from-white/60 via-transparent to-transparent"></div>
                        </div>

                        {{-- Anime Info --}}
                        <div class="flex-1 relative z-10 min-w-0 space-y-2 sm:space-y-3">
                            {{-- Title + Score --}}
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 sm:gap-4">
                                <h2 class="font-bold text-sm sm:text-base md:text-lg text-white group-hover:text-blue-400 transition-colors duration-300 line-clamp-2">
                                    {{ $anime['title'] }}
                                </h2>
                                <div class="flex items-center gap-1 sm:gap-2 text-xs flex-shrink-0 mt-1 sm:mt-0">
                                    <div>
                                        <div class="bg-imdb rounded px-1 sm:px-2 py-1 text-center min-w-[40px] sm:min-w-[50px] text-black font-bold text-xs sm:text-sm md:text-[15px] tracking-wide">IMDb</div>
                                        <div class="text-white text-center font-bold text-xs">{{ $anime['imdb_score'] ?? 'N/A' }}</div>
                                    </div>
                                    <div>
                                        <div class="bg-mal rounded px-1 sm:px-2 py-1 text-center min-w-[40px] sm:min-w-[50px] text-white font-bold text-xs sm:text-sm md:text-[15px] tracking-wide">MAL</div>
                                        <div class="text-white text-center font-bold text-xs">{{ $anime['score'] ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </div>

                            {{-- Title English --}}
                            @if (!empty($anime['title_english']) && $anime['title_english'] !== $anime['title'])
                                <p class="text-xs sm:text-sm text-white/60 line-clamp-1">
                                    {{ $anime['title_english'] }}
                                </p>
                            @endif

                            {{-- Meta --}}
                            <div class="flex flex-wrap gap-1 sm:gap-2 text-xs sm:text-sm text-white/80">
                                <span class="flex items-center gap-1">عدد الحلقات: {{ $anime['episodes'] ?? '؟' }}</span>
                                <span class="flex items-center gap-1">النوع: {{ $anime['type'] }}</span>
                            </div>

                            {{-- Genres --}}
                            <div class="flex flex-wrap gap-1 sm:gap-2 text-xs">
                                <span class="font-medium text-white/80">التصنيفات:</span>
                                @foreach (array_slice($anime['genres'], 0, 3) as $genre)
                                    <span class="font-medium text-white/80">
                                        @if(!$loop->first), @endif{{ $genre['name'] }}
                                    </span>
                                @endforeach
                            </div>

                            {{-- Batch Info --}}
                            <p class="text-xs sm:text-sm text-white/70 leading-relaxed line-clamp-2">
                                الإصدار: {{ $anime['latest_batch_name'] }}
                            </p>

                            <div class="flex flex-wrap items-center gap-2 text-xs text-white/60">
                                <span>تم النشر في: {{ \Carbon\Carbon::parse($anime['created_at'])->translatedFormat('d F Y - H:i') }}</span>

                                {{-- Badge "جديد" --}}
                                @if (\Carbon\Carbon::parse($anime['created_at'])->gt(now()->subDay()))
                                    <span class="inline-block bg-green-600 text-white px-2 py-1 rounded-full text-2xs sm:text-xs">جديد</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </a>

                {{-- Line Separator --}}
                @if (!$loop->last)
                    <div class="w-full h-px bg-gradient-to-r from-transparent via-gray-400 to-transparent opacity-30 sm:opacity-50"></div>
                @endif
            @endforeach
        </div>
    </div>
</div>

            {{-- Sidebar --}}
            <div class="space-y-8 order-2 lg:order-2 ml-10">
                {{-- Most Visited Section --}}
                <div>
                    <h2 class="text-xl font-bold mb-6 text-white pl-3 relative z-10 mt-24">
                        الأكثر زيارة
                    </h2>
                    <div class="bg-white/5 backdrop-blur-md rounded-lg border-white/10">
                        <div class="space-y-5 z-10 rounded-xl p-6 relative ">
                            <div class="rounded-lg">
                                @foreach ($mostVisited->take(5) as $index => $anime)
                                    <a href="{{ route('anime.show', ['mal_id' => $anime['mal_id']]) }}" class="block duration-300 rounded-lg group">
                                        <div class="flex items-center p-3 gap-4 relative overflow-hidden bg-gradient-to-r transition-all duration-300">
                                            {{-- Purple line accent --}}
                                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b"></div>
                                            
                                            {{-- Ranking Number --}}
                                            <div class="text-white font-black text-2xl w-10 relative z-10 flex-shrink-0 text-center order-1">
                                                <span class="bg-gradient-to-br from-white to-white bg-clip-text text-transparent">
                                                    {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                                    <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-8 h-0.5 bg-gradient-to-r from-red-400"></div>
                                                </span>
                                            </div>
                                            
                                            {{-- Anime Image --}}
                                            <div class="relative z-10 w-12 h-16 overflow-hidden rounded-lg flex-shrink-0 shadow-md order-2">
                                                <img src="{{ $anime['images']['jpg']['image_url'] }}" 
                                                    class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110" 
                                                    alt="{{ $anime['title'] }}">
                                                <div class="absolute inset-0 bg-gradient-to-t from-purple-900/50 via-transparent to-transparent"></div>
                                            </div>
                                            
                                            {{-- Anime Info --}}
                                            <div class="flex-1 relative z-10 min-w-0 order-3">
                                                <div class="text-sm font-bold text-white group-hover:text-purple-300 transition-colors duration-300 mb-1 leading-tight">
                                                    {{ $anime['title'] }}
                                                </div>
                                                <div class="inline-flex items-center text-xs text-white bg-blue-600/80 rounded-sm px-3 py-2">
                                                    <span> {{ $anime['episodes'] ?? '؟؟' }} حلقة</span>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Categories Section --}}
                <h2 class="text-xl font-bold text-white pl-3 mb-6 relative z-10">
                    التصنيفات
                </h2>
                <div class="bg-white/5 backdrop-blur-md rounded-xl p-6 relative overflow-hidden border-white/10">
                    <div class="grid grid-cols-2 gap-3 text-sm text-white relative z-10 mb-6">
                        @foreach(['لعبة','أكشن','حريم','مغامرة','تاريخي','سيارات','رعب','كوميدي','ايسيكاي','خرف','جوسي','شياطين','أطفال','سحر','ميكا','فانتازيا'] as $tag)
                            <button class="">
                                {{ $tag }}
                            </button>
                        @endforeach
                    </div>
                    
                    <button class="w-full bg-white/20 text-white py-3 rounded-lg relative z-10 transition-all duration-300 font-medium shadow-lg hover:shadow-blue-500/25 backdrop-blur-sm">
                        <a href="/genres">إظهار المزيد</a>
                    </button>
                </div>
            </div>
        </section>

@php
    $type = request('type', 'work_in_progress');

    $firstWork = $animes->where('type', 'work_in_progress')->first();
    $firstRec  = $animes->where('type', 'recommendation')->first();

    $bgWork = $firstWork ? asset('storage/' . $firstWork->background) : '';
    $bgRec  = $firstRec ? asset('storage/' . $firstRec->background) : '';

    $defaultBackground = $type === 'recommendation' ? $bgRec : $bgWork;
@endphp

<section 
    class="max-w-7xl mx-auto mt-20 p-6 text-black rounded-lg relative overflow-hidden"
    x-data="{ 
        tab: '{{ $type }}', 
        bg: '{{ $defaultBackground }}', 
        showBg: true,
        defaultBackgrounds: {
            work_in_progress: '{{ $bgWork }}',
            recommendation: '{{ $bgRec }}'
        }
    }"
>
    <div class="relative flex flex-col md:flex-row rounded-lg overflow-hidden">

        <!-- Background -->
        <div 
            x-show="showBg"
            x-transition:enter="transition-opacity duration-500"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity duration-500"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="absolute top-0 left-0 bottom-0 w-full md:w-2/3 bg-no-repeat bg-left bg-black"
            :style="`background-image: url('${bg}'); background-size: contain; background-position: left center;`"
        ></div>

        <!-- Gradient Overlay -->
        <div 
            x-show="showBg"
            x-transition:enter="transition-opacity duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="absolute inset-0 md:right-1/3 z-10 bg-gradient-to-l from-white/100 via-white/5 to-transparent pointer-events-none"
        ></div>

        <!-- Konten -->
        <div 
            class="relative z-10 bg-white p-6 space-y-6 transition-all duration-500 ease-in-out"
            :class="showBg ? 'md:ml-auto md:w-1/3' : 'md:mx-auto md:w-2/3'"
        >
            <!-- Tabs -->
            <div class="flex justify-start pb-4 mb-6 space-x-reverse space-x-4">
                <button 
                    @click="tab = 'work_in_progress'; bg = defaultBackgrounds.work_in_progress"
                    class="pb-1 border-b-2"
                    :class="tab === 'work_in_progress' 
                        ? 'text-red-600 text-xl font-bold border-red-600' 
                        : 'text-gray-500 text-sm border-transparent'"
                >
                    الأعمال الجارية
                </button>
                <button 
                    @click="tab = 'recommendation'; bg = defaultBackgrounds.recommendation"
                    class="pb-1 border-b-2"
                    :class="tab === 'recommendation' 
                        ? 'text-red-600 text-xl font-bold border-red-600' 
                        : 'text-gray-500 text-sm border-transparent'"
                >
                    اقتراحاتُنا
                </button>
            </div>

            <!-- Work In Progress -->
            <div x-show="tab === 'work_in_progress'" class="space-y-4">
                @forelse ($animes->where('type', 'work_in_progress') as $anime)
                    <a 
                        href="{{ route('anime.show', $anime->mal_id) }}"
                        class="block"
                    >
                        <div 
                            class="flex flex-row-reverse items-center gap-4 p-4 hover:bg-gray-100 rounded-lg transition cursor-pointer"
                            @mouseenter="bg = '{{ asset('storage/' . $anime->background) }}'"
                            @mouseleave="bg = defaultBackgrounds[tab]"
                        >
                            <div class="flex-1 text-right">
                                <h3 class="font-semibold text-lg">{{ $anime->title }}</h3>
                                <p class="text-xs text-black mb-1">
                                    {{ is_array($anime->genres) 
                                        ? collect($anime->genres)->pluck('name')->join(', ') 
                                        : $anime->genres }}
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
                    <a 
                        href="{{ route('anime.show', $anime->mal_id) }}"
                        class="block"
                    >
                        <div 
                            class="flex flex-row-reverse items-center gap-4 p-4 hover:bg-gray-100 rounded-lg transition cursor-pointer"
                            @mouseenter="bg = '{{ asset('storage/' . $anime->background) }}'"
                            @mouseleave="bg = defaultBackgrounds[tab]"
                        >
                            <div class="flex-1 text-right">
                                <h3 class="font-semibold text-lg">{{ $anime->title }}</h3>
                                <p class="text-xs text-black mb-1">
                                    {{ is_array($anime->genres) 
                                        ? collect($anime->genres)->pluck('name')->join(', ') 
                                        : $anime->genres }}
                                </p>
                                <div class="flex items-center gap-1 mt-1">
                        <svg class="w-3 h-3 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        <span class="text-xs">{{ $anime->score }}</span>
                    </div>
                                <p class="text-xs text-black mb-1">
                                    حلقة: {{ $anime->episodes }}
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