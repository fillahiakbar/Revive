<x-action-section>
    <x-slot name="title">
        <span class="text-gray-100">{{ __('إحصائيات الإحالة الخاصة بك') }}</span>
    </x-slot>

    <x-slot name="description">
        <span class="text-gray-400">{{ __('عرض إحصائيات الإحالة الخاصة بك وترتيبك في لوحة المتصدرين.') }}</span>
    </x-slot>

    <x-slot name="content">
        @php
            $stats = auth()->user()->currentRefStat ?? new \App\Models\RefStat([
                'total_click' => 0,
                'unique_click' => 0,
                'anime_shared' => 0
            ]);
            $activeSeason = \App\Models\LeaderboardSeason::active();
            $rank = $activeSeason 
                ? \App\Models\RefStat::where('season_id', $activeSeason->id)
                    ->where('unique_click', '>', $stats->unique_click)
                    ->count() + 1
                : '-';
            if ($stats->unique_click == 0) $rank = '-';
        @endphp
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4" dir="rtl">
            <div class="bg-gray-800 p-4 rounded-lg shadow border border-gray-700 text-center">
                <p class="text-sm text-gray-400 mb-1">الترتيب</p>
                <p class="text-3xl font-bold text-yellow-400">#{{ $rank }}</p>
            </div>
            <div class="bg-gray-800 p-4 rounded-lg shadow border border-gray-700 text-center">
                <p class="text-sm text-gray-400 mb-1">الزيارات الفريدة</p>
                <p class="text-3xl font-bold text-blue-400">{{ number_format($stats->unique_click) }}</p>
            </div>
            <div class="bg-gray-800 p-4 rounded-lg shadow border border-gray-700 text-center">
                <p class="text-sm text-gray-400 mb-1">إجمالي الزيارات</p>
                <p class="text-3xl font-bold text-white">{{ number_format($stats->total_click) }}</p>
            </div>
            <div class="bg-gray-800 p-4 rounded-lg shadow border border-gray-700 text-center">
                <p class="text-sm text-gray-400 mb-1">الأنميات المُشاركة</p>
                <p class="text-3xl font-bold text-purple-400">{{ number_format($stats->anime_shared) }}</p>
            </div>
        </div>

        <div class="mt-6 flex flex-col sm:flex-row items-center gap-4 justify-between" dir="rtl">
            <div class="flex-1">
                <p class="text-sm text-gray-400 mb-1">كود الإحالة الخاص بك:</p>
                <code class="bg-black px-3 py-1 rounded text-green-400 font-mono text-lg select-all">{{ auth()->user()->ref_code }}</code>
            </div>
            
            <a href="{{ route('leaderboard.show') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 mt-4 sm:mt-0">
                عرض لوحة المتصدرين
            </a>
        </div>
    </x-slot>
</x-action-section>
