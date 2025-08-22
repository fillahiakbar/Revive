@props([
    'genres' => [],
    'selected' => [],
    'selectedStatus' => null,
    'selectedTypes' => [],
    'selectedSort' => null,
    'types' => [],
    'query' => ''
])

<div class="space-y-6 text-white pt-24">
    {{-- 🔍 Pencarian Nama Anime --}}
    <label class="block text-sm mb-2">الاسم</label>
    <div class="bg-white/5 backdrop-blur-md rounded p-4 backdrop-saturate-150">
        <input type="text" name="q" value="{{ $query }}"
            class="w-full bg-white/20 text-white rounded px-4 py-2 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-red-500"
            placeholder="مثال: Mowgli">
    </div>

    {{-- 🔃 Sort --}}
    <label class="block text-sm mb-2">ترتيب حسب</label>
    <div class="bg-white/5 backdrop-blur-md rounded p-4 backdrop-saturate-150">
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

{{-- 🎬 Jenis Format --}}
<label class="block text-sm mb-2">فلتر البحث</label>
<div class="bg-white/5 backdrop-blur-md rounded p-4 backdrop-saturate-150">
    <div class="flex gap-4 flex-wrap">
        @foreach ($types as $type)
            <label class="inline-flex items-center gap-2">
                <input type="checkbox"
                       name="types[]"
                       value="{{ $type['name'] }}"
                       {{ in_array($type['name'], $selectedTypes ?? []) ? 'checked' : '' }}
                       class="form-checkbox">
                <span>{{ $type['name'] }}</span>
            </label>
        @endforeach
    </div>
</div>



    {{-- 🏷️ Genre Selector --}}
    <label class="block text-sm mb-2">التصنيفات</label>
    <div class="bg-white/5 backdrop-blur-md rounded p-4 backdrop-saturate-150">
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
            @foreach($genres as $genre)
                <label class="inline-flex items-center gap-2">
                    <input type="checkbox" name="genres[]" value="{{ $genre['id'] }}"
                        {{ in_array($genre['id'], $selected) ? 'checked' : '' }} class="accent-blue-500">
                    <span>{{ $genre['name'] }}</span>
                </label>
            @endforeach
        </div>
    </div>
</div>
