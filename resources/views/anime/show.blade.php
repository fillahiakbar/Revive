<x-app-layout>
    <div class="bg-cover bg-no-repeat min-h-screen pt-20">
        <div class="max-w-7xl mx-auto px-4 py-20 text-white">
            
            {{-- Judul dan Gambar --}}
            <div class="flex flex-col md:flex-row gap-8 items-start">
                {{-- Poster --}}
                <img src="{{ $anime['images']['jpg']['large_image_url'] }}" alt="{{ $anime['title'] }}"
                     class="w-64 rounded-lg shadow-lg border border-white/10">

                {{-- Informasi --}}
                <div class="flex-1">
                    <h1 class="text-4xl font-bold mb-4">{{ $anime['title'] }}</h1>
                    <p class="text-white/80 leading-relaxed text-justify">{{ $anime['synopsis'] }}</p>

                    <div class="grid grid-cols-2 gap-x-6 gap-y-3 mt-6 text-sm text-white/90">
                        <div><strong>الحالة:</strong> {{ $anime['status'] }}</div>
                        <div><strong>النوع:</strong> {{ $anime['type'] }}</div>
                        <div><strong>عدد الحلقات:</strong> {{ $anime['episodes'] ?? '??' }}</div>
                        <div><strong>الإستوديو:</strong> {{ $anime['studios'][0]['name'] ?? 'Unknown' }}</div>
                        <div><strong>البث:</strong> {{ \Carbon\Carbon::parse($anime['aired']['from'])->format('M d, Y') }}</div>
                        <div><strong>التصنيفات:</strong> {{ collect($anime['genres'])->pluck('name')->join(', ') }}</div>
                    </div>
                </div>
            </div>

         {{-- Card Transparan Utama --}}
<div class="mt-16 bg-white/5 backdrop-blur rounded-xl shadow-xl p-6 space-y-6">

    {{-- Header --}}
    <h2 class="text-2xl font-bold border-r-4 border-red-600 pr-3 text-white">قائمة الحلقات</h2>

    {{-- Scrollable episode list --}}
    <div id="episode-scroll" class="max-h-[600px] overflow-y-auto pr-1 space-y-4">


        @foreach ($episodes as $ep)
            <!-- Card per episode -->
            <div class="overflow-hidden shadow-md pt-5">

                <!-- Judul episode (bar hitam) -->
                <div class="bg-black text-white text-center py-3 font-semibold text-sm md:text-base">
                    {{ $ep['title'] ?? 'Black Clover Episode ' . $ep['mal_id'] }}
                </div>

                <!-- Tombol download (bar putih) -->
                <div class="bg-white flex flex-wrap md:flex-nowrap justify-between items-center px-4 py-3 gap-3">

                    <!-- Tombol putih (flex ke kiri di layout besar) -->
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ $ep['gdrive_link'] ?? '#' }}" class="flex items-center gap-1 bg-white text-black border px-3 py-1 rounded hover:bg-gray-100 text-sm shadow">
                            GDrive <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3" />
                            </svg>
                        </a>
                        <a href="{{ $ep['mp4upload_link'] ?? '#' }}" class="flex items-center gap-1 bg-white text-black border px-3 py-1 rounded hover:bg-gray-100 text-sm shadow">
                            Mp4upload <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3" />
                            </svg>
                        </a>
                        <a href="{{ $ep['torrent_link'] ?? '#' }}" class="flex items-center gap-1 bg-white text-black border px-3 py-1 rounded hover:bg-gray-100 text-sm shadow">
                            .Torrent <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3" />
                            </svg>
                        </a>
                    </div>

                    <!-- Tombol merah (Mp4 مع الترجمة) -->
                    <a href="{{ $ep['mp4_arabic_link'] ?? '#' }}" class="bg-red-600 hover:bg-red-700 text-white px-4 py-1.5 rounded text-sm whitespace-nowrap">
                        Mp4 مع الترجمة
                    </a>
                </div>
            </div>
        @endforeach

    </div>
</div>


        </div>
    </div>
</x-app-layout>
