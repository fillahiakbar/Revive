<x-app-layout>
    <div class="text-white font-cairo relative z-10">

{{-- 🎬 Hero Banner Section --}}
@if($sliders->isNotEmpty())
<section class="relative w-full h-[800px] overflow-hidden">
    @foreach ($sliders as $index => $slide)
    <div class="absolute left-0 top-0 w-full h-full z-0 transition-opacity duration-700 @if($index === 0) opacity-100 @else @endif" data-slide
        data-title="{{ $slide->title }}"
        data-type="{{ $slide->type }}"
        data-duration="{{ $slide->duration }}"
        data-year="{{ $slide->year }}"
        data-quality="{{ $slide->quality }}"
        data-episodes="{{ $slide->episodes }}"
        data-description="{{ $slide->description }}">
        
        <div class="w-full h-full bg-cover bg-center" style="background-image: url('{{ asset('storage/' . $slide->image) }}');"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-transparent to-black/80"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-transparent via-transparent to-black/70"></div>
    </div>
    @endforeach

    {{-- Konten Slider Dinamis --}}
    <div class="relative z-10 h-full flex items-center justify-start px-4 md:px-8 lg:px-20">
        <div class="w-full md:w-[45%] lg:w-[40%] max-w-2xl space-y-6 text-right">
            <span class="inline-block text-sm text-white/80 font-medium"> {{ $sliders->first()->choice }}</span>
            <h1 id="slide-title" class="text-2xl md:text-4xl lg:text-5xl font-black leading-tight text-white drop-shadow-2xl">
                {{ $sliders->first()->title }}
            </h1>
            <div class="flex flex-wrap gap-2 md:gap-3 text-xs md:text-sm font-medium items-center text-white justify-start">
                <span class="px-2 md:px-3 py-1 rounded-lg flex items-center gap-1 bg-black/40">
                    <img src="/img/icon/play.png" alt="Type" class="w-4 h-4" />
                    <span id="slide-type">{{ $sliders->first()->type ?? 'TV' }}</span>
                </span>
                <span class="px-2 md:px-3 py-1 rounded-lg flex items-center gap-1 bg-black/40">
                    <img src="/img/icon/time.png" alt="Duration" class="w-4 h-4" />
                    <span id="slide-duration">{{ $sliders->first()->duration ?? '24m' }}</span>
                </span>
                <span class="px-2 md:px-3 py-1 rounded-lg shadow-lg flex items-center gap-1 bg-black/40">
                    <img src="/img/icon/date.png" alt="Year" class="w-4 h-4" />
                    <span id="slide-year">{{ $sliders->first()->year ?? '2024' }}</span>
                </span>
                <span id="slide-quality" class="bg-red-600 px-2 md:px-3 py-1 rounded-lg shadow-lg flex items-center gap-1">
                    {{ $sliders->first()->quality ?? 'HD' }}
                </span>
                <span class="bg-blue-600 px-2 md:px-3 py-1 rounded-lg shadow-lg flex items-center gap-1">
                    <img src="/img/icon/eps.png" alt="Episodes" class="w-4 h-4" />
                    <span id="slide-episodes">{{ $sliders->first()->episodes ?? '12 Eps' }}</span>
                </span>
            </div>
            <p id="slide-description" class="text-xs md:text-sm leading-relaxed text-white/90 max-w-lg">
                {{ $sliders->first()->description }}
            </p>
        </div>
    </div>

    {{-- Tombol Navigasi Slider --}}
    <div class="absolute bottom-20 pb-24 right-20 flex gap-2 z-20">
        <button id="next-slide" class="bg-white/20 hover:bg-white/30 p-2 md:p-3 rounded-full backdrop-blur-sm transition-all duration-300">
            <svg class="w-4 h-4 md:w-5 md:h-5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" />
            </svg>
        </button>
        <button id="prev-slide" class="bg-white/20 hover:bg-white/30 p-2 md:p-3 rounded-full backdrop-blur-sm transition-all duration-300">
            <svg class="w-4 h-4 md:w-5 md:h-5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" />
            </svg>
        </button>
    </div>

    <div class="absolute bottom-0 left-0 right-0 h-16 z-5" style="background: linear-gradient(to bottom, transparent, rgba(27, 27, 27, 0.5), rgba(27, 27, 27, 0.8));"></div>


</section>

{{-- Script dinamis ganti konten slider --}}
<script>
    const slides = document.querySelectorAll('[data-slide]');
    const title = document.getElementById('slide-title');
    const type = document.getElementById('slide-type');
    const duration = document.getElementById('slide-duration');
    const year = document.getElementById('slide-year');
    const quality = document.getElementById('slide-quality');
    const episodes = document.getElementById('slide-episodes');
    const description = document.getElementById('slide-description');

    let currentIndex = 0;

    function showSlide(index) {
        slides.forEach((slide, i) => {
            slide.style.opacity = i === index ? '1' : '0';
        });

        const slide = slides[index];
        title.textContent = slide.getAttribute('data-title');
        type.textContent = slide.getAttribute('data-type');
        duration.textContent = slide.getAttribute('data-duration');
        year.textContent = slide.getAttribute('data-year');
        quality.textContent = slide.getAttribute('data-quality');
        episodes.textContent = slide.getAttribute('data-episodes');
        description.textContent = slide.getAttribute('data-description');
    }

    document.getElementById('next-slide').addEventListener('click', () => {
        currentIndex = (currentIndex + 1) % slides.length;
        showSlide(currentIndex);
    });

    document.getElementById('prev-slide').addEventListener('click', () => {
        currentIndex = (currentIndex - 1 + slides.length) % slides.length;
        showSlide(currentIndex);
    });
