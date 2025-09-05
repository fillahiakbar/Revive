<x-app-layout>
    <div class="min-h-screen text-white pt-20">
        {{-- Header --}}
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
                       class="hover:text-red-700 px-4 py-2 pt-20 rounded-lg transition-colors">
                        Back to Genre
                    </a>
                </div>
            </div>
        </div>

        @php
            // per page untuk kalkulasi teks info (default 24)
            $__perPage = $perPage ?? 24;

            // Normalisasi $animeList → collection
            $__items = $animeList instanceof \Illuminate\Pagination\LengthAwarePaginator
                ? collect($animeList->items())
                : collect($animeList ?? []);

            // Auto buat $pagination kalau view menerima paginator langsung
            if (!isset($pagination) && $animeList instanceof \Illuminate\Pagination\LengthAwarePaginator) {
                $pagination = [
                    'current_page'      => $animeList->currentPage(),
                    'last_visible_page' => $animeList->lastPage(),
                    'has_next_page'     => $animeList->hasMorePages(),
                    'items_count'       => $animeList->total(),
                ];
            }
        @endphp

        {{-- Grid --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 pl-24 pr-24 gap-4 min-h-[600px]">
            @forelse($__items as $card)
                {{-- Pakai komponen poster & detail dari DB --}}
                <x-anime.card :anime="$card" />
            @empty
                <div class="col-span-full text-center text-gray-500 py-20">
                    <div class="text-6xl mb-4">📺</div>
                    <h3 class="text-xl font-bold mb-2">No Anime Found</h3>
                    <p>No anime available for this genre</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @isset($pagination)
            @if(($pagination['last_visible_page'] ?? 1) > 1)
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
                            $end   = min($pagination['last_visible_page'], $pagination['current_page'] + 2);
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
        @endisset

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
    </div>
</x-app-layout>
