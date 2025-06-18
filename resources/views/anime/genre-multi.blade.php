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

            {{-- 🟩 Tombol Konfirmasi --}}
            <div class="text-center mt-6 mb-10">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded text-lg">
                    بحث
                </button>
            </div>
        </form>
        


         {{-- Anime Grid --}}
            <div class="bg-white/5 backdrop-blur-md rounded p-4 backdrop-saturate-150">
             <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 min-h-[600px]">
                @forelse($animes as $anime)
                    <a href="{{ route('anime.show', $anime['mal_id']) }}"
                       class="relative text-white rounded-lg overflow-hidden shadow hover:shadow-lg transition group">

                        {{-- Badge --}}
                        <div class="absolute left-1 z-10 flex flex-col gap-1">
                            <span class="badge-{{ strtolower($anime['type'] ?? 'unknown') }} text-xs px-2 py-0.5 rounded text-white font-medium">
                                {{ $anime['type'] ?? 'Unknown' }}
                            </span>
                        </div>

                        {{-- Image with fallback --}}
                        <div class="w-full h-60 bg-gray-800 flex items-center justify-center">
                            @if(isset($anime['images']['jpg']['image_url']) && $anime['images']['jpg']['image_url'])
                                <img src="{{ $anime['images']['jpg']['image_url'] }}"
                                     alt="{{ $anime['title'] }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                     loading="lazy"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="hidden w-full h-full items-center justify-center text-gray-500">
                                    <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                              d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                                              clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            @else
                                <div class="flex items-center justify-center text-gray-500">
                                    <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                              d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                                              clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        {{-- Detail --}}
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
                            @if(isset($anime['episodes']) && $anime['episodes'])
                                <p class="text-gray-400 text-xs">{{ $anime['episodes'] }} Episodes</p>
                            @endif
                        </div>
                    </a>
                @empty
                    {{-- No Result --}}
                    <div class="col-span-full text-center text-gray-500 py-20">
                        <div class="text-6xl mb-4">📺</div>
                        <h3 class="text-xl font-bold mb-2">No Anime Found</h3>
                        <p>No anime available for letter "{{ $letter }}"</p>
                        <p class="text-sm mt-2">Try selecting a different letter or check back later.</p>
                    </div>
                @endforelse

                {{-- Grid Filler --}}
                @if($animes->count() > 0 && $animes->count() < 24)
                    @for($i = $animes->count(); $i < 24; $i++)
                        <div class="invisible">
                            <div class="w-full h-60 bg-transparent"></div>
                            <div class="p-2 text-xs">
                                <div class="h-4 bg-transparent"></div>
                                <div class="h-3 bg-transparent mt-1"></div>
                            </div>
                        </div>
                    @endfor
                @endif
            </div>
            </div>
           

        {{-- Loader Infinite Scroll --}}
        <div id="loading" class="text-center py-8 hidden">
            <span>Loading For All...</span>
        </div>

    </div>

    {{-- Infinite Scroll Script --}}
    @push('scripts')
        <script>
            let page = 2;
            const selectedGenres = {!! json_encode($selected ?? []) !!};
            let loading = false;
            let endReached = false;

            document.getElementById('submitGenres')?.addEventListener('click', function () {
                const checked = [...document.querySelectorAll('input[name="genres[]"]:checked')]
                    .map(el => el.value);
                if (checked.length === 0) return alert("Pilih minimal 1 genre");
                window.location.href = `/genres?ids=${checked.join(',')}`;
            });

            window.addEventListener('scroll', async function () {
                if (loading || endReached) return;

                const scrollTop = window.scrollY;
                const windowHeight = window.innerHeight;
                const docHeight = document.body.offsetHeight;

                if (scrollTop + windowHeight + 100 >= docHeight) {
                    loading = true;
                    document.getElementById('loading').classList.remove('hidden');

                    try {
                        const res = await fetch(`/genres?ids=${selectedGenres.join(',')}&page=${page}`, {
                            headers: {'Accept': 'application/json'}
                        });

                        const data = await res.json();

                        if (!data.length) {
                            endReached = true;
                        } else {
                            const container = document.getElementById('anime-list');
                            data.forEach(anime => {
                                const card = document.createElement('div');
                                card.innerHTML = `
                                    <a href="/anime/${anime.mal_id}" class="bg-gray-900 rounded shadow overflow-hidden hover:scale-105 transition transform">
                                        <img src="${anime.images.jpg.image_url}" class="w-full h-72 object-cover" />
                                        <div class="p-4">
                                            <h3 class="text-lg font-semibold truncate">${anime.title}</h3>
                                            <p class="text-sm text-gray-400 mt-1">${anime.type ?? 'TV'}</p>
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
