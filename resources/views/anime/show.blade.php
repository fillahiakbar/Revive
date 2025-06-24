<x-app-layout>
    <div class="bg-no-repeat min-h-screen pt-20">
        <div class="max-w-7xl mx-auto px-4 py-20 text-white">
            <div class="flex flex-col lg:flex-row gap-8 items-start">

                {{-- Poster --}}
                <div class="w-full lg:w-64 flex-shrink-0">
                    <img src="{{ $anime['poster'] ?? asset('img/placeholder.png') }}" 
                         alt="{{ $anime['title'] }}"
                         class="w-full shadow-lg border border-white/10">
                </div>

                {{-- Info utama --}}
                <div class="flex-1">
                    <div class="bg-white/20 backdrop-blur-lg p-8 mx-8 my-6 shadow-2xl">
                        <div class="mb-8">
                            <div class="flex items-start justify-between mb-4">
                                <h1 class="text-4xl font-bold text-right flex-1">{{ $anime['title'] }}</h1>
                                <div class="flex items-center gap-2 text-xs flex-shrink-0 ml-4">
                                    <div>
                                        <div class="bg-yellow-600 border border-yellow-400 px-2 py-1 text-center min-w-[50px] text-black font-bold text-[10px] uppercase tracking-wide">IMDB</div>
                                        <div class="text-white font-bold text-center text-sm">{{ $anime['score'] ?? 'N/A' }}</div>
                                    </div>
                                    <div>
                                        <div class="bg-blue-600 border border-blue-400 px-2 py-1 text-center min-w-[50px] text-white font-bold text-[10px] uppercase tracking-wide">MAL</div>
                                        <div class="text-white text-center font-bold text-sm">{{ $anime['score'] ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </div>

                            @if(isset($anime['title_japanese']))
                                <p class="text-white/80 mb-4 text-right">{{ $anime['title'] }} < {{ $anime['title_japanese'] }}</p>
                            @endif
                        </div>

                        {{-- Info boxes --}}
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
                                            @if(isset($anime['studios'][0]['name']))
                                                {{ $anime['studios'][0]['name'] }}
                                            @else
                                                Unknown
                                            @endif
                                        </span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">التصنيفات:</span>
                                        <span class="font-semibold">
                                            @if(!empty($anime['genres']))
                                                @foreach($anime['genres'] as $index => $genre)
                                                    {{ $genre }}@if($index < count($anime['genres']) - 1), @endif
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
                                        <span class="font-semibold">{{ $anime['types'][0] ?? 'Unknown' }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">عدد الحلقات:</span>
                                        <span class="font-semibold">{{ $anime['episodes'] ?? 'Unknown' }}</span>
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
                                        <span class="font-semibold">{{ $anime['duration'] ?? 'Unknown' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Synopsis --}}
            <div class="mt-8">
                <div class="bg-white/30 backdrop-blur-lg p-6 border border-white/20">
                    <h2 class="text-2xl font-bold mb-4 text-right">قائمة الأنمي</h2>
                    <h3 class="text-lg font-semibold mb-4 text-right">ملخص الأنمي</h3>
                    <p class="text-white/90 leading-relaxed text-justify text-sm">
                        {{ $anime['synopsis'] ?? 'لا يوجد ملخص متاح لهذا الأنمي.' }}
                    </p>
                </div>
            </div>

           {{-- Download links --}}
@if ($animeLink && $animeLink->batches->isNotEmpty())
    <div class="px-8 md:px-12 lg:px-16 mx-20 lg:mx-20 xl:mx-0 bg-white/20 backdrop-blur-lg p-4 space-y-4 mt-10">
        <h2 class="text-xl font-bold pr-3 text-white">روابط الحلقات</h2>
        <div id="download-scroll" class="max-h-[600px] overflow-y-auto pr-1 space-y-3">
            @foreach ($animeLink->batches as $batch)
                @php
                    $validLinks = $batch->batchLinks->filter(function ($link) {
                        return $link->url_torrent || $link->url_mega || $link->url_gdrive;
                    });
                @endphp

                @if ($validLinks->isNotEmpty())
                    @foreach ($validLinks as $link)
                        <div class="overflow-hidden shadow-md pt-3">
                            <div class="bg-black text-white text-center py-2 font-semibold text-xs md:text-sm">
                                {{ $batch->name }} - Episodes {{ $batch->episodes }}
                            </div>
                            <div class="bg-white flex flex-wrap md:flex-nowrap justify-between items-center px-3 py-2 gap-2">
                                <div class="flex flex-wrap gap-2">
                                    @if ($link->url_torrent)
                                        <a href="{{ $link->url_torrent }}" class="bg-white text-black border px-2 py-1 rounded hover:bg-gray-100 text-xs shadow">
                                            Torrent ({{ $link->resolution }}p)
                                        </a>
                                    @endif
                                    @if ($link->url_mega)
                                        <a href="{{ $link->url_mega }}" class="bg-white text-black border px-2 py-1 rounded hover:bg-gray-100 text-xs shadow">
                                            Mega ({{ $link->resolution }}p)
                                        </a>
                                    @endif
                                    @if ($link->url_gdrive)
                                        <a href="{{ $link->url_gdrive }}" class="bg-white text-black border px-2 py-1 rounded hover:bg-gray-100 text-xs shadow">
                                            GDrive ({{ $link->resolution }}p)
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            @endforeach
        </div>
    </div>
@endif

        </div>
    </div>
</x-app-layout>
