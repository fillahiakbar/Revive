<x-app-layout>
    <div class="bg-no-repeat min-h-screen pt-20 overflow-x-hidden">
        <div class="max-w-7xl mx-auto px-4 py-20 text-white">
            <div class="flex flex-col lg:flex-row gap-8 items-stretch lg:items-stretch w-full">
                
                @php
                    $reviveScore = number_format($animeLink->average_rating, 1);
                    $reviveRatingCount = $animeLink->ratings()->count();
                    $userRating = auth()->check() ? $animeLink->ratings()->where('user_id', auth()->id())->value('rating') : null;
                @endphp

                {{-- Poster & Rate Panel --}}
                <div class="flex flex-col items-center lg:items-start gap-3 shrink-0">
                    <div class="bg-gray-800 flex items-center justify-center
                                flex-none aspect-[2/3] w-48 md:w-56 lg:w-64
                                overflow-hidden rounded-md shadow-lg">
                        @php
                            $poster = $animeLink->poster 
                                ?? $anime['poster'] 
                                ?? asset('img/placeholder.png');
                        @endphp
                        <img
                        src="{{ $poster }}"
                        alt="{{ $anime['title'] }}"
                        class="w-full h-full object-cover object-center border border-white/10"
                        loading="lazy"
                        decoding="async"
                        sizes="(max-width: 640px) 10rem, (max-width: 768px) 14rem, (max-width: 1024px) 16rem, 18rem"
                        onerror="this.onerror=null;this.src='{{ asset('img/placeholder.png') }}';"
                        />
                    </div>

                    {{-- User Rate Button --}}
                    <div class="w-full bg-white/5 backdrop-blur-sm rounded-lg py-1.5 px-3 text-center cursor-pointer hover:bg-white/10 transition border border-white/10" onclick="openRatingModal()">
                        <div class="flex flex-col items-center gap-0.5">
                            <svg class="w-5 h-5 text-yellow-500 {{ $userRating ? 'fill-current' : 'fill-none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                            </svg>
                            <span class="text-white font-bold tracking-widest text-[11px]" id="userRatingDisplay">
                                {{ $userRating ? $userRating . '/10' : 'RATE' }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Main Info --}}
                <div class="flex-1 flex flex-col">
                    <div class="bg-white/20 backdrop-blur-lg p-6 sm:p-10 mx-0 sm:mx-6 shadow-2xl rounded-lg flex-1 flex flex-col">
                        <div class="mb-8">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1 text-right">
                                    <h1 class="text-3xl sm:text-4xl font-bold" dir="ltr">{{ $anime['title'] }}</h1>
                                    @if (!empty($anime['title_english']) && $anime['title_english'] !== $anime['title'])
                                        <h2 class="text-xl sm:text-2xl text-white/70 mt-1" dir="ltr">{{ $anime['title_english'] }}</h2>
                                    @endif
                                </div>
                                
                                <div class="flex items-center gap-2 text-xs flex-shrink-0 ml-4">
                                    <div class="block cursor-pointer" onclick="openRatingModal()">
                                        <div class="bg-red-600 rounded px-2 py-1 text-center min-w-[50px] text-white font-bold text-[15px] uppercase tracking-wide">Revive</div>
                                        <div class="text-white text-center font-bold text-sm" id="reviveScoreDisplay">
                                            {{ $reviveScore }}
                                        </div>
                                    </div>

                                    <a href="https://www.imdb.com/title/{{ $anime['imdb_id'] ?? '' }}" target="_blank" class="block">
                                        <div class="bg-yellow-500 rounded px-2 py-1 text-center min-w-[50px] text-black font-bold text-[15px] tracking-wide">IMDb</div>
                                        <div class="text-white text-center font-bold text-sm">
                                            {{ number_format($animeLink->imdb_score, 1) }}
                                        </div>
                                    </a>

                                    <a href="https://myanimelist.net/anime/{{ $anime['mal_id'] ?? '' }}" target="_blank" class="block">
                                        <div class="bg-blue-600 rounded px-2 py-1 text-center min-w-[50px] text-white font-bold text-[15px] uppercase tracking-wide">MAL</div>
                                        <div class="text-white text-center font-bold text-sm">
                                            {{ $anime['score'] ?? 'N/A' }}
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>

                        {{-- Info boxes --}}
                        <div class="grid md:grid-cols-2 gap-4 mt-auto pt-4 lg:pt-8">
                            <div class="bg-white/90 text-black p-4 lg:p-6 rounded-md">
                                <div class="grid gap-3 lg:gap-4 text-sm lg:text-base">
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

                            <div class="bg-white/90 text-black p-4 lg:p-6 rounded-md">
                                <div class="grid gap-3 lg:gap-4 text-sm lg:text-base">
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

            {{-- Share Buttons --}}
            @if(\App\Models\Setting::get('share_feature_enabled', '0') === '1')
            @auth
            <div class="mt-6 text-right bg-white/10 p-4 rounded-lg border border-white/20 backdrop-blur-sm" dir="rtl">
                <h3 class="text-lg font-semibold mb-3">شارك الأنمي واحصل على نقاط!</h3>
                <p class="text-sm text-green-300 mb-4">شارك هذا الرابط مع أصدقائك. ستحصل على نقاط وتصعد في قائمة المتصدرين عندما يقومون بزيارته!</p>
                <div class="flex flex-wrap gap-3">
                    <button onclick="openShareModal()" class="bg-green-500 px-4 py-2 rounded text-sm font-bold shadow transition flex items-center gap-2">
                        نسخ الرابط
                    </button>
                </div>
            </div>

            {{-- Share Modal Overlay --}}
            <div id="shareModal" class="fixed inset-0 z-[100] flex items-center justify-center hidden opacity-0 transition-opacity duration-300 pointer-events-none p-4" dir="ltr">
                {{-- Background Overlay --}}
                <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" onclick="closeShareModal()"></div>
                
                {{-- Modal Content --}}
                <div id="shareModalContent" class="z-999 glass-panel max-w-2xl w-full rounded-2xl shadow-2xl overflow-hidden relative text-white transition-all duration-300 transform scale-95 pointer-events-auto" dir="rtl">
                    
                    {{-- Header --}}
                    <div class="p-6 border-b border-green-900/40 flex justify-between items-center bg-gradient-to-r from-black via-green-950/30 to-black">
                        <h2 class="text-2xl font-bold text-green-500 drop-shadow-md">
                            مشاركة الأنمي
                        </h2>
                        <button onclick="closeShareModal()" class="text-gray-400 hover:text-white transition transform hover:rotate-90">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    {{-- Body --}}
                    <div class="p-6 space-y-6">
                        {{-- Label --}}
                        <p class="text-gray-400 text-sm text-right">انسخ الرابط أو شارك عبر منصات التواصل:</p>

                        {{-- Input Group --}}
                        <div class="flex items-center gap-2 bg-black/50 p-3 rounded-lg border border-gray-700 group focus-within:border-green-500 transition">
                            <input type="text" id="shareInput" readonly class="bg-transparent border-none text-white w-full focus:ring-0 font-mono text-sm" dir="ltr" />
                            <button onclick="copyShareLink()" class="p-2 text-gray-400 hover:text-white hover:bg-white/10 rounded-lg transition flex items-center gap-2" title="Copy">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path>
                                </svg>
                                <span class="text-sm font-bold">نسخ</span>
                            </button>
                        </div>

                        {{-- Divider --}}
                        <div class="flex items-center gap-4">
                            <div class="flex-1 h-px bg-white/10"></div>
                            <span class="text-gray-500 text-xs font-medium">أو شارك عبر</span>
                            <div class="flex-1 h-px bg-white/10"></div>
                        </div>

                        {{-- Share Buttons --}}
                        <div class="grid grid-cols-3 gap-4">
                            <button onclick="shareWhatsApp()" class="cursor-pointer group relative p-6 rounded-xl bg-black/60 border border-green-900/30 hover:border-green-600 transition-all duration-300 hover-neon flex flex-col items-center gap-3 text-center">
                                <svg class="w-10 h-10 text-[#25D366] drop-shadow-lg group-hover:scale-110 transition-transform duration-300" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 0C5.385 0 0 5.389 0 12.042c0 2.126.552 4.195 1.6 6.012L.15 24l6.142-1.611c1.764.954 3.738 1.458 5.739 1.458 6.646 0 12.031-5.389 12.031-12.042C24 5.389 18.615 0 12.031 0zm0 21.895c-1.802 0-3.566-.484-5.116-1.404l-.367-.217-3.8.997.997-3.704-.238-.378c-1.006-1.597-1.536-3.447-1.536-5.347 0-5.467 4.45-9.92 9.932-9.92 5.467 0 9.92 4.453 9.92 9.92 0 5.467-4.453 9.92-9.92 9.92zm5.438-7.425c-.298-.15-1.764-.87-2.037-.97-.273-.1-.473-.15-.672.15-.199.299-.77 1.05-.945 1.25-.174.199-.348.224-.646.075-.298-.15-1.258-.464-2.395-1.484-.884-.792-1.482-1.77-1.656-2.07-.174-.299-.019-.462.13-.611.134-.134.298-.348.448-.523.15-.174.199-.299.299-.498.1-.199.05-.373-.025-.523-.075-.15-.672-1.619-.92-2.217-.243-.585-.49-.505-.672-.514-.174-.01-.373-.01-.572-.01-.2 0-.523.075-.797.373-.273.299-1.045 1.02-1.045 2.49 0 1.47 1.07 2.89 1.219 3.09.15.2 2.11 3.22 5.11 4.515 2.16 1.01 3.01 1.09 3.86.91.85-.18 2.395-.98 2.735-1.92.34-1.03.34-1.87.24-2.05-.1-.18-.28-.28-.58-.43z"/></svg>
                                <h3 class="font-bold text-lg group-hover:text-green-400 transition-colors">WhatsApp</h3>
                            </button>
                            <button onclick="shareTelegram()" class="cursor-pointer group relative p-6 rounded-xl bg-black/60 border border-blue-900/30 hover:border-blue-600 transition-all duration-300 hover-neon flex flex-col items-center gap-3 text-center">
                                <svg class="w-10 h-10 text-[#0088cc] drop-shadow-lg group-hover:scale-110 transition-transform duration-300" fill="currentColor" viewBox="0 0 24 24"><path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/></svg>
                                <h3 class="font-bold text-lg group-hover:text-blue-400 transition-colors">Telegram</h3>
                            </button>
                            <button onclick="shareTwitter()" class="cursor-pointer group relative p-6 rounded-xl bg-black/60 border border-gray-700/30 hover:border-gray-500 transition-all duration-300 hover-neon flex flex-col items-center gap-3 text-center">
                                <svg class="w-10 h-10 text-white drop-shadow-lg group-hover:scale-110 transition-transform duration-300" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                                <h3 class="font-bold text-lg group-hover:text-gray-300 transition-colors">X</h3>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Toast Notification --}}
            <div id="copyToast" class="fixed top-8 left-1/2 transform -translate-x-1/2 z-[110] opacity-0 transition-all duration-300 pointer-events-none translate-y-[-20px]">
                <div class="glass-panel px-6 py-3.5 rounded-xl shadow-2xl flex items-center gap-3 border-green-500/30">
                    <div class="bg-green-500 p-1.5 rounded-full">
                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <span class="font-semibold text-sm text-white">تم نسخ الرابط</span>
                </div>
            </div>

            <script>
                function openShareModal() {
                    const modal = document.getElementById('shareModal');
                    const content = document.getElementById('shareModalContent');
                    const input = document.getElementById('shareInput');

                    // Get URL
                    let currentUrl = window.location.href;
                    const refCode = '{{ auth()->user()->ref_code ?? "" }}';
                    if (refCode && !currentUrl.includes('ref=')) {
                        currentUrl += (currentUrl.includes('?') ? '&' : '?') + 'ref=' + refCode;
                    }
                    input.value = currentUrl;
                    
                    // Show modal
                    modal.classList.remove('hidden', 'pointer-events-none');
                    // Force reflow
                    void modal.offsetWidth;
                    
                    // Animate in
                    modal.classList.remove('opacity-0');
                    content.classList.remove('scale-95');
                    content.classList.add('scale-100');
                    
                    // Focus and select text
                    setTimeout(() => {
                        input.focus();
                        input.select();
                    }, 150);
                }

                function closeShareModal() {
                    const modal = document.getElementById('shareModal');
                    const content = document.getElementById('shareModalContent');
                    
                    // Animate out
                    modal.classList.add('opacity-0', 'pointer-events-none');
                    content.classList.remove('scale-100');
                    content.classList.add('scale-95');
                    
                    setTimeout(() => {
                        modal.classList.add('hidden');
                    }, 300);
                }

                function copyShareLink() {
                    const input = document.getElementById('shareInput');
                    
                    navigator.clipboard.writeText(input.value).then(() => {
                        const toast = document.getElementById('copyToast');
                        
                        toast.classList.remove('opacity-0', 'translate-y-[-20px]');
                        toast.classList.add('translate-y-0');
                        
                        setTimeout(() => {
                            toast.classList.add('opacity-0', 'translate-y-[-20px]');
                            toast.classList.remove('translate-y-0');
                        }, 2500);
                    });
                }

                function getShareText() {
                    return encodeURIComponent('شاهد {{ addslashes($anime["title"] ?? "") }} على REVIVE!');
                }

                function shareWhatsApp() {
                    const url = document.getElementById('shareInput').value;
                    const text = getShareText();
                    window.open(`https://api.whatsapp.com/send?text=${text} ${encodeURIComponent(url)}`, '_blank');
                }

                function shareTelegram() {
                    const url = document.getElementById('shareInput').value;
                    const text = getShareText();
                    window.open(`https://t.me/share/url?url=${encodeURIComponent(url)}&text=${text}`, '_blank');
                }

                function shareTwitter() {
                    const url = document.getElementById('shareInput').value;
                    const text = getShareText();
                    window.open(`https://twitter.com/intent/tweet?url=${encodeURIComponent(url)}&text=${text}`, '_blank');
                }

                // Keyboard events
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape') {
                        const modal = document.getElementById('shareModal');
                        if (modal && !modal.classList.contains('hidden')) {
                            closeShareModal();
                        }
                    }
                });
            </script>
            @endauth
            @endif

            {{-- Synopsis (RTL) --}}
            <div class="mt-8" dir="rtl">
                <div class="bg-white/30 backdrop-blur-lg p-6 border border-white/20 rounded-lg">
                    <h3 class="text-lg font-semibold mb-4 text-right">مُلخَّص القصَّة:</h3>
                    <p class="text-white/90 leading-relaxed text-justify text-sm">
                        {!! $anime['synopsis'] ?? 'لا يوجد ملخص متاح لهذا الأنمي.' !!}
                    </p>
                </div>
            </div>

           
            {{-- Subtitle & PixelDrain --}}
            @if(isset($animeLink) && ($animeLink->subtitle_url || $animeLink->subtitle_url_pixeldrain))
            <div class="mt-6 flex justify-start gap-3" dir="ltr">
                @if($animeLink->subtitle_url)
                <a href="{{ $animeLink->subtitle_url }}" target="_blank"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm text-white hover:brightness-125 transition-colors"
                   style="background: rgba(255, 255, 255, 0.358); border: 1px solid rgba(255,255,255,0.1);">
                    <span>ملفات الترجمة</span>
                </a>
                @endif

                @if($animeLink->subtitle_url_pixeldrain)
                <a href="{{ $animeLink->subtitle_url_pixeldrain }}" target="_blank"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm text-white hover:brightness-125 transition-colors"
                   style="background: rgba(255, 255, 255, 0.358); border: 1px solid rgba(255,255,255,0.1);">
                    <span>مشاهدة مباشرة</span>
                </a>
                @endif
            </div>
            @endif

            {{-- Episode Cards Section --}}
            @if ($animeLink && $animeLink->batches->isNotEmpty())
                @php
                    // Determine the latest batch(es) for NEW badge — top 2 most recent
                    $sortedBatches = $animeLink->batches->sortByDesc('created_at');
                    $latestBatchIds = $sortedBatches->take(2)->pluck('id')->toArray();
                @endphp

                <style>
                    .ep-card-grid {
                        display: grid;
                        grid-template-columns: repeat(2, 1fr);
                        gap: 1rem;
                    }
                    @media (max-width: 768px) {
                        .ep-card-grid {
                            grid-template-columns: 1fr;
                        }
                    }
                    .ep-card {
                        position: relative;
                        border-radius: 14px;
                        padding: 1.25rem 1.5rem;
                        background: rgba(255, 255, 255, 0.04);
                        border: 1px solid rgba(255, 255, 255, 0.07);
                        backdrop-filter: blur(16px);
                        -webkit-backdrop-filter: blur(16px);
                        overflow: visible;
                    }
                    .ep-card__header {
                        display: flex;
                        align-items: center;
                        justify-content: space-between;
                        gap: 0.5rem;
                        margin-bottom: 1rem;
                    }
                    .ep-card__ep-num {
                        font-size: 0.95rem;
                        font-weight: 700;
                        color: #ffffff;
                        letter-spacing: 0.02em;
                        direction: rtl;
                    }
                    .ep-card__badge-new {
                        display: inline-flex;
                        align-items: center;
                        padding: 2px 8px;
                        border-radius: 4px;
                        font-size: 0.65rem;
                        font-weight: 800;
                        letter-spacing: 0.08em;
                        text-transform: uppercase;
                        color: #ff0000;
                        background: rgba(185, 4, 4, 0.486);
                        margin-left: 0.5rem;
                        flex-shrink: 0;
                    }
                    .ep-card__date {
                        font-size: 0.78rem;
                        color: rgba(255, 255, 255, 0.45);
                        direction: rtl;
                        text-align: right;
                    }
                    .ep-card__title {
                        font-size: 0.95rem;
                        font-weight: 600;
                        color: rgba(255, 255, 255, 0.92);
                        direction: ltr;
                        text-align: right;
                        line-height: 1.4;
                        overflow: hidden;
                        text-overflow: ellipsis;
                        white-space: nowrap;
                        flex: 1;
                        min-width: 0;
                    }
                    .ep-card__meta-row {
                        display: flex;
                        align-items: center;
                        justify-content: space-between;
                        gap: 0.5rem;
                        flex-wrap: wrap;
                    }
                    .ep-card__pills {
                        display: flex;
                        align-items: center;
                        gap: 0.4rem;
                        flex-wrap: wrap;
                    }
                    .ep-card__pill {
                        display: inline-flex;
                        align-items: center;
                        padding: 3px 10px;
                        border-radius: 6px;
                        font-size: 0.68rem;
                        font-weight: 600;
                        letter-spacing: 0.04em;
                        color: rgba(255, 255, 255, 0.7);
                        background: rgba(255, 255, 255, 0.07);
                        border: 1px solid rgba(255, 255, 255, 0.08);
                    }
                    .ep-card__actions {
                        display: flex;
                        align-items: center;
                        gap: 0.4rem;
                        flex-shrink: 0;
                    }
                    .ep-card__dl-btn {
                        display: flex;
                        align-items: center;
                        gap: 6px;
                        padding: 6px 14px;
                        border-radius: 8px;
                        font-size: 0.78rem;
                        font-weight: 600;
                        color: #fff;
                        background: rgba(255, 255, 255, 0.1);
                        border: 1px solid rgba(255, 255, 255, 0.2);
                        cursor: pointer;
                    }
                    .ep-card__dropdown {
                        position: absolute;
                        z-index: 60;
                        margin-top: 0.5rem;
                        min-width: 200px;
                        border-radius: 12px;
                        overflow: hidden;
                        background: rgba(30, 30, 30, 0.97);
                        border: 1px solid rgba(255, 255, 255, 0.1);
                        box-shadow: 0 12px 32px rgba(0, 0, 0, 0.5);
                        backdrop-filter: blur(20px);
                    }
                    .ep-card__dropdown a {
                        display: flex;
                        align-items: center;
                        gap: 10px;
                        padding: 10px 16px;
                        font-size: 0.82rem;
                        color: rgba(255, 255, 255, 0.8);
                        transition: background 0.15s ease;
                        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
                    }
                    .ep-card__dropdown a:last-child {
                        border-bottom: none;
                    }
                    .ep-card__dropdown a:hover {
                        background: rgba(255, 255, 255, 0.1);
                        color: #fff;
                    }
                    .ep-card__dropdown a svg {
                        width: 16px;
                        height: 16px;
                        opacity: 0.5;
                        flex-shrink: 0;
                    }
                </style>

                <style>
                    /* Custom scrollbar for the new container */
                    .ep-list-container::-webkit-scrollbar {
                        width: 8px;
                    }
                    .ep-list-container::-webkit-scrollbar-track {
                        background: rgba(0, 0, 0, 0.1);
                        border-radius: 8px;
                    }
                    .ep-list-container::-webkit-scrollbar-thumb {
                        background: rgba(255, 255, 255, 0.3);
                        border-radius: 8px;
                    }
                    .ep-list-container::-webkit-scrollbar-thumb:hover {
                        background: rgba(255, 255, 255, 0.5);
                    }
                </style>

               

                <div class="ep-list-container mt-10 bg-white/20 backdrop-blur-lg p-6 rounded-lg overflow-y-auto" style="max-height: 380px;">
                    <div class="text-2xl font-semibold mb-4">قائمة الحلقات </div>
                    <div class="ep-card-grid" id="episode-grid">
                        @foreach ($animeLink->batches as $batch)
                        @php
                            $allLinks = $batch->batchLinks->filter(fn ($link) =>
                                $link->url_torrent || $link->url_rr_torrent || $link->url_mega || $link->url_gdrive || $link->url_megaHard || $link->url_gdriveHard || $link->url_pixeldrain
                            );

                            $groupedLinks = $allLinks->groupBy(function ($link) {
                                $codec = (string)$link->codec;
                                if (empty($codec) || stripos($codec, '264') !== false || stripos($codec, 'avc') !== false || stripos($codec, 'h.264') !== false) {
                                    return 'H.264';
                                }
                                if (stripos($codec, '265') !== false || stripos($codec, 'hevc') !== false) {
                                    return 'HEVC';
                                }
                                return $codec;
                            });

                            $maxRes = $allLinks->max('resolution');
                            $episodeTitle = $anime['title'] . ' - ' . ($batch->episodes ?? '??');
                            if ($maxRes) $episodeTitle .= ' [' . $maxRes . 'p]';

                            $isNew = in_array($batch->id, $latestBatchIds);

                            // Collect unique codecs for pills
                            $codecs = $allLinks->pluck('codec')->filter()->unique()->values();

                            // Determine content label based on anime type
                            $animeTypes = $animeLink->types->pluck('name')->toArray();
                            $isMovie = in_array('Movie', $animeTypes);
                            $isOVA = in_array('OVA', $animeTypes);
                            $isSeries = in_array('TV', $animeTypes) || in_array('WEB', $animeTypes);

                            if ($isMovie) {
                                $contentLabel = 'فيلم'; // Movie
                            } elseif ($isOVA) {
                                $contentLabel = 'OVA';
                            } else {
                                $contentLabel = 'الحلقة ' . ($batch->episodes ?? '??'); // Episode + number
                            }
                        @endphp

                        @if ($allLinks->isNotEmpty())
                            <div x-data="{ openDropdown: null }"
                                 class="ep-card"
                                 :class="{ 'z-50': openDropdown !== null, 'z-10': openDropdown === null }">

                                {{-- Header: Episode Number/Badge + Episode Title --}}
                                <div class="flex flex-col items-start gap-1.5 mb-3 w-full">
                                    {{-- Episode Number + NEW Badge --}}
                                    <div class="flex items-center gap-2">
                                        <span class="ep-card__ep-num">{{ $contentLabel }}</span>
                                    </div>
                                    
                                    {{-- Episode Title --}}
                                    <div class="ep-card__title w-full mt-2" title="{{ $episodeTitle }}">
                                        @if ($isNew)
                                            <span class="ep-card__badge-new">NEW</span>
                                        @endif
                                        {{ $episodeTitle }}
                                    </div>
                                </div>

                                {{-- Bottom row: Download Buttons + Date --}}
                                <div class="ep-card__meta-row">

                                    {{-- Download Buttons --}}
                                    <div class="ep-card__actions">
                                        @foreach ($groupedLinks as $codecName => $links)
                                            <div class="relative">
                                                @php $safeCodec = \Illuminate\Support\Str::slug($codecName); @endphp
                                                <button @click="openDropdown = (openDropdown === '{{ $safeCodec }}' ? null : '{{ $safeCodec }}')"
                                                        @click.outside="if(openDropdown === '{{ $safeCodec }}') openDropdown = null"
                                                        class="ep-card__dl-btn">
                                                    <svg class="w-3.5 h-3.5 opacity-70" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v12m0 0l-4-4m4 4l4-4M4 20h16"/>
                                                    </svg>
                                                    <span>{{ $codecName }}</span>
                                                    <svg class="w-3 h-3 opacity-50 transition-transform duration-200" :class="{ 'rotate-180': openDropdown === '{{ $safeCodec }}' }" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                    </svg>
                                                </button>

                                                {{-- Dropdown --}}
                                                <div x-show="openDropdown === '{{ $safeCodec }}'" x-cloak
                                                     x-transition:enter="transition ease-out duration-150"
                                                     x-transition:enter-start="opacity-0 translate-y-1"
                                                     x-transition:enter-end="opacity-100 translate-y-0"
                                                     x-transition:leave="transition ease-in duration-100"
                                                     x-transition:leave-start="opacity-100 translate-y-0"
                                                     x-transition:leave-end="opacity-0 translate-y-1"
                                                     class="ep-card__dropdown right-0">
                                                    @foreach ($links as $link)
                                                        @if ($link->url_mega)
                                                            <a href="{{ $link->url_mega }}" target="_blank">
                                                                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M19.35 10.04C18.67 6.59 15.64 4 12 4 9.11 4 6.6 5.64 5.35 8.04 2.34 8.36 0 10.91 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96zM17 13l-5 5-5-5h3V9h4v4h3z"/></svg>
                                                                <span>Mega</span>
                                                            </a>
                                                        @endif
                                                        @if ($link->url_megaHard)
                                                            <a href="{{ $link->url_megaHard }}" target="_blank">
                                                                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M19.35 10.04C18.67 6.59 15.64 4 12 4 9.11 4 6.6 5.64 5.35 8.04 2.34 8.36 0 10.91 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96zM17 13l-5 5-5-5h3V9h4v4h3z"/></svg>
                                                                <span>Mega Hardsub</span>
                                                            </a>
                                                        @endif
                                                        @if ($link->url_gdrive)
                                                            <a href="{{ $link->url_gdrive }}" target="_blank">
                                                                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M19.35 10.04C18.67 6.59 15.64 4 12 4 9.11 4 6.6 5.64 5.35 8.04 2.34 8.36 0 10.91 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96zM17 13l-5 5-5-5h3V9h4v4h3z"/></svg>
                                                                <span>GDrive</span>
                                                            </a>
                                                        @endif
                                                        @if ($link->url_gdriveHard)
                                                            <a href="{{ $link->url_gdriveHard }}" target="_blank">
                                                                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M19.35 10.04C18.67 6.59 15.64 4 12 4 9.11 4 6.6 5.64 5.35 8.04 2.34 8.36 0 10.91 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96zM17 13l-5 5-5-5h3V9h4v4h3z"/></svg>
                                                                <span>GDrive Hardsub</span>
                                                            </a>
                                                        @endif
                                                        @if ($link->url_pixeldrain)
                                                            <a href="{{ $link->url_pixeldrain }}" target="_blank">
                                                                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M19.35 10.04C18.67 6.59 15.64 4 12 4 9.11 4 6.6 5.64 5.35 8.04 2.34 8.36 0 10.91 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96zM17 13l-5 5-5-5h3V9h4v4h3z"/></svg>
                                                                <span>PixelDrain</span>
                                                            </a>
                                                        @endif
                                                        @if ($link->url_torrent)
                                                            <a href="{{ $link->url_torrent }}" target="_blank">
                                                                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 4V1L8 5l4 4V6c3.31 0 6 2.69 6 6 0 1.01-.25 1.97-.7 2.8l1.46 1.46C19.54 15.03 20 13.57 20 12c0-4.42-3.58-8-8-8zm0 14c-3.31 0-6-2.69-6-6 0-1.01.25-1.97.7-2.8L5.24 7.74C4.46 8.97 4 10.43 4 12c0 4.42 3.58 8 8 8v3l4-4-4-4v3z"/></svg>
                                                                <span>Torrent</span>
                                                            </a>
                                                        @endif
                                                        @if ($link->url_rr_torrent)
                                                            <a href="{{ \Illuminate\Support\Facades\URL::signedRoute('torrent.download', ['filename' => $link->url_rr_torrent], now()->addHours(2)) }}">
                                                                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 4V1L8 5l4 4V6c3.31 0 6 2.69 6 6 0 1.01-.25 1.97-.7 2.8l1.46 1.46C19.54 15.03 20 13.57 20 12c0-4.42-3.58-8-8-8zm0 14c-3.31 0-6-2.69-6-6 0-1.01.25-1.97.7-2.8L5.24 7.74C4.46 8.97 4 10.43 4 12c0 4.42 3.58 8 8 8v3l4-4-4-4v3z"/></svg>
                                                                <span>RR Torrent</span>
                                                            </a>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    {{-- Date --}}
                                    <div class="ep-card__date">
                                        {{ \Carbon\Carbon::parse($batch->created_at ?? now())->locale('ar')->translatedFormat('j F Y') }}
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                    </div>
                </div>
            @endif

            {{-- Related Anime --}}
           @if($animeLink && $animeLink->relatedGroup && $animeLink->relatedGroup->animeLinks->count() > 1)
    <div class="mt-16">
        <h2 class="text-xl font-bold mb-4 text-white">أعمال ذات صلة</h2>
        <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @foreach($animeLink->relatedGroup->animeLinks->where('id', '!=', $animeLink->id) as $related)
                <a href="{{ route('anime.show', $related->mal_id) }}"
                    class="relative text-white rounded-lg overflow-hidden shadow hover:shadow-lg transition group block h-full">

                    {{-- Badge (Types) --}}
                    <div class="flex flex-wrap gap-1 mt-2 px-2 absolute top-0 z-10 left-0 right-0 pointer-events-none">
                        @foreach ($related->types ?? [] as $type)
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded shadow-sm"
                                style="background-color: {{ $type->color ?? '#6b7280' }}; color: white;">
                                {{ $type->name }}
                            </span>
                        @endforeach
                    </div>

                    {{-- Poster --}}
                    <div class="w-full bg-gray-800 flex items-center justify-center aspect-[2/3] relative overflow-hidden">
                        @if ($related->poster)
                            <img src="{{ $related->poster }}" alt="{{ $related->title }}"
                                class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
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

                        {{-- Relation Title Overlay --}}
                        @if (!empty($related->relation_title))
                            <div class="absolute inset-x-0 bottom-0 z-10 text-center px-2 pt-8 pb-3"
                                style="background: linear-gradient(to top, rgba(0, 0, 0, 0.9) 0%, rgba(0, 0, 0, 0.6) 40%, rgba(0, 0, 0, 0) 100%);">
                                <span class="text-xs font-bold text-white" style="text-shadow: 0 2px 4px rgba(0,0,0,0.5);">
                                    {{ $related->relation_title }}
                                </span>
                            </div>
                        @endif
                    </div>

                    {{-- Details --}}
                    <div class="p-1 text-[11px] text-center">
                        <h3 class="font-bold truncate" dir="ltr" title="{{ $related->title }}">{{ $related->title }}</h3>
                        @if (!empty($related->title_english) && $related->title_english !== $related->title)
                            <p class="text-gray-400 truncate" title="{{ $related->title_english }}">
                                {{ $related->title_english }}
                            </p>
                        @endif
                    </div>
               </a>
            @endforeach
        </div>
    </div>
