<x-app-layout>
    <div class="max-w-7xl mx-auto py-10 pt-24 px-6 text-white">

        {{-- 🔵 Advanced Genre Selector --}}
        <form method="GET" action="{{ route('anime.genre.multi') }}">
            <x-genre-selector
                :genres="$genres"
                :selected="$selected ?? []"
                :selected-status="$selectedStatus ?? null"
                :selected-types="$selectedTypes ?? []"
                :selected-sort="$selectedSort ?? null"
                :query="$query ?? ''"
            />

            <div class="text-center mt-6 mb-10">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded text-lg">
                    بحث
                </button>
            </div>
        </form>

        {{-- Anime Grid --}}
        <div class="bg-white/5 backdrop-blur-md rounded p-4 backdrop-saturate-150">
            <div id="anime-list" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 min-h-[600px]">
                @forelse($animes as $anime)
                    <a href="{{ route('anime.show', $anime['mal_id']) }}"
                       class="relative text-white rounded-lg overflow-hidden shadow hover:shadow-lg transition group">

                        <div class="absolute left-1 z-10 flex flex-col gap-1">
                            <span class="badge-{{ strtolower($anime['type'] ?? 'unknown') }} text-xs px-2 py-0.5 rounded text-white font-medium">
                                {{ $anime['type'] ?? 'Unknown' }}
                            </span>
                        </div>

                        <div class="w-full h-60 bg-gray-800 flex items-center justify-center">
                            @if(isset($anime['images']['jpg']['image_url']))
                                <img src="{{ $anime['images']['jpg']['image_url'] }}"
                                     alt="{{ $anime['title'] }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                     loading="lazy"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="hidden w-full h-full items-center justify-center text-gray-500">
                                    <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            @else
                                <div class="flex items-center justify-center text-gray-500">
                                    <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <div class="p-2 text-xs">
                            <h3 class="font-bold truncate" title="{{ $anime['title'] }}">
                                {{ $anime['title'] ?? 'Unknown Title' }}
                            </h3>
                            <p class="text-gray-400">
                                @php
                                    preg_match('/\d+/', $anime['duration'] ?? '', $matches);
                                    $durationMinutes = $matches[0] ?? 'N/A';
                                @endphp
                                {{ $durationMinutes }}m
                            </p>
                            @if(isset($anime['episodes']))
                                <p class="text-gray-400 text-xs">{{ $anime['episodes'] }} Episodes</p>
                            @endif
                        </div>
                    </a>
                @empty
                    <div class="col-span-full text-center text-gray-500 py-20">
                        <div class="text-6xl mb-4">📺</div>
                        <h3 class="text-xl font-bold mb-2">No Anime Found</h3>
                        <p class="text-sm mt-2">Try changing filter or keyword.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Infinite Scroll Loader --}}
        @if($hasMorePages)
            <div id="loading" class="text-center py-8 hidden">
                <div class="inline-flex items-center px-4 py-2">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                </div>
            </div>
        @endif

    </div>

    @push('scripts')
        <script>
            let page = 2;
            const selectedGenres = {!! json_encode($selected ?? []) !!};
            let loading = false;
            let endReached = false;

            window.addEventListener('scroll', async function () {
                if (loading || endReached) return;

                const scrollTop = window.scrollY;
                const windowHeight = window.innerHeight;
                const docHeight = document.body.offsetHeight;

                if (scrollTop + windowHeight + 100 >= docHeight) {
                    loading = true;
                    document.getElementById('loading').classList.remove('hidden');

                    try {
                        const url = new URL(window.location.href);
                        url.searchParams.set('page', page);

                        const res = await fetch(url.toString(), {
                            headers: {'Accept': 'application/json'}
                        });

                        if (!res.ok) throw new Error("Failed to fetch");

                        const data = await res.json();

                        if (!data.length) {
                            endReached = true;
                        } else {
                            const container = document.getElementById('anime-list');
                            data.forEach(anime => {
                                const card = document.createElement('div');
                                card.innerHTML = `
                                    <a href="/anime/${anime.mal_id}" class="relative text-white rounded-lg overflow-hidden shadow hover:shadow-lg transition group">
                                        <div class="absolute left-1 z-10 flex flex-col gap-1">
                                            <span class="badge-${(anime.type ?? 'unknown').toLowerCase()} text-xs px-2 py-0.5 rounded text-white font-medium">
                                                ${anime.type ?? 'Unknown'}
                                            </span>
                                        </div>
                                        <div class="w-full h-60 bg-gray-800 flex items-center justify-center">
                                            <img src="${anime.images?.jpg?.image_url}" alt="${anime.title}" 
                                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                                loading="lazy"
                                                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <div class="hidden w-full h-full items-center justify-center text-gray-500">
                                                <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="p-2 text-xs">
                                            <h3 class="font-bold truncate" title="${anime.title}">
                                                ${anime.title ?? 'Unknown Title'}
                                            </h3>
                                            <p class="text-gray-400">
                                                ${(anime.duration?.match(/\d+/)?.[0] ?? 'N/A')}m
                                            </p>
                                            ${anime.episodes ? `<p class="text-gray-400 text-xs">${anime.episodes} Episodes</p>` : ''}
                                        </div>
                                    </a>
                                `;
                                container.appendChild(card.firstElementChild);
                            });
                            page++;
                        }
                    } catch (err) {
                        console.error("Gagal memuat data:", err);
                    } finally {
                        loading = false;
                        document.getElementById('loading').classList.add('hidden');
                    }
                }
            });
        </script>
    @endpush
</x-app-layout>
