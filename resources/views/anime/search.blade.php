<x-app-layout>
    <div class="pt-24 pb-6">
        <div class="max-w-7xl pt-24 mx-auto sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="text-white text-right mb-6">
                <p class="text-sm">الرئيسية . البحث</p>
                <h1 class="text-sm font-bold">نتائج البحث</h1>
            </div>

            {{-- Search Form --}}
            <div class="max-w-2xl mx-auto mb-8">
                <form action="{{ route('anime.search') }}" method="GET" class="flex gap-2">
                    <input type="text" name="q" value="{{ $query }}" placeholder="ابحث عن الأنمي..."
                        class="flex-1 bg-gray-800 border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                        required>
                    <button type="submit"
                        class="bg-red-600 hover:bg-red-700 px-6 py-3 rounded-lg transition duration-200 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        بحث
                    </button>
                </form>
            </div>

            {{-- Search Info --}}
            @if ($query)
                <div class="text-white text-center mb-6">
                    <p class="text-sm">البحث عن: <span class="text-red-400 font-semibold">"{{ $query }}"</span>
                    </p>
                    <p class="text-xs text-gray-400 mt-1">تم العثور على {{ $animeList->count() }} نتيجة</p>
                </div>
            @endif

            {{-- Error --}}
            @if (isset($error))
                <div class="max-w-2xl mx-auto mb-8">
                    <div class="bg-red-600/20 border border-red-500 rounded-lg p-4 text-center">
                        <p class="text-red-400">{{ $error }}</p>
                    </div>
                </div>
            @endif

            {{-- Anime Grid --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 min-h-[600px]">
                @forelse($animeList as $anime)
                    <a href="{{ route('anime.show', $anime['mal_id']) }}"
                        class="relative text-white rounded-lg overflow-hidden shadow hover:shadow-lg transition group block h-full">

                        {{-- Badge (Types) --}}
                        <div
                            class="flex flex-wrap gap-1 mt-2 px-2 absolute top-0 z-10 left-0 right-0 pointer-events-none">
                            @foreach ($anime['types'] ?? [] as $type)
                                @php
                                    $label = is_array($type) ? $type['name'] : $type;
                                    $color = is_array($type) ? $type['color'] ?? '#6b7280' : '#6b7280';
                                @endphp
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded shadow-sm"
                                    style="background-color: {{ $color }}; color: white;">
                                    {{ $label }}
                                </span>
                            @endforeach
                        </div>

                        {{-- Poster --}}
                        <div
                            class="w-full bg-gray-800 flex items-center justify-center aspect-[2/3] relative overflow-hidden">
                            @php
                                $image =
                                    $anime['images']['jpg']['large_image_url'] ??
                                    ($anime['images']['jpg']['image_url'] ?? null);
                            @endphp
                            @if ($image)
                                <img src="{{ $image }}"
                                    alt="{{ $anime['local_title'] ?? ($anime['title'] ?? 'Anime') }}"
                                    class="w-full h-full object-cover shadow border border-white/10 transition-transform duration-300 group-hover:scale-105"
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
                                <div class="flex items-center justify-center text-gray-500 bg-gray-800 w-full h-full">
                                    <span class="text-xs">No Image</span>
                                </div>
                            @endif
                        </div>

                        {{-- Details --}}
                        <div class="p-2 text-xs">
                            <h3 class="font-bold truncate" title="{{ $anime['local_title'] ?? $anime['title'] }}">
                                {{ $anime['local_title'] ?? ($anime['title'] ?? 'Unknown Title') }}
                            </h3>
                            @php $baseTitle = $anime['local_title'] ?? ($anime['title'] ?? ''); @endphp
                            @if (!empty($anime['title_english']) && $anime['title_english'] !== $baseTitle)
                                <p class="text-gray-400 truncate">{{ $anime['title_english'] }}</p>
                            @endif

                            <p class="text-gray-400">
                                @php
                                    preg_match('/\d+/', $anime['duration'] ?? '', $matches);
                                    $durationMinutes = $matches[0] ?? 'N/A';
                                @endphp
                                {{ $durationMinutes }}m
                            </p>

                            @if (!empty($anime['score']))
                                <div class="flex items-center gap-1 mt-1">
                                    <svg class="w-3 h-3 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    <span class="text-xs">{{ $anime['score'] }}</span>
                                </div>
                            @endif
                        </div>
                    </a>
                @empty
                    {{-- No Results --}}
                    <div class="col-span-full text-center text-gray-500 py-20">
                        <div class="text-6xl mb-4">🔍</div>
                        <h3 class="text-xl font-bold mb-2 text-gray-300">لم يتم العثور على نتائج</h3>
                        <p class="text-gray-400 mb-4">لم نتمكن من العثور على أي أنمي يطابق بحثك "{{ $query }}"
                        </p>
                        <div class="text-sm text-gray-500">
                            <p>جرّب:</p>
                            <ul class="list-disc list-inside mt-2 space-y-1">
                                <li>التحقق من الإملاء</li>
                                <li>استخدام كلمات مختلفة</li>
                                <li>استخدام مصطلحات أكثر عمومية</li>
                            </ul>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- JS fallback for broken images --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const images = document.querySelectorAll('img[src]');
            images.forEach(img => {
                img.addEventListener('error', function() {
                    this.style.display = 'none';
                    const fallback = this.nextElementSibling;
                    if (fallback) fallback.style.display = 'flex';
                });
            });
        });
    </script>
</x-app-layout>