@endif

            {{-- Comments Section --}}
            <div class="mt-10 px-4 md:px-8 lg:px-16 mx-0 sm:mx-4 lg:mx-20 xl:mx-0 bg-white/20 backdrop-blur-lg p-6 space-y-6 rounded-lg text-white">
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
                                        <span class="text-xs text-white/50">{{ $comment->created_at->locale('ar')->diffForHumans() }}</span>
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
                                                        <span>{{ $reply->created_at->locale('ar')->diffForHumans() }}</span>
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

            </div>

            {{-- Rating Modal Overlay (Alpine.js) --}}
            <div x-data="{
                rateOpen: false,
                currentRating: {{ (int)($userRating ?? 0) }},
                hoverRating: 0,
                isSubmitting: false,
                
                openModal() {
                    @if(!auth()->check())
                        window.location.href = '{{ route('login') }}';
                        return;
                    @endif
                    this.rateOpen = true;
                },
                
                closeModal() {
                    this.rateOpen = false;
                    this.hoverRating = 0;
                },
                
                setHover(val) {
                    this.hoverRating = val;
                },
                
                clearHover() {
                    this.hoverRating = 0;
                },
                
                setRating(val) {
                    this.currentRating = val;
                },
                
                get displayRating() {
                    return this.hoverRating || this.currentRating || '?';
                },

                async submitRating() {
                    if (!this.currentRating) return;
                    this.isSubmitting = true;
                    try {
                        const response = await fetch('{{ route('anime.rate', $anime['mal_id']) }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ rating: this.currentRating })
                        });
                        const data = await response.json();
                        if (data.success) {
                            const reviveEl = document.getElementById('reviveScoreDisplay');
                            if(reviveEl) reviveEl.innerText = data.average;
                            
                            const userRateEl = document.getElementById('userRatingDisplay');
                            if(userRateEl) userRateEl.innerText = this.currentRating + '/10';
                            
                            // Visual indication
                            const btn = this.$refs.rateBtn;
                            const oldText = btn.innerText;
                            btn.innerText = 'Success!';
                            btn.style.backgroundColor = '#22c55e';
                            btn.style.color = '#fff';
                            
                            setTimeout(() => {
                                this.closeModal();
                                setTimeout(() => {
                                    btn.innerText = 'Rate';
                                    btn.style.backgroundColor = '';
                                    btn.style.color = '';
                                }, 300);
                            }, 800);
                        } else {
                            alert(data.message || 'Error submitting rating');
                        }
                    } catch (error) {
                        alert('Something went wrong. Make sure you are logged in.');
                    }
                    this.isSubmitting = false;
                }
            }" 
            @open-rating-modal.window="openModal()"
            @keydown.escape.window="closeModal()"
            x-show="rateOpen" 
            class="fixed inset-0 z-[120] flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm" 
            style="display: none;"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            dir="ltr">
                
                {{-- Modal Box --}}
                <div @click.away="closeModal()" class="relative w-full max-w-md bg-[#1f1f1f] rounded-lg shadow-[0_20px_50px_rgba(0,0,0,0.8)] px-8 pb-8 pt-4 flex flex-col items-center mt-12 mb-auto md:my-auto"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform scale-90 translate-y-8"
                    x-transition:enter-end="opacity-100 transform scale-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 transform scale-100 translate-y-0"
                    x-transition:leave-end="opacity-0 transform scale-90 translate-y-8">
                    
                    {{-- Close Button --}}
                    <button @click="closeModal()" class="absolute top-4 right-4 text-gray-400 hover:text-white transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>

                    {{-- Giant Floating Star --}}
                    <div class="relative w-32 h-32 -mt-20 mb-4 flex items-center justify-center">
                        <svg class="w-full h-full drop-shadow-[0_8px_30px_rgba(77,159,235,0.5)]" viewBox="0 0 24 24" fill="#4d9feb" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                        <span class="absolute text-4xl font-bold text-white" style="text-shadow: 0 2px 8px rgba(0,0,0,0.5);" x-text="displayRating"></span>
                    </div>

                    <div class="text-center w-full mb-6">
                        <p class="text-[#fac916] font-bold uppercase tracking-[0.2em] text-xs mb-2">Rate This</p>
                        <h3 class="text-white text-2xl font-semibold truncate px-4" dir="ltr">{{ $anime['title'] }}</h3>
                    </div>

                    {{-- 10 Stars row --}}
                    <div class="flex gap-1 sm:gap-2 justify-center w-full mb-8" @mouseleave="clearHover()">
                        <template x-for="star in 10">
                            <button @mouseenter="setHover(star)" @click="setRating(star)" class="focus:outline-none transition-transform duration-150 hover:scale-125">
                                <svg class="w-8 h-8 sm:w-9 sm:h-9 transition-all duration-200" 
                                    :fill="(hoverRating ? star <= hoverRating : star <= currentRating) ? '#4d9feb' : '#3a3a3a'"
                                    :style="(hoverRating ? star <= hoverRating : star <= currentRating) ? 'filter: drop-shadow(0 0 6px rgba(77,159,235,0.4))' : ''"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                            </button>
                        </template>
                    </div>

                    {{-- Submit Button --}}
                    <button x-ref="rateBtn"
                            @click="submitRating()" 
                            :disabled="!currentRating || isSubmitting"
                            class="w-full py-3 rounded text-lg font-bold transition-all duration-300"
                            :style="currentRating ? 'background-color: #fac916; color: #000; box-shadow: 0 10px 15px -3px rgba(234,179,8,0.2);' : 'background-color: #333; color: #6b7280; cursor: not-allowed;'">
                        <span x-show="!isSubmitting">Rate</span>
                        <span x-show="isSubmitting"><i class="fas fa-circle-notch fa-spin"></i></span>
                    </button>
                </div>
            </div>

            <script>
                function openRatingModal() {
                    window.dispatchEvent(new CustomEvent('open-rating-modal'));
                }
            </script>

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

            {{-- Referral Tracking Script --}}
            <script>
                document.addEventListener("DOMContentLoaded", () => {
                    const urlParams = new URLSearchParams(window.location.search);
                    const refCode = urlParams.get('ref');
                    
                    if (refCode) {
                        setTimeout(() => {
                            fetch('/api/referral/track', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    ref: refCode,
                                    anime_id: {{ $anime['mal_id'] ?? 0 }}
                                })
                            }).then(res => res.json())
                              .then(data => console.log('Referral tracked'))
                              .catch(err => console.error(err));
                        }, 8000); // 8 seconds delay
                    }
                });
            </script>

        </div>
    </div>
</x-app-layout>