</script>
@endif




        {{-- 📦 Main Content Section --}}
        <section class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-4 gap-6 px-4 pt-2 relative z-10 md:-mt-16 rounded-lg">
{{-- Latest Releases --}}
<div class="lg:col-span-3 order-1 lg:order-1">
    <h2 class="text-lg md:text-xl font-bold mb-6 pl-2 text-whit">أحدث الإصدارات</h2>
    
    {{-- Background blur container --}}
    <div class="bg-white/5 bg-opacity-30 backdrop-blur-lg rounded-xl p-6 border border-white/10 shadow-2xl">
        <div class="grid grid-cols-1 gap-4 md:gap-6">
            @foreach ($latestReleases as $anime)
                <a href="{{ route('anime.show', ['id' => $anime['mal_id']]) }}" class="block hover:bg-gray-700 hover:bg-opacity-50 transition-all duration-300 rounded-lg group">
                    <div class="p-4 flex items-center gap-4 relative overflow-hidden">
                        
                        {{-- Anime Image --}}
                        <div class="relative z-10 w-20 md:w-28 h-28 md:h-40 overflow-hidden rounded-lg flex-shrink-0 shadow-lg order-1">
                            <img src="{{ $anime['images']['jpg']['image_url'] }}" 
                                 class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" 
                                 alt="{{ $anime['title'] }}">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                        </div>
                        
                        {{-- Anime Info --}}
                        <div class="flex-1 relative z-10 min-w-0 space-y-3 order-2">
                            {{-- Title and Rating in same row --}}
                            <div class="flex items-center justify-between gap-4">
                                <h3 class="font-bold text-lg md:text-xl text-white group-hover:text-blue-400 transition-colors duration-300 truncate">
                                    {{ $anime['title'] }}
                                </h3>
                                
                                {{-- Ratings --}}
                                <div class="flex items-center gap-2 text-xs flex-shrink-0">


                                    {{-- MAL Rating --}}
                                    <div>
                                        <div class="bg-blue-600 border border-blue-400 rounded px-2 py-1 text-center min-w-[50px] text-white font-bold text-[10px] tracking-wide">MAL</div>
                                        <div class="text-white text-center font-bold text-sm">{{ $anime['score'] ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- Meta info --}}
                            <div class="flex flex-wrap gap-2 text-sm text-white/80">
                                <span class="flex items-center gap-1">عدد الحلقات: {{ $anime['episodes'] ?? '?' }}</span>
                            </div>

                            <div class="flex flex-wrap gap-2 text-sm text-white/80">
                                <span class="flex items-center gap-1">
                                    <span>النوع: {{ $anime['type'] }}</span>
                                </span>
                            </div>
                            
                            {{-- Genres --}}
                            <div class="flex flex-wrap gap-2">
                                <span class="text-xs font-medium text-white shadow-sm">التصنيفات:</span>
                                @foreach (array_slice($anime['genres'], 0, 3) as $genre)
                                    <span class="text-xs font-medium text-white shadow-sm">
                                        @if(!$loop->first), @endif{{ $genre['name'] }}
                                    </span>
                                @endforeach
                            </div>
                            
                            {{-- Batch Name --}}
                            <p class="text-sm text-white/70 line-clamp-2 leading-relaxed">
                                الإصدار: {{ Str::limit($anime['batch_names'], 150, '...') }}
                            </p>
                        </div>
                    </div>
                </a>
                {{-- ✨ Line Separator - Metode 1 --}}
                @if (!$loop->last)
                    <div class="w-full h-px bg-gradient-to-r from-transparent via-gray-400 to-transparent opacity-50"></div>
                @endif
            @endforeach
        </div>
    </div>
</div>

            {{-- Sidebar --}}
            <div class="space-y-8 order-2 lg:order-2">
                {{-- Most Visited Section --}}
                <div>
                    <h2 class="text-xl font-bold mb-6 text-white pl-3 relative z-10">
                        الأكثر زيارة
                    </h2>
                    <div class="bg-white/5 backdrop-blur-md rounded-lg border-white/10">
                        <div class="space-y-5 z-10 rounded-xl p-6 relative ">
                            <div class="rounded-lg">
                                 @foreach (array_slice($mostVisited, 0, 5) as $index => $anime)
                                <a href="{{ route('anime.show', ['id' => $anime['mal_id']]) }}" class="block duration-300 rounded-lg group">
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
    <span> {{ $anime['episodes'] ?? '??' }} Eps</span>
</div>
                            </div>
                                        
                                        {{-- Purple accent arrow --}}
                                        <div class="text-purple-400 opacity-0 group-hover:opacity-100 transition-opacity duration-300 order-4">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                            </svg>
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
                        @foreach(['Game','Action','Harem','Adventure','Historical','Cars','Horror','Comedy','Isekai','Dementia','Josei','Demons','Kids','Magic','Mecha','Fantasy'] as $tag)
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

    </div>
</x-app-layout>