<x-app-layout>
    <div class="min-h-screen  text-white pt-20">
        {{-- Header Section --}}
        <div class="">
            <div class="container mx-auto px-4 py-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-white pt-20 mb-2">
                            {{ $genreData['name'] ?? 'Unknown Genre' }} Anime
                        </h1>
                    </div>
                    
                    {{-- Back Button --}}
                    <a href="{{ route('anime.genres') }}" 
                       class=" hover:text-red-700  px-4 py-2 pt-20 rounded-lg transition-colors">
                        Back to Genre
                    </a>
                </div>
            </div>
        </div>

           {{-- Info Pagination --}}
@if($pagination && ($pagination['items_count'] ?? 0) > 0)
    <div class="text-white text-sm mb-4 text-center lg:text-right">
        Showing {{ (($pagination['current_page'] ?? 1) - 1) * 24 + 1 }}
        to {{ (($pagination['current_page'] ?? 1) - 1) * 24 + count($animeList) }}
        of {{ $pagination['items_count'] ?? '?' }} results
        (Page {{ $pagination['current_page'] ?? 1 }} of {{ $pagination['last_visible_page'] ?? '?' }})
    </div>
@endif

{{-- Anime Grid --}}
<div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 pl-24 pr-24 gap-4 min-h-[600px]">
    @forelse($animeList as $anime)
        <a href="{{ route('anime.show', $anime['mal_id']) }}"
           class="relative text-white rounded-lg overflow-hidden shadow hover:shadow-lg transition group">

            {{-- Badge --}}
            <div class="absolute left-1 z-10 flex flex-col gap-1">
                <span class="badge-{{ strtolower($anime['type'] ?? 'unknown') }} text-xs px-2 py-0.5 rounded text-white font-medium">
                    {{ $anime['type'] ?? 'Unknown' }}
                </span>
            </div>

            {{-- Image --}}
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
                    {{ $anime['duration'] ?? 'N/A' }}
                </p>
                @if(isset($anime['episodes']))
                    <p class="text-gray-400 text-xs">{{ $anime['episodes'] }} Episodes</p>
                @endif
            </div>
        </a>
    @empty
        {{-- No Result --}}
        <div class="col-span-full text-center text-gray-500 py-20">
            <div class="text-6xl mb-4">📺</div>
            <h3 class="text-xl font-bold mb-2">No Anime Found</h3>
            <p>No anime available for this genre</p>
        </div>
    @endforelse
</div>

{{-- Pagination --}}
@if ($pagination && ($pagination['last_visible_page'] ?? 1) > 1)
    <div class="flex justify-center mt-10">
        <nav class="flex items-center space-x-2 rtl:space-x-reverse">
            @if(($pagination['current_page'] ?? 1) > 1)
                <a href="{{ request()->url() }}?{{ http_build_query(array_merge(request()->query(), ['page' => 1])) }}"
                   class="w-10 h-10 flex items-center justify-center rounded-full bg-white hover:bg-gray-300 text-black text-lg transition"
                   title="First Page">&laquo;</a>
                <a href="{{ request()->url() }}?{{ http_build_query(array_merge(request()->query(), ['page' => ($pagination['current_page'] - 1)])) }}"
                   class="w-10 h-10 flex items-center justify-center rounded-full bg-white hover:bg-gray-300 text-black text-lg transition"
                   title="Previous Page">&lsaquo;</a>
            @endif

            @php
                $start = max(1, $pagination['current_page'] - 2);
                $end = min($pagination['last_visible_page'], $pagination['current_page'] + 2);
            @endphp
            @for ($i = $start; $i <= $end; $i++)
                <a href="{{ request()->url() }}?{{ http_build_query(array_merge(request()->query(), ['page' => $i])) }}"
                   class="w-10 h-10 flex items-center justify-center rounded-full text-sm transition {{ $pagination['current_page'] == $i ? 'bg-red-500 text-white font-bold' : 'bg-white text-black hover:bg-gray-300' }}"
                   title="Page {{ $i }}">{{ $i }}</a>
            @endfor

            @if(($pagination['has_next_page'] ?? false))
                <a href="{{ request()->url() }}?{{ http_build_query(array_merge(request()->query(), ['page' => ($pagination['current_page'] + 1)])) }}"
                   class="w-10 h-10 flex items-center justify-center rounded-full bg-white hover:bg-gray-300 text-black text-lg transition"
                   title="Next Page">&rsaquo;</a>
                <a href="{{ request()->url() }}?{{ http_build_query(array_merge(request()->query(), ['page' => $pagination['last_visible_page']])) }}"
                   class="w-10 h-10 flex items-center justify-center rounded-full bg-white hover:bg-gray-300 text-black text-lg transition"
                   title="Last Page">&raquo;</a>
            @endif
        </nav>
    </div>

    <div class="text-center mt-4 text-gray-400 text-sm">
        Page {{ $pagination['current_page'] ?? 1 }} of {{ $pagination['last_visible_page'] ?? '?' }}
        ({{ $pagination['items_count'] ?? '?' }} total results)
    </div>
@endif

{{-- JS Fallback --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const images = document.querySelectorAll('img[src]');
        images.forEach(img => {
            img.addEventListener('error', function () {
                this.style.display = 'none';
                const fallback = this.nextElementSibling;
                if (fallback) fallback.style.display = 'flex';
            });
        });
    });
</script>



</x-app-layout>