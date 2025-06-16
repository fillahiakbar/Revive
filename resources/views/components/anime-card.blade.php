<a href="{{ route('anime.show', $anime['mal_id']) }}"
   class="bg-gray-900 rounded shadow hover:scale-105 transition transform overflow-hidden">
    <img src="{{ $anime['images']['jpg']['image_url'] }}" alt="{{ $anime['title'] }}"
         class="w-full h-72 object-cover">
    <div class="p-4">
        <h3 class="text-lg font-semibold truncate">{{ $anime['title'] }}</h3>
        <p class="text-sm text-gray-400 mt-1">{{ $anime['type'] ?? 'TV' }}</p>
    </div>
</a>
