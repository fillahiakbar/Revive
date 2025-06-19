<x-app-layout>
    <div class="bg-no-repeat min-h-screen pt-20">
        <div class="max-w-7xl mx-auto px-4 py-20 text-white">

            {{-- Content dengan Poster dan Info Boxes --}}
            <div class="flex flex-col lg:flex-row gap-8 items-start">

                {{-- Poster dengan pemisah --}}
                <div class="w-full lg:w-64 flex-shrink-0">
                    <img src="{{ $anime['images']['jpg']['large_image_url'] }}" 
                         alt="{{ $anime['title'] }}"
                         class="w-full shadow-lg border border-white/10">
                </div>

                {{-- Header dengan Judul dan Rating + Info Boxes --}}
                <div class="flex-1">
                    <div class="bg-white/20 backdrop-blur-lg p-8 mx-8 my-6 shadow-2xl">

                        {{-- Header --}}
                        <div class="mb-8">
                            <div class="flex items-start justify-between mb-4">
                                <h1 class="text-4xl font-bold text-right flex-1">{{ $anime['title'] }}</h1>

                                {{-- Ratings --}}
                                <div class="flex items-center gap-2 text-xs flex-shrink-0 ml-4">
                                    <div>
                                        <div class="bg-yellow-600 border border-yellow-400  px-2 py-1 text-center min-w-[50px] text-black font-bold text-[10px] uppercase tracking-wide">IMDB</div>
                                        <div class="text-white font-bold text-center text-sm">{{ $anime['score'] ?? 'N/A' }}</div>
                                    </div>
                                    <div>
                                        <div class="bg-blue-600 border border-blue-400  px-2 py-1 text-center min-w-[50px] text-white font-bold text-[10px] uppercase tracking-wide">MAL</div>
                                        <div class="text-white text-center font-bold text-sm">{{ $anime['score'] ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </div>

                            @if(isset($anime['title_japanese']))
                                <p class="text-white/80 mb-4 text-right">{{ $anime['title'] }} < {{ $anime['title_japanese'] }}</p>
                            @endif
                        </div>

                        {{-- Info Boxes --}}
                        <div class="grid md:grid-cols-2 gap-4 ">
                            <div class="bg-white/90 text-black p-4">
                                <div class="grid gap-3 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">حالة الأنمي:</span>
                                        <span class="font-semibold">{{ $anime['status'] ?? 'Unknown' }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">موسم الإصدار:</span>
                                        <span class="font-semibold">
                                            @if(isset($anime['season']) && isset($anime['year']))
                                                {{ ucfirst($anime['season']) }} {{ $anime['year'] }}
                                            @else
                                                {{ $anime['year'] ?? 'Unknown' }}
                                            @endif
                                        </span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">الاستوديو:</span>
                                        <span class="font-semibold">
                                            @if(isset($anime['studios']) && count($anime['studios']) > 0)
                                                {{ $anime['studios'][0]['name'] }}
                                            @else
                                                Unknown
                                            @endif
                                        </span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">التصنيفات:</span>
                                        <span class="font-semibold">
                                            @if(isset($anime['genres']) && count($anime['genres']) > 0)
                                                @foreach($anime['genres'] as $index => $genre)
                                                    {{ $genre['name'] }}@if($index < count($anime['genres']) - 1), @endif
                                                @endforeach
                                            @else
                                                Unknown
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-white/90 text-black p-4">
                                <div class="grid gap-3 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">نوع الأنمي:</span>
                                        <span class="font-semibold">{{ $anime['type'] ?? 'Unknown' }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">عدد الحلقات:</span>
                                        <span class="font-semibold">
                                            @if(isset($anime['episodes']) && $anime['episodes'])
                                                {{ $anime['episodes'] }}
                                            @else
                                                <span class="bg-yellow-400 px-2 py-1  text-xs">Unknown</span>
                                            @endif
                                        </span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">البث الحصري:</span>
                                        <span class="font-semibold">
                                            @if(isset($anime['aired']['from']))
                                                {{ date('M j, Y', strtotime($anime['aired']['from'])) }}
                                            @else
                                                Unknown
                                            @endif
                                        </span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">مدة الحلقة:</span>
                                        <span class="font-semibold">
                                            @if(isset($anime['duration']))
                                                {{ $anime['duration'] }}
                                            @else
                                                Unknown
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            {{-- Synopsis Section --}}
            <div class="mt-8">
                <div class="bg-white/30 backdrop-blur-lg p-6 border border-white/20">
                    <h2 class="text-2xl font-bold mb-4 text-right">قائمة الأنمي</h2>
                    <h3 class="text-lg font-semibold mb-4 text-right">ملخص الأنمي</h3>
                    <p class="text-white/90 leading-relaxed text-justify text-sm">
                        {{ $anime['synopsis'] ?? 'لا يوجد ملخص متاح لهذا الأنمي.' }}
                    </p>
                </div>
            </div>

 @if ($downloadLinks && $downloadLinks->isNotEmpty())
    <div class="px-8 md:px-12 lg:px-16 mx-20 lg:mx-20 xl:mx-0 bg-white/20 backdrop-blur-lg p-4 space-y-4 mt-10">
        <h2 class="text-xl font-bold pr-3 text-white">روابط الحلقات (من قاعدة البيانات)</h2>

        <div id="download-scroll" class="max-h-[600px] overflow-y-auto pr-1 space-y-3">
            @foreach ($downloadLinks->sortBy('episode_number') as $link)
                <div class="overflow-hidden shadow-md pt-3">
                    <div class="bg-black text-white text-center py-2 font-semibold text-xs md:text-sm">
                        {{ $link->title }} - Episode {{ $link->episode_number }}
                    </div>
                    <div class="bg-white flex flex-wrap md:flex-nowrap justify-between items-center px-3 py-2 gap-2">
                        <div class="flex flex-wrap gap-2">
                            @if ($link->links['arabic_sub'] ?? false)
                                <a href="{{ $link->links['arabic_sub'] }}" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs whitespace-nowrap">
                                    Mp4 مع الترجمة
                                </a>
                            @endif
                            @if ($link->links['gdrive'] ?? false)
                                <a href="{{ $link->links['gdrive'] }}" class="flex items-center gap-1 bg-white text-black border px-2 py-1 rounded hover:bg-gray-100 text-xs shadow">
                                    GDrive
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3" />
                                    </svg>
                                </a>
                            @endif
                            @if ($link->links['mp4upload'] ?? false)
                                <a href="{{ $link->links['mp4upload'] }}" class="flex items-center gap-1 bg-white text-black border px-2 py-1 rounded hover:bg-gray-100 text-xs shadow">
                                    Mp4upload
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3" />
                                    </svg>
                                </a>
                            @endif
                            @if ($link->links['torrent'] ?? false)
                                <a href="{{ $link->links['torrent'] }}" class="flex items-center gap-1 bg-white text-black border px-2 py-1 rounded hover:bg-gray-100 text-xs shadow">
                                    .Torrent
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3" />
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif


            {{-- Similar Anime Section --}}
            @if(isset($similarAnime) && count($similarAnime) > 0)
                <div class="mt-12">
                    <div class="bg-white/20 backdrop-blur-lg p-6 border border-white/20">
                        <h2 class="text-2xl font-bold mb-6 text-right">أنمي مشابه</h2>
                        
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                            @foreach($similarAnime as $similar)
                                <a href="{{ route('anime.show', $similar['mal_id']) }}"
                                   class="relative text-white  overflow-hidden shadow hover:shadow-lg transition group">


                                    {{-- Image with fallback --}}
                                    <div class="w-full h-60 bg-gray-800 flex items-center justify-center">
                                        @if(isset($similar['images']['jpg']['large_image_url']) && $similar['images']['jpg']['large_image_url'])
                                            <img src="{{ $similar['images']['jpg']['large_image_url'] }}"
                                                 alt="{{ $similar['title'] }}"
                                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                                 loading="lazy"
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                             {{-- Badge --}}
                        <div class="absolute left-1 z-10 flex flex-col gap-1">
                            <span class="badge-{{ strtolower($anime['type'] ?? 'unknown') }} text-xs px-2 py-0.5  text-white font-medium">
                                {{ $anime['type'] ?? 'Unknown' }}
                            </span>
                        </div>
                                            <div class="hidden w-full h-full items-center justify-center text-gray-500">
                                                <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                          d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                                                          clip-rule="evenodd"></path>
                                                </svg>
                                            </div>

                                                               

                                        @elseif(isset($similar['images']['jpg']['image_url']) && $similar['images']['jpg']['image_url'])
                                            <img src="{{ $similar['images']['jpg']['image_url'] }}"
                                                 alt="{{ $similar['title'] }}"
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
                                        <h3 class="font-bold truncate" title="{{ $similar['title'] }}">
                                            {{ $similar['title'] ?? 'Unknown Title' }}
                                        </h3>
                                        @if(isset($similar['episodes']) && $similar['episodes'])
                                            <p class="text-gray-400 text-xs">{{ $similar['episodes'] }} Episodes</p>
                                        @endif
                                        @if(isset($similar['score']) && $similar['score'])
                                            <div class="flex items-center gap-1 mt-1">
                                                <svg class="w-3 h-3 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                                <span class="text-xs">{{ $similar['score'] }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </a>
                            @endforeach
                        </div>

                        {{-- Show More Button --}}
                        @if(count($similarAnime) >= 12)
                            <div class="text-center mt-6">
                                <a href="{{ route('anime.search', ['q' => explode(' ', $anime['title'])[0] ?? $anime['title']]) }}" 
                                   class="bg-red-600 hover:bg-red-700 text-white px-6 py-3  transition duration-200 inline-flex items-center gap-2">
                                    <span>عرض المزيد من الأنمي المشابه</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </a>
                            </div>
                        @endif
                    </div>
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