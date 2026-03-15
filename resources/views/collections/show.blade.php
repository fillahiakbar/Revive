<x-app-layout>
    <div class="pt-24 pb-6">
        <div class="max-w-7xl pt-24 mx-auto sm:px-6 lg:px-8">
            <div class="text-white text-right mb-6">
                <p class="text-sm">
                    <a href="{{ route('collections.index') }}"
                        class="hover:text-red-400 transition text-2xl font-bold mt-2">سلسلة
                    </a>
                    <a class="hover:text-red-400 transition text-2xl font-bold mt-2">{{ $collection->title }}</a>
                </p>
                <h1 class="text-l mt-2">عدد الأعمال : {{ $collection->animeLinks->count() }}</h1>
            </div>

            {{-- Anime Grid --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 min-h-[600px]">
                @forelse($collection->animeLinks as $anime)
                    <a href="{{ route('anime.show', $anime->mal_id) }}"
                        class="relative text-white rounded-lg overflow-hidden shadow hover:shadow-lg transition group block h-full">

                        {{-- Badge (Types) --}}
                        <div
                            class="flex flex-wrap gap-2 mt-2 px-2 absolute top-0 z-10 left-0 right-0 pointer-events-none">
                            @foreach ($anime->types as $type)
                                @php
                                    $color = $type->color ?? '#6b7280';
                                    $label = $type->name ?? $type;
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
                                $poster = $anime->poster;
                            @endphp
                            @if ($poster)
                                <img src="{{ $poster }}" alt="{{ $anime->title }}"
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

                            {{-- Collection Label Overlay --}}
                            @if (!empty($anime->pivot->collection_label))
                                <div class="absolute inset-x-0 bottom-0 z-10 text-center px-2 pt-8 pb-3"
                                    style="background: linear-gradient(to top, rgba(30, 27, 75, 1) 0%, rgba(30, 27, 75, 0.85) 40%, rgba(30, 27, 75, 0) 100%);">
                                    <span class="text-sm md:text-base font-bold text-white"
                                        style="text-shadow: 0 2px 4px rgba(0,0,0,0.5);">
                                        {{ $anime->pivot->collection_label }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        {{-- Details --}}
                        <div class="pt-2 text-xs text-right">
                            <h3 class="font-bold truncate text-white text-sm" title="{{ $anime->title }}">
                                {{ $anime->title }}
                            </h3>

                            {{-- Score --}}
                            <div class="flex justify-start items-center gap-1 mt-1">
                                @if (!empty($anime->mal_score))
                                    <span class="text-xs font-bold text-white">{{ $anime->mal_score }}</span>
                                    <svg class="w-3 h-3 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                @endif
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full text-center text-gray-500 py-10">
                        <p>No anime found in this collection.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
