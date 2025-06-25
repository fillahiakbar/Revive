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
                                <h1 class="text-4xl font-bold text-right flex-1">{{ $anime['title'] }}</h1>
                                <div class="flex items-center gap-2 text-xs flex-shrink-0 ml-4">
                                    <div>
                                        <div class="bg-blue-600 border border-blue-400 px-2 py-1 text-center min-w-[50px] text-white font-bold text-[10px] uppercase tracking-wide">MAL</div>
                                        <div class="text-white text-center font-bold text-sm">{{ $anime['score'] ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </div>

                            @if(isset($anime['title_english']))
                                <p class="text-white/80 mb-4 text-right">{{ $anime['title_english'] }}</p>
                            @endif
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
                                        <span class="font-semibold">{{ $anime['types'][0] ?? 'Unknown' }}</span>
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
                        {{ $anime['synopsis'] ?? 'لا يوجد ملخص متاح لهذا الأنمي.' }}
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
                                $validLinks = $batch->batchLinks->filter(function ($link) {
                                    return $link->url_torrent || $link->url_mega || $link->url_gdrive;
                                });
                            @endphp

                            @if ($validLinks->isNotEmpty())
                                @foreach ($validLinks as $link)
                                Episodes {{ $batch->episodes }}
                                    <div class="overflow-hidden shadow-md pt-3">
                                        <div class="bg-black text-white text-center py-2 font-semibold text-xs md:text-sm">
                                            {{ $batch->name }} 
                                            <!-- - Episodes {{ $batch->episodes }} -->
                                        </div>
                                        <div class="bg-white flex flex-wrap md:flex-nowrap justify-center items-center px-3 py-2 gap-2">
                                            <div class="flex flex-wrap gap-2">
                                                @if ($link->url_torrent)
                                                    <a href="{{ $link->url_torrent }}" class="bg-white text-black border px-2 py-1 rounded hover:bg-gray-100 text-xs shadow">
                                                        Torrent ({{ $link->resolution }}p)
                                                    </a>
                                                @endif
                                                @if ($link->url_mega)
                                                    <a href="{{ $link->url_mega }}" class="bg-white text-black border px-2 py-1 rounded hover:bg-gray-100 text-xs shadow">
                                                        Mega ({{ $link->resolution }}p)
                                                    </a>
                                                @endif
                                                @if ($link->url_gdrive)
                                                    <a href="{{ $link->url_gdrive }}" class="bg-white text-black border px-2 py-1 rounded hover:bg-gray-100 text-xs shadow">
                                                        GDrive ({{ $link->resolution }}p)
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

                {{-- Flash message --}}
                @if(session('success'))
                    <div class="bg-green-500 text-white p-2 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Error messages --}}
                @if ($errors->any())
                    <div class="bg-red-500 text-white p-2 rounded">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Add Comment Form --}}
                @auth
                    <form action="{{ route('comments.store', $animeLink->id) }}" method="POST" class="space-y-4 mt-6">
                        @csrf
                        <div>
                            <label for="body" class="block text-sm">💬 تعليقك:</label>
                            <textarea id="body" name="body" class="w-full p-3 rounded bg-white/80 text-black" rows="4" required placeholder="اكتب تعليقك هنا…"></textarea>
                        </div>
                        <div>
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                أضف تعليق
                            </button>
                        </div>
                    </form>
                @else
                    <p class="text-white/70 text-sm mt-2">يرجى <a href="{{ route('login') }}" class="underline">تسجيل الدخول</a> لكتابة تعليق.</p>
                @endauth

                {{-- Comment List --}}
                @if ($animeLink && $animeLink->comments->isNotEmpty())
                    <div class="space-y-6 mt-6">
                        @foreach ($animeLink->comments as $comment)
                            <div class="bg-white/10 p-4 rounded-lg border border-white/20 flex gap-4 items-start">
                                {{-- Avatar --}}
                                <img src="{{ $comment->user->profile_photo_url ?? asset('img/default-avatar.png') }}" 
                                     class="w-10 h-10 rounded-full object-cover mt-1" alt="avatar">

                                <div class="flex-1">
                                    {{-- Username + Timestamp --}}
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="font-bold text-sm">{{ $comment->user->name }}</span>
                                        <span class="text-xs text-white/50">{{ $comment->created_at->diffForHumans() }}</span>
                                    </div>

                                    {{-- Isi Komentar --}}
                                    <div class="text-white text-sm">
                                        {{ $comment->body }}
                                    </div>

                                    {{-- Aksi --}}
                                    <div class="mt-2 flex gap-4 text-xs text-white/70">
                                        <button class="hover:underline">👍 إعجاب</button>
                                        <button class="hover:underline">💬 رد</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-white/70 italic mt-6">لا توجد تعليقات بعد.</p>
                @endif

            </div>

        </div>
    </div>
</x-app-layout>
