{{-- components/anime/card.blade.php --}}
@props(['anime'])

<a href="{{ route('anime.show', $anime['mal_id']) }}"
   class="relative text-white rounded-lg overflow-hidden shadow hover:shadow-lg transition group">

    {{-- Badge (Types) --}}
    <div class="flex flex-wrap gap-2 mt-2 px-2 absolute top-0 z-10">
        @foreach ($anime['types'] ?? [] as $type)
            @php
                $color = $type['color'] ?? '#6b7280';
                $label = $type['name'] ?? $type;
            @endphp
            <span class="text-xs font-medium px-2 py-1 rounded"
                  style="background-color: {{ $color }}; color: white;">
                {{ $label }}
            </span>
        @endforeach
    </div>

    {{-- Poster --}}
    <div class="w-full bg-gray-800 flex items-center justify-center">
        @php
            $image = $anime['images']['jpg']['large_image_url'] ?? $anime['images']['jpg']['image_url'] ?? $anime['poster'] ?? null;
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

    {{-- Title / Detail --}}
    <div class="p-2 text-xs">
        <h3 class="font-bold truncate" title="{{ $anime['local_title'] ?? $anime['title'] }}">
            {{ $anime['local_title'] ?? $anime['title'] ?? 'Unknown Title' }}
        </h3>
        @if (!empty($anime['title_english']) && $anime['title_english'] !== ($anime['local_title'] ?? $anime['title']))
            <p class="text-gray-400 text-[11px] truncate" title="{{ $anime['title_english'] }}">
                {{ $anime['title_english'] }}
            </p>
        @endif

        {{-- Durasi & Episode --}}
        <p class="text-gray-400">
            @php
                preg_match('/\d+/', $anime['duration'] ?? '', $matches);
                $durationMinutes = $matches[0] ?? 'N/A';
            @endphp
            {{ $durationMinutes }}m
        </p>
        @if(!empty($anime['episodes']))
            <p class="text-gray-400 text-xs">{{ $anime['episodes'] }} Episodes</p>
        @endif

        {{-- Score --}}
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
