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
                                <div class="flex-1 text-right">
                                    <h1 class="text-4xl font-bold">{{ $anime['title'] }}</h1>
                                    @if (!empty($anime['title_english']) && $anime['title_english'] !== $anime['title'])
                                        <h2 class="text-2xl text-white/70 mt-1">{{ $anime['title_english'] }}</h2>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2 text-xs flex-shrink-0 ml-4">
                                    <a href="https://www.imdb.com/title/{{ $anime['imdb_id'] ?? '' }}" target="_blank" class="block">
    <div class="bg-imdb rounded px-2 py-1 text-center min-w-[50px] text-black font-bold text-[15px] tracking-wide">IMDb</div>
    <div class="text-white text-center font-bold text-sm">
        {{ $anime['imdb_score'] ?? 'N/A' }}
    </div>
</a>

                                    <a href="https://myanimelist.net/anime/{{ $anime['mal_id'] ?? '' }}" target="_blank" class="block">
                                        <div class="bg-mal rounded px-2 py-1 text-center min-w-[50px] text-white font-bold text-[15px] uppercase tracking-wide">MAL</div>
                                        <div class="text-white text-center font-bold text-sm">
                                            {{ $anime['score'] ?? 'N/A' }}
                                        </div>
                                    </a>
                                </div>
                            </div>
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
                                        <span class="font-semibold">{{ $anime['type'] ?? 'Unknown' }}</span>
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
                    <h3 class="text-lg font-semibold mb-4 text-right">مُلخَّص القصَّة:</h3>
                    <p class="text-white/90 leading-relaxed text-justify text-sm">
                        {!! $anime['synopsis'] ?? 'لا يوجد ملخص متاح لهذا الأنمي.' !!}
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
                                $validLinks = $batch->batchLinks->filter(fn ($link) =>
                                    $link->url_torrent || $link->url_mega || $link->url_gdrive || $link->url_megaHard || $link->url_gdriveHard
                                );
                            @endphp

                            @if ($validLinks->isNotEmpty())
                                @foreach ($validLinks as $link)
                                    <div class="overflow-hidden shadow-md pt-3">
                                        <span class="font-semibold block text-white text-sm mb-1 px-3">
                                            Episode - {{ $batch->episodes ?? 'غير معروف' }}
                                        </span>
                                        <div class="bg-black text-white text-center py-2 font-semibold text-xs md:text-sm">
                                            {{ $batch->name }} 
                                        </div>
                                        <div class="bg-white flex flex-wrap md:flex-nowrap justify-center items-center px-3 py-2 gap-2">
                                            <div class="flex flex-wrap gap-2">
                                               @if ($link->url_torrent)
    <a href="{{ $link->url_torrent }}" target="_blank" class="bg-white text-black border px-2 py-1 rounded hover:bg-gray-100 text-xs shadow">
        Torrent ({{ $link->resolution }}p)
    </a>
@endif
@if ($link->url_mega)
    <a href="{{ $link->url_mega }}" target="_blank" class="bg-white text-black border px-2 py-1 rounded hover:bg-gray-100 text-xs shadow">
        Mega ({{ $link->resolution }}p)
    </a>
@endif
@if ($link->url_gdrive)
    <a href="{{ $link->url_gdrive }}" target="_blank" class="bg-white text-black border px-2 py-1 rounded hover:bg-gray-100 text-xs shadow">
        GDrive ({{ $link->resolution }}p)
    </a>
@endif
@if ($link->url_megaHard)
    <a href="{{ $link->url_megaHard }}" target="_blank" class="bg-white text-black border px-2 py-1 rounded hover:bg-gray-100 text-xs shadow">
        ({{ $link->resolution }}p) (هاردسب | Hardsub) Mega
    </a>
@endif
@if ($link->url_gdriveHard)
    <a href="{{ $link->url_gdriveHard }}" target="_blank" class="bg-white text-black border px-2 py-1 rounded hover:bg-gray-100 text-xs shadow">
        ({{ $link->resolution }}p) (هاردسب | Hardsub) GDrive
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

            {{-- Comments Section --}}
