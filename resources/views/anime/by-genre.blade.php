{{-- Ganti @extends('layouts.app') dengan x-app-layout --}}
<x-app-layout>
    <div class="min-h-screen bg-gray-900 text-white pt-20">
        {{-- Header Section --}}
        <div class="bg-gray-800 border-b border-gray-700">
            <div class="container mx-auto px-4 py-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-white mb-2">
                            {{ $genreData['name'] ?? 'Unknown Genre' }} Anime
                        </h1>
                        <p class="text-gray-400">
                            {{ count($animeList) }} anime found
                        </p>
                    </div>
                    
                    {{-- Back Button --}}
                    <a href="{{ route('anime.genres') }}" 
                       class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded-lg transition-colors">
                        ← Back to Genres
                    </a>
                </div>
            </div>
        </div>

        <div class="container mx-auto px-4 py-8">
            {{-- Filter & Sort Section --}}
            <div class="mb-8 bg-gray-800 rounded-lg p-6">
                <form method="GET" class="flex flex-wrap gap-4 items-center">
                    {{-- Sort Options --}}
                    <div class="flex items-center gap-2">
                        <label class="text-gray-300">Sort by:</label>
                        <select name="sort" class="bg-gray-700 border border-gray-600 rounded px-3 py-2 text-white">
                            <option value="popularity" {{ request('sort') == 'popularity' ? 'selected' : '' }}>Popularity</option>
                            <option value="score" {{ request('sort') == 'score' ? 'selected' : '' }}>Score</option>
                            <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>Title</option>
                            <option value="start_date" {{ request('sort') == 'start_date' ? 'selected' : '' }}>Release Date</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded transition-colors">
                        Apply Filter
                    </button>
                </form>
            </div>

            {{-- Debug Info (hapus setelah testing) --}}
            <div class="mb-4 bg-yellow-600 text-yellow-900 p-4 rounded-lg">
                <strong>Debug Info:</strong><br>
                Genre ID: {{ $genre_id }}<br>
                Genre Name: {{ $genreData['name'] ?? 'N/A' }}<br>
                Anime Count: {{ count($animeList) }}<br>
                Current URL: {{ request()->url() }}
            </div>

            {{-- Anime Grid --}}
            @if(count($animeList) > 0)
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-6">
                    @foreach($animeList as $anime)
                        <div class="bg-gray-800 rounded-lg overflow-hidden hover:bg-gray-700 transition-colors group">
                            {{-- Anime Poster --}}
                            <div class="aspect-[3/4] overflow-hidden">
                                <img src="{{ $anime['images']['jpg']['large_image_url'] ?? $anime['images']['jpg']['image_url'] ?? 'https://via.placeholder.com/300x400' }}" 
                                     alt="{{ $anime['title'] }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            </div>
                            
                            {{-- Anime Info --}}
                            <div class="p-4">
                                <h3 class="font-semibold text-white text-sm mb-2 line-clamp-2">
                                    {{ $anime['title'] }}
                                </h3>
                                
                                <div class="flex items-center justify-between text-xs text-gray-400">
                                    <span>{{ $anime['year'] ?? 'N/A' }}</span>
                                    @if(isset($anime['score']) && $anime['score'])
                                        <span class="bg-yellow-600 text-yellow-100 px-2 py-1 rounded">
                                            ⭐ {{ number_format($anime['score'], 1) }}
                                        </span>
                                    @endif
                                </div>
                                
                                {{-- Action Buttons --}}
                                <div class="mt-3 flex gap-2">
                                    <a href="{{ route('anime.show', $anime['mal_id']) }}" 
                                       class="flex-1 bg-red-600 hover:bg-red-700 text-white text-xs py-2 px-3 rounded text-center transition-colors">
                                        View Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if($pagination && ($pagination['has_next_page'] ?? false))
                    <div class="mt-12 flex justify-center gap-4">
                        {{-- Previous Page --}}
                        @if(($pagination['current_page'] ?? 1) > 1)
                            <a href="{{ request()->url() }}?{{ http_build_query(array_merge(request()->query(), ['page' => ($pagination['current_page'] ?? 1) - 1])) }}" 
                               class="bg-gray-700 hover:bg-gray-600 px-4 py-2 rounded transition-colors">
                                ← Previous
                            </a>
                        @endif
                        
                        {{-- Current Page Info --}}
                        <span class="bg-red-600 px-4 py-2 rounded">
                            Page {{ $pagination['current_page'] ?? 1 }} of {{ $pagination['last_visible_page'] ?? 1 }}
                        </span>
                        
                        {{-- Next Page --}}
                        @if($pagination['has_next_page'] ?? false)
                            <a href="{{ request()->url() }}?{{ http_build_query(array_merge(request()->query(), ['page' => ($pagination['current_page'] ?? 1) + 1])) }}" 
                               class="bg-gray-700 hover:bg-gray-600 px-4 py-2 rounded transition-colors">
                                Next →
                            </a>
                        @endif
                    </div>
                @endif
            @else
                {{-- No Anime Found --}}
                <div class="text-center py-20">
                    <div class="text-6xl mb-4">😢</div>
                    <h3 class="text-2xl font-bold text-gray-300 mb-2">No Anime Found</h3>
                    <p class="text-gray-400">Sorry, no anime found for this genre.</p>
                    <a href="{{ route('anime.genres') }}" 
                       class="inline-block mt-4 bg-red-600 hover:bg-red-700 px-6 py-3 rounded-lg transition-colors">
                        Browse Other Genres
                    </a>
                </div>
            @endif
        </div>
    </div>

    {{-- Custom CSS --}}
    <style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    </style>
</x-app-layout>