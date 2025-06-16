<x-app-layout>
      <div class="text-white">

        {{-- 🌟 FULL-WIDTH BANNER --}}
        <section class="relative w-full h-[730px] overflow-hidden">
            {{-- Gambar Background --}}
            <div class="absolute top-0 left-0 w-full h-full">
                <img src="{{ asset('img/720.png') }}"
                     alt="Anime Banner"
                     class="w-full h-full object-cover object-left" />
            </div>

            {{-- Gradient Overlay --}}
            <div class="absolute inset-0 z-[2] pointer-events-none">
                <div class="absolute inset-0 bg-gradient-to-l from-black/30 via-black/60 to-transparent"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-black/40 to-transparent"></div>
            </div>

            {{-- Konten teks --}}
            <div class="relative z-10 h-full flex items-center justify-start pt-10 px-8 md:px-20 text-right">
                <div class="w-full md:w-[40%] max-w-xl">
                    <span class="block text-sm mb-2 text-white/80 font-medium">#1 تسليط الضوء</span>
                    <h1 class="text-3xl md:text-5xl font-extrabold leading-tight mb-4 drop-shadow">
                        {{ $anime['title'] }}
                    </h1>
                    <div class="flex flex-wrap justify-start pt-4 gap-3 text-sm font-light items-center">
                        <span class="flex items-center gap-1 bg-red-600 px-2 py-0.5 rounded text-white">
                            {{ $anime['rating'] ?? 'N/A' }}
                        </span>
                        <span class="flex items-center gap-1 bg-blue-600 px-2 py-0.5 rounded text-white">
                            Eps {{ $anime['episodes'] ?? '??' }}
                        </span>
                        <span class="text-white/80">{{ $anime['type'] }}</span>
                        <span class="text-white/60">min per ep {{ $anime['duration'] ?? 'Unknown' }}</span>
                        <span class="text-white/60">{{ \Carbon\Carbon::parse($anime['aired']['from'])->format('M d, Y') }}</span>
                    </div>
                    <div class="pt-5">
                        <p class="text-white/80 text-2xl">Crime is timeless. By the year 2071, humanity has expanded across the galaxy, filling the surface of other planets with settlements like those on Earth. These new societies are plagued by murder, drug use, and theft, and intergalactic outlaws are hunted by a growing number of tough bounty hunters...</p>
                    </div>
                </div>
            </div>
        </section>



        {{-- 📦 Section: الأحدث والأكثر زيارة --}}
        <section class="max-w-7xl mx-auto mt-20 grid grid-cols-1 md:grid-cols-4 gap-6 text-right">
            
            
            <!-- Overlay -->
            {{-- الأحدث --}}
            <div class="md:col-span-3">
            {{-- Gradient Overlay --}}
            <div class="absolute inset-0 z-[1] pointer-events-none">
                <div class="absolute inset-0 bg-gradient-to-l from-black/100 via-black/60 to-transparent"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-black/100 via-black/40 to-transparent"></div>
            </div>

                <h2 class="text-xl font-bold mb-4 border-r-4 border-blue-600 pr-2">أحدث الإصدارات</h2>
                <div class="grid grid-cols-1 gap-6">
                    @foreach ($latestReleases as $anime)
                        <a href="{{ route('anime.show', ['id' => $anime['mal_id']]) }}" class="block hover:bg-gray-700 transition rounded-lg">
                            <div class="bg-gray-800 p-4 flex items-center gap-4">
                                <img src="{{ $anime['images']['jpg']['image_url'] }}" class="w-24 h-32 object-cover rounded" alt="{{ $anime['title'] }}">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-lg">{{ $anime['title'] }}</h3>
                                    <div class="text-sm text-white/60 mb-2">
                                        {{ $anime['episodes'] ?? '?' }} حلقات • {{ $anime['type'] }}
                                    </div>
                                    <div class="flex flex-wrap gap-2 text-xs">
                                        @foreach ($anime['genres'] as $genre)
                                            <span class="bg-blue-600 px-2 py-0.5 rounded">{{ $genre['name'] }}</span>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="flex flex-col items-end gap-1 text-xs">
                                    <span class="bg-yellow-600 px-2 py-0.5 rounded">IMDB: {{ $anime['score'] ?? 'N/A' }}</span>
                                    <span class="bg-sky-600 px-2 py-0.5 rounded">MAL: {{ $anime['popularity'] ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- الأكثر زيارة dan التصنيفات --}}
            <div class="space-y-8">
                {{-- الأكثر زيارة --}}
                <div>
                    <h2 class="text-xl font-bold mb-4 border-r-4 border-purple-600 pr-2">الأكثر زيارة</h2>
                    <div class="space-y-4">
                        @foreach ($mostVisited as $index => $anime)
                            <a href="{{ route('anime.show', ['id' => $anime['mal_id']]) }}" class="block hover:bg-gray-700 transition rounded-lg">
                                <div class="flex items-center bg-gray-800 p-3 gap-3">
                                    <div class="text-purple-400 font-bold text-2xl w-8">
                                        {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                    </div>
                                    <img src="{{ $anime['images']['jpg']['image_url'] }}" class="w-12 h-16 object-cover rounded" alt="{{ $anime['title'] }}">
                                    <div class="flex-1">
                                        <div class="text-sm font-semibold">
                                            {{ Str::limit($anime['title'], 20) }}
                                        </div>
                                        <div class="text-xs text-white/60">
                                            {{ $anime['episodes'] ?? '?' }} حلقات
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>

                {{-- التصنيفات --}}
                <div class="bg-gray-900 bg-opacity-70 border border-blue-500 rounded-lg p-4">
                    <h2 class="text-xl font-bold text-right border-r-4 border-blue-400 pr-2 mb-4">التصنيفات</h2>
                    <div class="grid grid-cols-2 gap-y-2 text-sm text-white text-center">
                        @foreach(['Game','Action','Harem','Adventure','Historical','Cars','Horor','Comedy','Isekai','Dementia','Josei','Demons','Kids','Drama','Magic','Ecchi','Mecha','Fantasy'] as $tag)
                            <span>{{ $tag }}</span>
                        @endforeach
                    </div>
                    <a href="/genres"><button class="mt-4 w-full bg-gray-600 text-white py-1 rounded">إظهار المزيد</button>
                    </a>
                </div>
            </div>
        </section>

        {{-- 🟢 الأعمال الحالية --}}
        <section class="max-w-7xl mx-auto mt-20 p-6 bg-white text-black text-right rounded-lg shadow-md">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                {{-- Banner --}}
                @if(count($currentWorks) > 0)
                    <div class="order-1 md:order-2 flex justify-center">
                        <div class="relative bg-white rounded-lg overflow-hidden max-w-sm w-full">
                            <img src="{{ $currentWorks[0]['images']['webp']['large_image_url'] }}" alt="{{ $currentWorks[0]['title'] }}" class="rounded-lg w-full h-auto" />
                            <div class="absolute bottom-4 left-4">
                                <h3 class="text-white font-bold text-xl bg-black bg-opacity-60 px-4 py-1 rounded">{{ $currentWorks[0]['title'] }}</h3>
                                <p class="text-xs mt-2">
                                    © {{ \Carbon\Carbon::parse($currentWorks[0]['aired']['from'])->format('Y') }}
                                    {{ $currentWorks[0]['studios'][0]['name'] ?? 'Unknown Studio' }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- List --}}
                <div class="order-2 md:order-1 space-y-6">
                    <div class="flex justify-between items-center border-b pb-2">
                        <h2 class="text-xl font-bold border-l-4 border-red-500 pl-2">الأعمال الحالية</h2>
                        <span>اقتراحاتنا</span>
                    </div>

                    @foreach($currentWorks as $index => $anime)
                        @continue($index === 0)
                        <a href="{{ route('anime.show', ['id' => $anime['mal_id']]) }}" class="block hover:bg-gray-100 rounded-lg">
                            <div class="flex items-center gap-4 p-2">
                                <img src="{{ $anime['images']['webp']['image_url'] }}" alt="{{ $anime['title'] }}" class="w-16 h-20 rounded-lg object-cover">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-lg">{{ $anime['title'] }}</h3>
                                    <p class="text-xs text-gray-600 mb-1">
                                        {{ collect($anime['genres'])->pluck('name')->implode(', ') }}
                                    </p>
                                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                                        @php
                                            $totalEpisodes = $anime['episodes'] ?? null;
                                            $isAiring = $anime['airing'] ?? false;
                                            $progress = 0;
                                            if ($totalEpisodes && $isAiring) {
                                                $progress = 50;
                                            } elseif ($totalEpisodes && !$isAiring) {
                                                $progress = 100;
                                            }
                                        @endphp
                                        <div class="bg-red-500 h-2.5 rounded-full" style="width: {{ $progress }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>

    </div>
</x-app-layout>