<div class="mt-10 px-8 md:px-12 lg:px-16 mx-20 lg:mx-20 xl:mx-0 bg-white/20 backdrop-blur-lg p-6 space-y-6 rounded-lg text-white">
    <h2 class="text-2xl font-bold mb-4">💬 التعليقات</h2>

    {{-- Flash Message --}}
    @if(session('success'))
        <div class="bg-green-500 text-white p-2 rounded">{{ session('success') }}</div>
    @endif

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="bg-red-500 text-white p-2 rounded">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Comment Form --}}
    @auth
        <form action="{{ route('comments.store', $animeLink->id) }}" method="POST" class="space-y-4 mt-6">
            @csrf
            <div>
                <label for="body" class="block text-sm">💬 تعليقك:</label>
                <textarea id="body" name="body" class="w-full p-3 rounded bg-white/80 text-black" rows="4" required placeholder="اكتب تعليقك هنا..."></textarea>
            </div>
            <div>
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    أضف تعليق
                </button>
            </div>
        </form>
    @else
        <p class="text-white/70 text-sm mt-2">
            يرجى <a href="{{ route('login') }}" class="underline">تسجيل الدخول</a> لكتابة تعليق.
        </p>
    @endauth

    {{-- Comments List --}}
    @if ($animeLink && $animeLink->comments->isNotEmpty())
        <div class="space-y-6 mt-6">
            @foreach ($animeLink->comments->whereNull('parent_id') as $comment)
                @php
                    $avatar = $comment->user && $comment->user->profile_photo_url
                        ? $comment->user->profile_photo_url
                        : asset('img/default-avatar.png');
                @endphp

                <div class="bg-white/10 p-4 rounded-lg border border-white/20 flex gap-4 items-start">
                    <img src="{{ $avatar }}"
                         alt="{{ $comment->user->name ?? 'User' }}"
                         class="w-10 h-10 rounded-full border-2 border-white shadow-md object-cover mt-1">

                    <div class="flex-1">
                        <div class="flex justify-between items-center mb-1">
                            <span class="font-bold text-sm">{{ $comment->user->name ?? 'مستخدم' }}</span>
                            <span class="text-xs text-white/50">{{ $comment->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="text-white text-sm mb-2">{{ $comment->body }}</div>

                        <div class="flex gap-4 text-xs text-white/70">
                            {{-- Like button --}}
                            <form action="{{ route('comments.like', $comment->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="hover:underline">👍 إعجاب ({{ $comment->likes ?? 0 }})</button>
                            </form>

                            {{-- Reply trigger --}}
                            <button type="button" data-toggle-reply="reply-form-{{ $comment->id }}" class="hover:underline">💬 رد</button>
                        </div>

                        {{-- Replies --}}
                        @if ($comment->replies->isNotEmpty())
                            <div class="mt-4 space-y-2 ml-6 border-l border-white/10 pl-4">
                                @foreach ($comment->replies as $reply)
                                    <div class="bg-white/5 p-3 rounded-md">
                                        <div class="flex justify-between items-center text-xs text-white/60">
                                            <span>{{ $reply->user->name ?? 'مستخدم' }}</span>
                                            <span>{{ $reply->created_at->diffForHumans() }}</span>
                                        </div>
                                        <div class="text-white text-sm mt-1">{{ $reply->body }}</div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        {{-- Reply Form --}}
                        @auth
                            <form id="reply-form-{{ $comment->id }}" action="{{ route('comments.reply', $comment->id) }}" method="POST" class="space-y-2 mt-3 hidden">
                                @csrf
                                <textarea name="body" class="w-full p-2 rounded bg-white/80 text-black text-sm" rows="2" placeholder="رد على هذا التعليق..." required></textarea>
                                <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white text-xs font-bold py-1 px-3 rounded">أرسل الرد</button>
                            </form>
                        @endauth
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-white/70 italic mt-6">لا توجد تعليقات بعد.</p>
    @endif
</div>

{{-- Toggle Reply Form Script --}}
<script>
    document.addEventListener("DOMContentLoaded", () => {
        document.querySelectorAll('[data-toggle-reply]').forEach(button => {
            button.addEventListener('click', () => {
                const targetId = button.getAttribute('data-toggle-reply');
                const form = document.getElementById(targetId);
                if (form) {
                    form.classList.toggle('hidden');
                }
            });
        });
    });
</script>


        </div>
    </div>
</x-app-layout>
