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
<div id="anime-list" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 min-h-[600px]">
    @forelse($animes as $anime)
        <a href="{{ route('anime.show', $anime['mal_id']) }}"
           class="relative text-white rounded-lg overflow-hidden shadow hover:shadow-lg transition group">
            {{-- Type Badge --}}
@foreach ($anime['types'] ?? [] as $type)
    <span class="badge-{{ strtolower($type) }} text-xs px-2 py-0.5 rounded text-white font-medium">
        {{ $type }}
    </span>
@endforeach




            {{-- Image --}}
            <div class="w-full h-60 bg-gray-800 flex items-center justify-center">
                @if($anime['image'])
                    <img src="{{ $anime['image'] }}"
                         alt="{{ $anime['title'] }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                         loading="lazy">
                @else
                    <div class="text-gray-500 flex justify-center items-center">
                        <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                        </svg>
                    </div>
                @endif
            </div>

            {{-- Title --}}
            <div class="p-2 text-xs">
                <h3 class="font-bold truncate" title="{{ $anime['title'] }}">
                    {{ $anime['title'] }}
                </h3>
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

</x-app-layout>