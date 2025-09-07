<x-app-layout>
    <div class="pt-24 pb-6">
        <div class="max-w-7xl pt-24 mx-auto sm:px-6 lg:px-8">
            <div class="text-white text-right mb-6">
                <p class="text-sm">الرئيسية . قائمة الأنمي A-Z</p>
                <h1 class="text-sm font-bold">ترتيب حسب الحروف</h1>
            </div>
            <div class="px-4 py-6 rounded shadow mb-10 text-black">
                <div class="flex flex-wrap justify-center items-center gap-2 text-sm font-bold">
                    <a href="{{ route('anime.list', ['letter' => 'ALL']) }}"
                       class="h-8 px-3 rounded-md flex items-center justify-center transition
                       {{ $letter === 'ALL' ? 'bg-red-500 text-black' : 'bg-gray-200 hover:bg-red-400 hover:text-black' }}">
                        الكل
                    </a>
                    <a href="{{ route('anime.list', ['letter' => '0-9']) }}"
                       class="h-8 px-3 rounded-md flex items-center justify-center transition
                       {{ $letter === '0-9' ? 'bg-red-500 text-black' : 'bg-gray-200 hover:bg-red-400 hover:text-black' }}">
                        0-9
                    </a>
                    @foreach(range('Z', 'A') as $char)
                        <a href="{{ route('anime.list', ['letter' => $char]) }}"
                           class="w-8 h-8 rounded-md flex items-center justify-center transition
                           {{ $letter === $char ? 'bg-red-500 text-black' : 'bg-gray-200 hover:bg-red-400 hover:text-black' }}">
                            {{ $char }}
                        </a>
                    @endforeach
                </div>
            </div>
<div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 min-h-[600px]">
    @forelse($animes as $anime)
        <a href="{{ route('anime.show', $anime['mal_id']) }}"
           class="relative text-white rounded-lg overflow-hidden shadow hover:shadow-lg transition group">
<div class="flex flex-wrap gap-2 mt-2 px-2 absolute top-0 z-10">
    @foreach ($anime['types'] as $type)
        @php
            $color = $type['color'] ?? '#6b7280';
            $label = $type['name'] ?? $type;
        @endphp
        <span
            class="text-xs font-medium px-2 py-1 rounded"
            style="background-color: {{ $color }}; color: white;"
            title="{{ $label }}"
        >
            {{ $label }}
        </span>
    @endforeach
</div>
            <div class="w-full bg-gray-800 flex items-center justify-center">
                @php
                    $image = $anime['images']['jpg']['large_image_url'] ?? $anime['images']['jpg']['image_url'] ?? null;
                @endphp
                @if($image)
                    <img src="{{ $image }}"
                         alt="{{ $anime['title'] }}"
                         class="w-full shadow border border-white/10 transition-transform duration-300 group-hover:scale-105"
                         loading="lazy"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="hidden w-full h-full items-center justify-center text-gray-500 bg-gray-800">
                        <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                  d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                                  clip-rule="evenodd" />
                        </svg>
                    </div>
                @else
                    <div class="flex items-center justify-center text-gray-500 bg-gray-800">
                        <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                  d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                                  clip-rule="evenodd" />
                        </svg>
                    </div>
                @endif
            </div>
            <div class="p-2 text-xs">
<h3 class="font-bold truncate" title="{{ $anime['local_title'] ?? $anime['title'] }}">
    {{ $anime['local_title'] ?? $anime['title'] ?? 'Unknown Title' }}
</h3>
@if (!empty($anime['title_english']) && $anime['title_english'] !== ($anime['local_title'] ?? $anime['title']))
    <p class="text-gray-400 text-[11px] truncate" title="{{ $anime['title_english'] }}">
        {{ $anime['title_english'] }}
    </p>
@endif
                <p class="text-gray-400">
    {{ !empty($anime['duration']) ? $anime['duration'] : 'N/A' }}
</p>
                @if(!empty($anime['episodes']))
                        <p class="text-gray-400 text-xs">حلقات عدد: {{ $anime['episodes'] }}</p>
                @endif
                @if(!empty($anime['score']))
                    <div class="flex items-center gap-1 mt-1">
                        <svg class="w-3 h-3 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        <span class="text-xs">{{ $anime['score'] }}</span>
                    </div>
                @endif
            </div>
        </a>
    @empty
        <div class="col-span-full text-center text-gray-500 py-20">
            <div class="text-6xl mb-4">📺</div>
            <h3 class="text-xl font-bold mb-2">No Anime Found</h3>
            <p>No anime available for letter "{{ $letter }}"</p>
            <p class="text-sm mt-2">Try selecting a different letter or check back later.</p>
        </div>
    @endforelse
    @if($animes->count() > 0 && $animes->count() < 24)
        @for($i = $animes->count(); $i < 24; $i++)
            <div class="invisible">
                <div class="w-full bg-transparent"></div>
                <div class="p-2 text-xs">
                    <div class="h-4 bg-transparent"></div>
                    <div class="h-3 bg-transparent mt-1"></div>
                </div>
            </div>
        @endfor
    @endif
</div>
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
