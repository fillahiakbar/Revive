<x-app-layout>
    <div class="bg-no-repeat min-h-screen pt-20">
        <div class="max-w-7xl mx-auto px-4 py-20 text-white">

            {{-- Content dengan Poster dan Info Boxes --}}
            <div class="flex flex-col lg:flex-row gap-8 items-start">

                {{-- Poster dengan pemisah --}}
                <div class="w-full lg:w-64 flex-shrink-0">
                    <img src="{{ $anime['images']['jpg']['large_image_url'] }}" 
                         alt="{{ $anime['title'] }}"
                         class="w-full rounded-lg shadow-lg border border-white/10">
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
                                    {{-- IMDB Rating --}}
                                    <div>
                                        <div class="bg-yellow-600 border border-yellow-400 rounded px-2 py-1 text-center min-w-[50px] text-black font-bold text-[10px] uppercase tracking-wide">IMDB</div>
                                        <div class="text-white font-bold text-center text-sm">{{ $anime['score'] ?? 'N/A' }}</div>
                                    </div>

                                    {{-- MAL Rating --}}
                                    <div>
                                        <div class="bg-blue-600 border border-blue-400 rounded px-2 py-1 text-center min-w-[50px] text-white font-bold text-[10px] uppercase tracking-wide">MAL</div>
                                        <div class="text-white text-center font-bold text-sm">{{ $anime['score'] ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </div>

                            @if(isset($anime['title_japanese']))
                                <p class="text-white/80 mb-4 text-right">{{ $anime['title'] }} < {{ $anime['title_japanese'] }}</p>
                            @endif
                        </div>

                        {{-- Info Boxes --}}
                        <div class="grid md:grid-cols-2 gap-4">

                            {{-- Box 1 - Status Info --}}
                            <div class="bg-white/90 text-black p-4 rounded-lg">
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

                            {{-- Box 2 - Episode Info --}}
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
                                                <span class="bg-yellow-400 px-2 py-1 rounded text-xs">Unknown</span>
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

            {{-- Episode List --}}
            <div class="px-8 md:px-12 lg:px-16 mx-20 lg:mx-20 xl:mx-0 bg-white/20 backdrop-blur-lg p-4 space-y-4 mt-10">
                <h2 class="text-xl font-bold pr-3 text-white">قائمة الحلقات</h2>

                <div id="episode-scroll" class="max-h-[600px] overflow-y-auto pr-1 space-y-3">
                    @foreach ($allEpisodes as $ep)
                        <div class="overflow-hidden shadow-md pt-3">
                            <div class="bg-black text-white text-center py-2 font-semibold text-xs md:text-sm">
                                {{ $anime['title'] ?? 'Black Clover Episode ' . $ep['mal_id'] }} Episode {{ $ep['mal_id'] ?? '??' }}
                            </div>
                            <div class="bg-white flex flex-wrap md:flex-nowrap justify-between items-center px-3 py-2 gap-2">
                                <div class="flex flex-wrap gap-2">
                                    <a href="{{ $ep['mp4_arabic_link'] ?? '#' }}" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs whitespace-nowrap">
                                        Mp4 مع الترجمة
                                    </a>
                                    <a href="{{ $ep['gdrive_link'] ?? '#' }}" class="flex items-center gap-1 bg-white text-black border px-2 py-1 rounded hover:bg-gray-100 text-xs shadow">
                                        GDrive
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3" />
                                        </svg>
                                    </a>
                                    <a href="{{ $ep['mp4upload_link'] ?? '#' }}" class="flex items-center gap-1 bg-white text-black border px-2 py-1 rounded hover:bg-gray-100 text-xs shadow">
                                        Mp4upload
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3" />
                                        </svg>
                                    </a>
                                    <a href="{{ $ep['torrent_link'] ?? '#' }}" class="flex items-center gap-1 bg-white text-black border px-2 py-1 rounded hover:bg-gray-100 text-xs shadow">
                                        .Torrent
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
