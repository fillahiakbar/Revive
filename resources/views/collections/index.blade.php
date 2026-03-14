<x-app-layout>
    <div class="pt-24 pb-6">
        <div class="max-w-7xl pt-24 mx-auto sm:px-6 lg:px-8">
            <div class="text-white text-right mb-6">
                <h1 class="text-xl font-bold">الرئيسية</h1>
                <p class="text-sm">قائمة السلاسل</p>
                <p class="text-sm">عددها: {{ $collections->total() }}</p>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 min-h-[600px]">
                @forelse($collections as $collection)
                    <a href="{{ route('collections.show', $collection->slug) }}"
                        class="relative text-white rounded-lg overflow-hidden shadow hover:shadow-lg transition group">

                        {{-- Poster --}}
                        <div
                            class="w-full bg-gray-800 flex items-center justify-center aspect-[2/3] rounded-lg overflow-hidden relative">
                            <img src="{{ $collection->poster_url }}" alt="{{ $collection->title }}"
                                class="w-full h-full object-cover shadow border border-white/10 transition-transform duration-300 group-hover:scale-105"
                                loading="lazy"
                                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="hidden w-full h-full items-center justify-center text-gray-500 bg-gray-800">
                                <span class="text-xs">Image Error</span>
                            </div>
                        </div>

                        {{-- Title Below --}}
                        <div class="mt-2 text-center text-sm">
                            <h3 class="font-bold truncate text-white group-hover:text-red-400 transition"
                                title="{{ $collection->title }}">
                                {{ $collection->title }}
                            </h3>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full text-center text-gray-500 py-20">
                        <div class="text-6xl mb-4">📂</div>
                        <h3 class="text-xl font-bold mb-2">No Collections Found</h3>
                    </div>
                @endforelse
            </div>

            <div class="mt-6">
                {{ $collections->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
