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

        {{-- 🔴 Container Anime --}}
        <div id="anime-list" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($animes as $anime)
                <a href="{{ route('anime.show', $anime['mal_id']) }}"
                   class="bg-gray-900 rounded shadow hover:scale-105 transition transform overflow-hidden">
                    <img src="{{ $anime['images']['jpg']['image_url'] }}" alt="{{ $anime['title'] }}"
                         class="w-full h-72 object-cover">
                    <div class="p-4">
                        <h3 class="text-lg font-semibold truncate">{{ $anime['title'] }}</h3>
                        <p class="text-sm text-gray-400 mt-1">{{ $anime['type'] ?? 'TV' }}</p>
                    </div>
                </a>
            @endforeach
        </div>

        {{-- Loader Infinite Scroll --}}
        <div id="loading" class="text-center py-8 hidden">
            <span>Memuat lebih banyak...</span>
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
