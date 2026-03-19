<x-app-layout>
    <style>
        :root {
            --primary-color: #dc2626;
            --primary-glow: rgba(220, 38, 38, 0.5);
            --primary-glow-subtle: rgba(220, 38, 38, 0.15);
            --accent-color: #16a34a;
            --accent-glow: rgba(22, 163, 74, 0.5);
            --bg-dark-glass: rgba(10, 10, 10, 0.75);
            --border-glass: rgba(255, 255, 255, 0.08);
        }

        .leaderboard-wrapper {
            min-height: 100vh;
            color: #ffffff;
            font-family: 'Inter', system-ui, sans-serif;
        }

        .text-glow-red {
            text-shadow: 0 0 25px var(--primary-glow);
        }

        .text-glow-green {
            text-shadow: 0 0 25px var(--accent-glow);
        }

        .pulse-glow {
            animation: pulse-glow-anim 2.5s infinite alternate ease-in-out;
        }

        @keyframes pulse-glow-anim {
            0% {
                box-shadow: 0 0 15px var(--primary-glow-subtle);
            }

            100% {
                box-shadow: 0 0 40px var(--primary-glow);
            }
        }

        .lb-glass {
            background: var(--bg-dark-glass);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--border-glass);
        }

        .podium-card {
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border-top: 2px solid transparent;
        }

        .podium-card:hover {
            transform: translateY(-12px);
            border-top-color: var(--primary-color);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.6);
        }

        .gradient-border-1 {
            background: linear-gradient(var(--bg-dark-glass), var(--bg-dark-glass)) padding-box,
                linear-gradient(180deg, var(--primary-color), transparent) border-box;
            border: 1px solid transparent;
        }

        .gradient-border-2 {
            background: linear-gradient(var(--bg-dark-glass), var(--bg-dark-glass)) padding-box,
                linear-gradient(180deg, #94a3b8, transparent) border-box;
            border: 1px solid transparent;
        }

        .gradient-border-3 {
            background: linear-gradient(var(--bg-dark-glass), var(--bg-dark-glass)) padding-box,
                linear-gradient(180deg, #b45309, transparent) border-box;
            border: 1px solid transparent;
        }

        .table-row-hover {
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
        }

        .table-row-hover:hover {
            background-color: rgba(220, 38, 38, 0.06);
            border-left-color: var(--primary-color);
            transform: scale(1.005);
        }

        .digital-font {
            font-family: 'Courier New', Courier, monospace;
            letter-spacing: 0.05em;
        }

        .countdown-digit {
            background: rgba(220, 38, 38, 0.15);
            border: 1px solid rgba(220, 38, 38, 0.3);
            border-radius: 8px;
            padding: 6px 12px;
        }
    </style>

    <div class="leaderboard-wrapper pt-16 pb-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto">

            {{-- Header Section --}}
            <div class="text-center mb-12">
                <h1 class="mt-16 text-5xl md:text-6xl font-black mb-4 tracking-tighter">
                    <span class="text-glow-red" style="color: var(--primary-color);">Leaderboard</span>
                </h1>
                <p class="text-gray-400 text-lg md:text-xl max-w-2xl mx-auto font-light" dir="ltr">
                    Share anime links to earn points. Rise through the ranks and claim your spot on the podium.
                </p>
            </div>

            @php
                $currentUserRank = '-';
                $todayPoints = 0;
                $totalUsers = \App\Models\User::count();

                if (auth()->check()) {
                    $userStat = $topUsers->where('user.id', auth()->id())->first();
                    if ($userStat) {
                        $currentUserRank =
                            $topUsers->search(function ($item) use ($userStat) {
                                return $item->id == $userStat->id;
                            }) + 1;
                    }

                    $todayPoints = \App\Models\RefClick::where('ref_user_id', auth()->id())
                        ->whereDate('created_at', \Carbon\Carbon::today())
                        ->count();
                }

                $top3 = $topUsers->take(3);
                $restUsers = $topUsers->skip(3);
            @endphp

            {{-- Info Bar & Timer --}}
            <div class="flex flex-col md:flex-row justify-between items-stretch gap-6 mb-16">
                {{-- Season Ends Timer --}}
                <div class="lb-glass rounded-2xl py-5 px-8 text-center shadow-lg flex-1 md:flex-none"
                    x-data="{
                        endDate: '{{ $seasonEndDate ?? '' }}',
                        days: 0,
                        hours: 0,
                        minutes: 0,
                        seconds: 0,
                        expired: false,
                        init() {
                            if (!this.endDate) { this.expired = true; return; }
                            this.tick();
                            setInterval(() => this.tick(), 1000);
                        },
                        tick() {
                            const end = new Date(this.endDate).getTime();
                            const now = Date.now();
                            const diff = end - now;
                            if (diff <= 0) { this.expired = true; return; }
                            this.days = Math.floor(diff / 86400000);
                            this.hours = Math.floor((diff % 86400000) / 3600000);
                            this.minutes = Math.floor((diff % 3600000) / 60000);
                            this.seconds = Math.floor((diff % 60000) / 1000);
                        }
                    }">
                    <p class="text-xs text-gray-500 uppercase tracking-widest font-bold mb-3">Season Ends In</p>
                    <template x-if="!expired">
                        <div class="digital-font text-2xl md:text-3xl font-bold flex gap-3 justify-center items-center"
                            style="color: var(--primary-color);" dir="ltr">
                            <div class="countdown-digit flex flex-col items-center">
                                <span class="leading-none" x-text="days + 'd'"></span>
                            </div>
                            <span class="text-gray-600 opacity-50">:</span>
                            <div class="countdown-digit flex flex-col items-center">
                                <span class="leading-none" x-text="String(hours).padStart(2,'0') + 'h'"></span>
                            </div>
                            <span class="text-gray-600 opacity-50">:</span>
                            <div class="countdown-digit flex flex-col items-center">
                                <span class="leading-none" x-text="String(minutes).padStart(2,'0') + 'm'"></span>
                            </div>
                            <span class="text-gray-600 opacity-50">:</span>
                            <div class="countdown-digit flex flex-col items-center">
                                <span class="leading-none" x-text="String(seconds).padStart(2,'0') + 's'"></span>
                            </div>
                        </div>
                    </template>
                    <template x-if="expired">
                        <div class="text-xl font-bold text-gray-500">Season Ended</div>
                    </template>
                </div>

                {{-- Current Status --}}
                @auth
                    <div
                        class="lb-glass rounded-2xl py-5 px-8 flex-1 w-full text-center md:text-right shadow-lg flex flex-col justify-center relative overflow-hidden">
                        <div
                            class="absolute inset-y-0 left-0 w-1 bg-gradient-to-b from-transparent via-red-600 to-transparent opacity-80">
                        </div>
                        <p class="text-gray-400 text-sm font-semibold tracking-widest uppercase mb-1">Your Status</p>
                        <p class="text-gray-300 text-lg">
                            You earned <span class="font-bold text-glow-green text-xl"
                                style="color: var(--accent-color);">{{ $todayPoints }} points</span> today
                            and you are ranked <span
                                class="text-white font-black text-2xl drop-shadow-md">#{{ $currentUserRank }}</span> of
                            <span class="text-gray-400">{{ number_format($totalUsers) }}</span> users.
                        </p>
                    </div>
                @endauth
            </div>

            {{-- Podium Layout --}}
            @if ($top3->count() > 0)
                <div class="flex flex-col md:flex-row justify-center items-end gap-6 md:gap-8 mb-24 px-2 md:px-10 h-auto"
                    style="margin-top: 8rem;">

                    {{-- Rank 2 (Left) --}}
                    @if ($top3->count() >= 2)
                        @php $rank2 = $top3[1]; @endphp
                        <div
                            class="podium-card gradient-border-2 rounded-3xl p-6 w-full md:w-[30%] flex flex-col items-center justify-between lb-glass order-2 md:order-1 mt-20 md:mt-0" style="height: 400px;">
                            <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full border-4 z-20 overflow-hidden shadow-xl flex-shrink-0"
                                style="margin-top: -4.5rem; border-color: #94a3b8; background: #0a0a0a;">
                                <img src="{{ $rank2->user->profile_photo_url ?? asset('img/default-avatar.png') }}"
                                    alt="Avatar" class="w-full h-full object-cover">
                            </div>
                            <div class="z-30 text-4xl" style="margin-top: -1.75rem;">🥈</div>

                            <div class="flex-grow flex flex-col items-center justify-center w-full mt-2">
                                <h3 class="text-xl font-bold text-white truncate w-full text-center mb-2">
                                    {{ $rank2->user->name ?? 'Unknown' }}</h3>
                                <div class="text-slate-900 px-3 py-1 rounded-full text-xs font-black uppercase"
                                    style="background-color: #94a3b8;">Rank 2</div>
                            </div>

                            <div class="w-full rounded-2xl p-4 text-center mt-2"
                                style="background: rgba(0,0,0,0.4); border: 1px solid rgba(255,255,255,0.05);">
                                <p class="text-xs text-gray-500 font-bold uppercase mb-1">Total Points</p>
                                <p class="text-2xl font-black text-slate-300">{{ number_format($rank2->unique_click) }}
                                </p>
                            </div>
                        </div>
                    @endif

                    {{-- Rank 1 (Center) --}}
                    @php $rank1 = $top3[0]; @endphp
                    <div class="podium-card gradient-border-1 pulse-glow rounded-3xl p-6 w-full md:w-[40%] flex flex-col items-center justify-between lb-glass order-1 md:order-2 z-10"
                        style="box-shadow: 0 15px 35px rgba(0,0,0,0.5); height: 460px;">
                        <div class="w-24 h-24 sm:w-32 sm:h-32 rounded-full border-4 z-20 overflow-hidden shadow-2xl flex-shrink-0"
                            style="margin-top: -4rem; border-color: var(--primary-color); background: #0a0a0a;">
                            <img src="{{ $rank1->user->profile_photo_url ?? asset('img/default-avatar.png') }}"
                                alt="Avatar" class="w-full h-full object-cover">
                        </div>
                        <div class="z-30 text-6xl"
                            style="margin-top: -2.2rem; filter: drop-shadow(0 5px 15px rgba(255,215,0,0.5));">🏆</div>

                        <div class="flex-grow flex flex-col items-center justify-center w-full mt-2">
                            <h3
                                class="text-2xl md:text-3xl font-black text-white text-glow-red w-full text-center truncate mb-3">
                                {{ $rank1->user->name ?? 'Unknown' }}</h3>
                            <div class="text-white px-5 py-1.5 rounded-full text-xs font-black uppercase tracking-widest"
                                style="background-color: var(--primary-color); box-shadow: 0 0 15px var(--primary-glow);">
                                {{ $rank1Achievement ?? 'CHAMPION' }}
                            </div>
                        </div>

                        <div class="w-full rounded-2xl p-4 text-center mt-4"
                            style="background: rgba(0,0,0,0.5); border: 1px solid rgba(255,255,255,0.08);">
                            <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Total Points</p>
                            <p class="text-3xl md:text-4xl font-black mb-3"
                                style="color: var(--primary-color); text-shadow: 0 0 20px var(--primary-glow-subtle);">
                                {{ number_format($rank1->unique_click) }}</p>
                            @if (!empty($leaderboardPrize))
                                <div class="rounded-xl py-2 flex justify-center items-center gap-2 relative overflow-hidden"
                                    style="background: rgba(0,0,0,0.6); border: 1px solid rgba(255,255,255,0.05);">
                                    <span class="text-emerald-400 text-lg pl-2">🎁</span>
                                    <span class="text-sm font-bold text-white pr-2">{{ $leaderboardPrize }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Rank 3 (Right) --}}
                    @if ($top3->count() >= 3)
                        @php $rank3 = $top3[2]; @endphp
                        <div
                            class="podium-card gradient-border-3 rounded-3xl p-6 w-full md:w-1/3 flex flex-col items-center justify-between lb-glass order-3 mt-16 md:mt-0" style="height: 350px;">
                            <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full border-4 z-20 overflow-hidden shadow-xl flex-shrink-0"
                                style="margin-top: -3.5rem; border-color: #b45309; background: #0a0a0a;">
                                <img src="{{ $rank3->user->profile_photo_url ?? asset('img/default-avatar.png') }}"
                                    alt="Avatar" class="w-full h-full object-cover rounded-full">
                            </div>
                            <div class="z-30 text-4xl" style="margin-top: -1.25rem;">🥉</div>

                            <div class="flex-grow flex flex-col items-center justify-center w-full mt-2">
                                <h3 class="text-lg font-bold text-white truncate w-full text-center mb-2">
                                    {{ $rank3->user->name ?? 'Unknown' }}</h3>
                                <div class="text-white px-3 py-1 rounded-full text-xs font-black uppercase"
                                    style="background-color: #b45309;">Rank 3</div>
                            </div>

                            <div class="w-full rounded-2xl p-4 text-center mt-2"
                                style="background: rgba(0,0,0,0.4); border: 1px solid rgba(255,255,255,0.05);">
                                <p class="text-[10px] text-gray-500 font-bold uppercase mb-1">Total Points</p>
                                <p class="text-2xl font-black text-amber-500">{{ number_format($rank3->unique_click) }}
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            {{-- Leaderboard Table Section --}}
            <div
                class="lb-glass rounded-3xl overflow-hidden shadow-[0_15px_50px_rgba(0,0,0,0.6)] border border-white/5 relative z-20">
                <div
                    class="px-8 py-6 border-b border-white/5 bg-gradient-to-r from-red-950/20 to-transparent flex items-center justify-between">
                    <h3 class="text-2xl font-black text-white tracking-tight">Global Ranking</h3>
                    <div class="text-sm font-medium text-gray-400">{{ $restUsers->count() }} Contenders</div>
                </div>

                <div class="overflow-x-auto pb-4 custom-scroll">
                    <table class="w-full text-left whitespace-nowrap min-w-[500px]">
                        <thead>
                            <tr class="bg-black/40 text-xs text-gray-500 font-bold border-b border-white/5">
                                <th class="py-5 px-6 w-20 text-center uppercase tracking-widest">Rank</th>
                                <th class="py-5 px-6 uppercase tracking-widest">User</th>
                                <th class="py-5 px-6 text-center uppercase tracking-widest">Shared</th>
                                <th class="py-5 px-8 text-right uppercase tracking-widest">Points</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($restUsers as $index => $stat)
                                @php $ActualRank = $index + 1 + 3; /* offset by 3 */ @endphp
                                <tr class="table-row-hover group cursor-default h-20">
                                    <td class="py-4 px-6 text-center">
                                        <div
                                            class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-black/50 text-gray-400 font-black font-mono text-sm shadow-inner border border-white/5 group-hover:bg-red-950/30 group-hover:text-white transition-all">
                                            #{{ $ActualRank }}
                                        </div>
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="flex items-center gap-4">
                                            <div class="relative">
                                                <img src="{{ $stat->user->profile_photo_url ?? asset('img/default-avatar.png') }}"
                                                    class="w-12 h-12 rounded-full bg-gray-800 border-2 border-transparent group-hover:border-red-600 transition-colors object-cover shadow-md"
                                                    alt="Avatar">
                                            </div>
                                            <div>
                                                <p
                                                    class="font-black text-white text-lg tracking-tight group-hover:text-red-400 transition-all">
                                                    {{ $stat->user->name ?? 'Unknown' }}</p>
                                                <p class="text-xs text-gray-500 font-medium group-hover:text-gray-400 transition-colors"
                                                    dir="ltr" style="text-align: left;">
                                                    {{ '@' . strtolower(str_replace(' ', '', $stat->user->name ?? 'user')) }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <div
                                            class="inline-flex items-center justify-center px-4 py-1.5 rounded-full bg-black/30 border border-white/5 text-gray-300 font-bold group-hover:bg-red-950/20 transition-colors">
                                            {{ number_format($stat->anime_shared ?? 0) }}
                                        </div>
                                    </td>
                                    <td class="py-4 px-8 text-right">
                                        <div class="flex items-center justify-end gap-3">
                                            <div class="text-2xl font-black font-mono tracking-tighter"
                                                style="color: var(--primary-color);">
                                                {{ number_format($stat->unique_click) }}
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                @if ($top3->count() == 0)
                                    <tr>
                                        <td colspan="4" class="py-32 text-center">
                                            <div class="flex flex-col items-center justify-center opacity-60">
                                                <div
                                                    class="w-20 h-20 rounded-full bg-white/5 border border-white/10 flex items-center justify-center mb-5 animate-pulse">
                                                    <span class="text-3xl">🤫</span>
                                                </div>
                                                <h3 class="text-2xl font-bold text-white mb-2 tracking-tight">
                                                    Silence...</h3>
                                                <p class="text-gray-500">The leaderboard is completely empty. Be the
                                                    first to grab points!</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
