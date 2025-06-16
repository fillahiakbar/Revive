@props([
    'genres' => [],
    'selected' => [],
    'selectedStatus' => null,
    'selectedTypes' => [],
    'selectedSort' => null,
    'query' => ''
])

<div class="space-y-6 text-white pt-24">
    <label class="block text-sm mb-2">الاسم</label>
    {{-- 🟩 Pencarian Nama Anime --}}
<div class="bg-gray-300/90 backdrop-blur-md rounded p-4 backdrop-saturate-150">
    <input type="text" name="q" value="{{ $query }}"
           class="w-full bg-gray-900 text-white rounded px-4 py-2 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-red-500"
           placeholder="Contoh: One Piece">
</div>


    {{-- 🟨 Status --}}
         <label class="block text-sm mb-2">الحالة</label>
    <div class="bg-gray-300/90 backdrop-blur-md rounded p-4 backdrop-saturate-150">
   
        <div class="flex gap-4 flex-wrap">
            @foreach(['airing' => 'مستمر', 'complete' => 'منتهي', 'all' => 'الكل'] as $key => $label)
                <label class="flex items-center gap-2">
                    <input type="radio" name="status" value="{{ $key }}"
                           {{ $selectedStatus === $key ? 'checked' : '' }} class="accent-red-500">
                    <span>{{ $label }}</span>
                </label>
            @endforeach
        </div>
    </div>

    {{-- 🟥 Jenis Format --}}
            <label class="block text-sm mb-2">النوع</label>
    <div class="bg-gray-300/90 backdrop-blur-md rounded p-4 backdrop-saturate-150">

        <div class="flex gap-4 flex-wrap">
            @foreach(['TV', 'Movie', 'OVA', 'ONA', 'Special', 'BD', 'DVD', 'WEB', 'Live Action'] as $type)
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="types[]" value="{{ $type }}"
                           {{ in_array($type, $selectedTypes) ? 'checked' : '' }} class="accent-green-500">
                    <span>{{ $type }}</span>
                </label>
            @endforeach
        </div>
    </div>

    {{-- 🟪 Urutan Sortir --}}
            <label class="block text-sm mb-2">ترتيب حسب</label>
    <div class="bg-gray-300/90 backdrop-blur-md rounded p-4 backdrop-saturate-150">

        <div class="flex gap-6 flex-wrap">
            @foreach([
                'title_asc' => 'A-Z',
                'title_desc' => 'Z-A',
                'episodes' => 'عدد الحلقات',
                'updated' => 'آخر تحديث',
                'recent' => 'أضيفت مؤخرا',
            ] as $key => $label)
                <label class="flex items-center gap-2">
                    <input type="radio" name="sort" value="{{ $key }}"
                           {{ $selectedSort === $key ? 'checked' : '' }} class="accent-pink-500">
                    <span>{{ $label }}</span>
                </label>
            @endforeach
        </div>
    </div>

    {{-- 🟦 Genre Selector --}}
            <label class="block text-sm mb-2">التصنيفات</label>
    <div class="bg-gray-300/90 backdrop-blur-md rounded p-4 backdrop-saturate-150">

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
            @foreach($genres as $genre)
                <label class="flex items-center space-x-2 bg-gray-700 px-4 py-2 rounded hover:bg-gray-600 cursor-pointer">
                    <input type="checkbox" name="genres[]" value="{{ $genre['id'] }}"
                           @if(in_array($genre['id'], $selected)) checked @endif
                           class="accent-red-400">
                    <span>{{ $genre['name'] }}</span>
                </label>
            @endforeach
        </div>
    </div>
</div>
