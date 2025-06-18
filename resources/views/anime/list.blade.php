<x-app-layout>
    <div class="pt-24 pb-6">
        <div class="max-w-7xl pt-24 mx-auto sm:px-6 lg:px-8">

            {{-- Judul Halaman --}}
            <div class="text-white text-right mb-6">
                <p class="text-sm">الرئيسية . قائمة الأنمي A-Z</p>
                <h1 class="text-sm font-bold">ترتيب حسب الحروف</h1>
            </div>

            {{-- Navigation A-Z + 0-9 + الكل --}}
            <div class="px-4 py-6 rounded shadow mb-10 text-black">
                <div class="flex flex-wrap justify-center items-center gap-2 text-sm font-bold">
                    {{-- ALL --}}
                    <a href="{{ route('anime.list', ['letter' => 'ALL']) }}"
                       class="h-8 px-3 rounded-md flex items-center justify-center transition
                       {{ $letter === 'ALL' ? 'bg-red-500 text-black' : 'bg-gray-200 hover:bg-red-400 hover:text-black' }}">
                        الكل
                    </a>

                    {{-- 0-9 --}}
                    <a href="{{ route('anime.list', ['letter' => '0-9']) }}"
                       class="h-8 px-3 rounded-md flex items-center justify-center transition
                       {{ $letter === '0-9' ? 'bg-red-500 text-black' : 'bg-gray-200 hover:bg-red-400 hover:text-black' }}">
                        0-9
                    </a>

                    {{-- A-Z --}}
                    @foreach(range('Z', 'A') as $char)
                        <a href="{{ route('anime.list', ['letter' => $char]) }}"
                           class="w-8 h-8 rounded-md flex items-center justify-center transition
                           {{ $letter === $char ? 'bg-red-500 text-black' : 'bg-gray-200 hover:bg-red-400 hover:text-black' }}">
                            {{ $char }}
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Info Pagination --}}
            @if($animes->total() > 0)
                <div class="text-white text-sm mb-4 text-center lg:text-right">
                    Showing {{ $animes->firstItem() }} to {{ $animes->lastItem() }} of {{ $animes->total() }} results
                    (Page {{ $animes->currentPage() }} of {{ $animes->lastPage() }})
                </div>
            @endif

            {{-- Anime Grid --}}
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

            {{-- Loading Spinner --}}
            <div id="loading" class="hidden text-center py-8">
                <div class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-gray-600 rounded-lg">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                         fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor"
                              d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 
                              5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 
                              3 7.938l3-2.647z"/>
                    </svg>
                    Loading...
                </div>
            </div>

            {{-- Pagination --}}
            @if ($animes->hasPages())
                <div class="flex justify-center mt-10">
                    <nav class="flex items-center space-x-2 rtl:space-x-reverse">
                        @if (!$animes->onFirstPage())
                            <a href="{{ $animes->url(1) }}"
                               class="w-10 h-10 flex items-center justify-center rounded-full bg-white hover:bg-gray-300 text-black text-lg transition"
                               title="First Page">
                                &laquo;
                            </a>
                            <a href="{{ $animes->previousPageUrl() }}"
                               class="w-10 h-10 flex items-center justify-center rounded-full bg-white hover:bg-gray-300 text-black text-lg transition"
                               title="Previous Page">
                                &lsaquo;
                            </a>
                        @endif

                        @foreach ($animes->getUrlRange(max(1, $animes->currentPage() - 2), min($animes->lastPage(), $animes->currentPage() + 2)) as $page => $url)
                            <a href="{{ $url }}"
                               class="w-10 h-10 flex items-center justify-center rounded-full text-sm transition
                               {{ $animes->currentPage() == $page ? 'bg-red-500 text-white font-bold' : 'bg-white text-black hover:bg-gray-300' }}"
                               title="Page {{ $page }}">
                                {{ $page }}
                            </a>
                        @endforeach

                        @if ($animes->hasMorePages())
                            <a href="{{ $animes->nextPageUrl() }}"
                               class="w-10 h-10 flex items-center justify-center rounded-full bg-white hover:bg-gray-300 text-black text-lg transition"
                               title="Next Page">
                                &rsaquo;
                            </a>
                            <a href="{{ $animes->url($animes->lastPage()) }}"
                               class="w-10 h-10 flex items-center justify-center rounded-full bg-white hover:bg-gray-300 text-black text-lg transition"
                               title="Last Page">
                                &raquo;
                            </a>
                        @endif
                    </nav>
                </div>

                <div class="text-center mt-4 text-gray-400 text-sm">
                    Page {{ $animes->currentPage() }} of {{ $animes->lastPage() }}
                    ({{ $animes->total() }} total results)
                </div>
            @endif
        </div>
    </div>

    {{-- JS Error Handler --}}
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
