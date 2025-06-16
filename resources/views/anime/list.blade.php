<x-app-layout>
    <div class="pt-24 pb-6">
        <div class="max-w-7xl pt-24 mx-auto sm:px-6 lg:px-8">

            {{-- Judul Halaman --}}
            <div class="text-white text-right mb-6">
                <p class="text-sm">الرئيسية . قائمة الأنمي A-Z</p>
                <h1 class="text-sm font-bold">ترتيب حسب الحروف</h1>
            </div>

            {{-- Navigation A-Z + 0-9 + الكل --}}
            <div class="px-4 py-6 rounded shadow mb-10">
                <div class="flex flex-wrap justify-center items-center gap-2 text-sm font-bold">
                    @foreach(range('A', 'Z') as $char)
                        <a href="{{ route('anime.list', ['letter' => $char]) }}"
                           class="w-8 h-8 rounded-md flex items-center justify-center transition
                           {{ $letter === $char ? 'bg-red-500 text-white' : 'bg-gray-200 hover:bg-red-400 hover:text-white' }}">
                            {{ $char }}
                        </a>
                    @endforeach

                    {{-- 0-9 --}}
                    <a href="{{ route('anime.list', ['letter' => '0-9']) }}"
                       class="h-8 px-3 rounded-md flex items-center justify-center transition
                       {{ $letter === '0-9' ? 'bg-red-500 text-white' : 'bg-gray-200 hover:bg-red-400 hover:text-white' }}">
                        0-9
                    </a>

                    {{-- ALL --}}
                    <a href="{{ route('anime.list', ['letter' => 'ALL']) }}"
                       class="h-8 px-3 rounded-md flex items-center justify-center transition
                       {{ $letter === 'ALL' ? 'bg-red-500 text-white' : 'bg-gray-200 hover:bg-red-400 hover:text-white' }}">
                        الكل
                    </a>
                </div>
            </div>

            {{-- Anime Grid --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @forelse($animes as $anime)
                    <a href="{{ route('anime.show', $anime['mal_id']) }}"
                       class="relative bg-gray-900 text-white rounded-lg overflow-hidden shadow hover:shadow-lg transition group">

                        {{-- Badge --}}
                        <div class="absolute top-2 left-2 z-10 flex flex-col gap-1">
                            <span class="bg-green-500 text-xs px-2 py-0.5 rounded">
                                SUB {{ $anime['episodes'] ?? '?' }} eps
                            </span>
                        </div>

                        {{-- Image --}}
                        <img src="{{ $anime['images']['jpg']['image_url'] }}"
                             alt="{{ $anime['title'] }}"
                             class="w-full h-60 object-cover group-hover:scale-105 transition-transform duration-300">

                        {{-- Detail --}}
                        <div class="p-2 text-xs">
                            <h3 class="font-bold truncate">{{ $anime['title'] }}</h3>
                            <p class="text-gray-400">
                                {{ strtoupper($anime['type']) ?? 'N/A' }} • {{ $anime['duration'] ?? 'N/A' }}
                            </p>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full text-center text-gray-500">
                        Not Have Anime In This Page
                    </div>
                @endforelse
            </div>

{{-- Pagination --}}
@if ($animes->hasPages())
    <div class="flex justify-center mt-10">
        <nav class="flex items-center space-x-2 rtl:space-x-reverse">
            {{-- First Page --}}
            @if (!$animes->onFirstPage())
                <a href="{{ $animes->url(1) }}" class="w-10 h-10 flex items-center justify-center rounded-full bg-white hover:bg-gray-300 text-black text-lg">&laquo;</a>
                <a href="{{ $animes->previousPageUrl() }}" class="w-10 h-10 flex items-center justify-center rounded-full bg-white hover:bg-gray-300 text-black text-lg">&lsaquo;</a>
            @endif

            {{-- Page Links --}}
            @foreach ($animes->getUrlRange(max(1, $animes->currentPage() - 2), min($animes->lastPage(), $animes->currentPage() + 2)) as $page => $url)
                <a href="{{ $url }}"
                   class="w-10 h-10 flex items-center justify-center rounded-full text-sm
                   {{ $animes->currentPage() == $page ? 'bg-red-500 text-white' : 'bg-white text-black hover:bg-gray-300' }}">
                    {{ $page }}
                </a>
            @endforeach

            {{-- Last Page --}}
            @if ($animes->hasMorePages())
                <a href="{{ $animes->nextPageUrl() }}" class="w-10 h-10 flex items-center justify-center rounded-full bg-white hover:bg-gray-300 text-black text-lg">&rsaquo;</a>
                <a href="{{ $animes->url($animes->lastPage()) }}" class="w-10 h-10 flex items-center justify-center rounded-full bg-white hover:bg-gray-300 text-black text-lg">&raquo;</a>
            @endif
        </nav>
    </div>
@endif




        </div>
    </div>
</x-app-layout>
