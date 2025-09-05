<x-app-layout>
    <div class="pt-24 pb-6">
        <div class="max-w-7xl pt-24 mx-auto sm:px-6 lg:px-8">

            {{-- Judul Halaman --}}
            <div class="text-white text-right mb-6">
                <h1 class="text-lg font-bold">التصنيفات</h1>
            </div>

            {{-- Navigation A-Z + 0-9 + الكل --}}
            <div class="px-4 py-6 rounded shadow mb-10 text-black">
                <div class="flex flex-wrap justify-center items-center gap-2 text-sm font-bold">
                    {{-- ALL --}}
                    <a href="{{ route('anime.genres', ['letter' => 'ALL']) }}"
                       class="h-8 px-3 rounded-md flex items-center justify-center transition
                       {{ $letter === 'ALL' ? 'bg-red-500 text-black' : 'bg-gray-200 hover:bg-red-400 hover:text-black' }}">
                        الكل
                    </a>

                    {{-- 0-9 --}}
                    <a href="{{ route('anime.genres', ['letter' => '0-9']) }}"
                       class="h-8 px-3 rounded-md flex items-center justify-center transition
                       {{ $letter === '0-9' ? 'bg-red-500 text-black' : 'bg-gray-200 hover:bg-red-400 hover:text-black' }}">
                        0-9
                    </a>

                    {{-- A-Z --}}
                    @foreach(range('A', 'Z') as $char)
                        <a href="{{ route('anime.genres', ['letter' => $char]) }}"
                           class="w-8 h-8 rounded-md flex items-center justify-center transition
                           {{ $letter === $char ? 'bg-red-500 text-black' : 'bg-gray-200 hover:bg-red-400 hover:text-black' }}">
                            {{ $char }}
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Genre Single Column Layout --}}
            @php
                // pastikan $genres adalah collection (DB only)
                $genres = collect($genres ?? []);
            @endphp

            <div class="grid grid-cols-1 gap-6">
                @if($genres->count() > 0)
                    @php
                        // Group genres by first letter
                        $groupedGenres = [];
                        foreach($genres as $genre) {
                            $firstLetter = strtoupper(substr($genre['name'], 0, 1));
                            if (is_numeric($firstLetter)) {
                                $firstLetter = '0-9';
                            }
                            $groupedGenres[$firstLetter][] = $genre;
                        }
                        
                        // Sort the groups alphabetically
                        ksort($groupedGenres);
                        
                        // Move 0-9 to the beginning if it exists
                        if (isset($groupedGenres['0-9'])) {
                            $zeroNine = $groupedGenres['0-9'];
                            unset($groupedGenres['0-9']);
                            $groupedGenres = ['0-9' => $zeroNine] + $groupedGenres;
                        }
                    @endphp

                    {{-- Alternatif: UL list dengan bullet points (FIXED) --}}
                    @foreach($groupedGenres as $letter => $genreGroup)
                        <div class="p-6 w-full">
                            {{-- Row Header --}}
                            <div class="mb-6 pb-3 border-b border-gray-600">
                                <h2 class="text-white font-bold text-2xl">{{ $letter }}</h2>
                            </div>
                            
                            {{-- Genre UL List dengan Bullets - Tampil --}}
                            <ul class="genre-list columns-2 md:columns-3 lg:columns-4 gap-6 space-y-1 pl-6 pr-10 list-disc">
                                @foreach($genreGroup as $genre)
                                    <li class="genre-item break-inside-avoid text-gray-300 ml-0">
                                        {{-- $genre['mal_id'] berisi SLUG genre dari DB → route menampilkan anime per-genre --}}
                                        <a href="{{ route('anime.by-genre', $genre['mal_id']) }}" 
                                           class="text-gray-300 hover:text-red-400 transition-colors duration-200 text-sm">
                                            {{ $genre['name'] }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach

                @else
                    {{-- Show all letters when filtering by single letter --}}
                    @if($letter !== 'ALL')
                        @php
                            $letters = $letter === '0-9' ? ['0-9'] : [$letter];
                        @endphp
                        
                        @foreach($letters as $currentLetter)
                            <div class="p-6 w-full">
                                {{-- Row Header --}}
                                <div class="mb-6 pb-3 border-b">
                                    <h2 class="text-white font-bold text-2xl">{{ $currentLetter }}</h2>
                                </div>
                                
                                {{-- Genre Grid in Row --}}
                                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-3">
                                    @if($genres->count() > 0)
                                        @foreach($genres as $genre)
                                            <div class="genre-item">
                                                <a href="{{ route('anime.by-genre', $genre['mal_id']) }}" 
                                                   class="block text-gray-300 hover:text-red-400 hover:bg-gray-700 hover:bg-opacity-50 
                                                          px-4 py-3 rounded-lg transition-all duration-200 text-sm text-center
                                                          border border-gray-600 hover:border-red-400">
                                                    <div class="font-medium">{{ $genre['name'] }}</div>
                                                    @if(isset($genre['count']))
                                                        <div class="text-xs text-gray-500 mt-1">{{ $genre['count'] }} anime</div>
                                                    @endif
                                                </a>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="col-span-full text-center py-8">
                                            <div class="text-gray-500">
                                                <i class="fas fa-search text-3xl mb-3"></i>
                                                <p>No genres found for "{{ $currentLetter }}"</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @else
                        {{-- Show placeholder when ALL is selected but no data --}}
                        <div class="p-6 w-full">
                            <div class="text-center py-12">
                                <div class="text-gray-400">
                                    @if(isset($loading) && $loading)
                                        <div class="flex items-center justify-center">
                                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-red-500"></div>
                                            <span class="ml-3 text-lg">Loading genres...</span>
                                        </div>
                                    @elseif(isset($error))
                                        <div class="text-red-400">
                                            <i class="fas fa-exclamation-triangle text-3xl mb-3"></i>
                                            <p class="text-lg">{{ $error }}</p>
                                            <button onclick="window.location.reload()" 
                                                    class="mt-4 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
                                                Try Again
                                            </button>
                                        </div>
                                    @else
                                        <div>
                                            <i class="fas fa-list text-3xl mb-3"></i>
                                            <p class="text-lg">No genres available</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
            </div>

            {{-- Pagination if available --}}
            @if(isset($genres) && method_exists($genres, 'links'))
                <div class="mt-8 flex justify-center">
                    {{ $genres->links() }}
                </div>
            @endif

            {{-- Custom Styles --}}
            <style>
                /* Scrollbar styling for better appearance */
                ::-webkit-scrollbar { width: 6px; }
                ::-webkit-scrollbar-track { background: rgba(55, 65, 81, 0.3); border-radius: 3px; }
                ::-webkit-scrollbar-thumb { background: rgba(239, 68, 68, 0.5); border-radius: 3px; }
                ::-webkit-scrollbar-thumb:hover { background: rgba(239, 68, 68, 0.7); }

                /* Row hover effects */
                .bg-gray-800:hover { background-color: rgba(55, 65, 81, 0.7) !important; transform: translateY(-2px); box-shadow: 0 4px 20px rgba(0,0,0,0.3); }

                /* Genre item animations */
                .genre-item { transition: transform 0.2s ease; }
                .genre-item:hover { transform: translateY(-3px); }

                /* Letter navigation active state */
                .letter-nav.active { background: linear-gradient(135deg, #ef4444, #dc2626); transform: scale(1.1); }

                /* Responsive grid adjustments */
                @media (max-width: 640px) { .grid-cols-2 { grid-template-columns: repeat(2, 1fr); } }
                @media (min-width: 641px) and (max-width: 768px) { .md\:grid-cols-3 { grid-template-columns: repeat(3, 1fr); } }
                @media (min-width: 769px) and (max-width: 1024px) { .lg\:grid-cols-4 { grid-template-columns: repeat(4, 1fr); } }
                @media (min-width: 1025px) { .xl\:grid-cols-6 { grid-template-columns: repeat(6, 1fr); } }

                /* Enhanced loading spinner */
                @keyframes pulse-border { 0%,100%{border-color:rgba(239,68,68,0.3);} 50%{border-color:rgba(239,68,68,0.8);} }
                .animate-pulse-border { animation: pulse-border 2s infinite; }
            </style>

            {{-- Enhanced JavaScript --}}
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    document.documentElement.style.scrollBehavior = 'smooth';

                    const genreItems = document.querySelectorAll('.genre-item a');
                    genreItems.forEach(item => {
                        item.addEventListener('mouseenter', function() {
                            this.style.transform = 'scale(1.05)';
                            this.style.boxShadow = '0 4px 15px rgba(239, 68, 68, 0.2)';
                        });
                        item.addEventListener('mouseleave', function() {
                            this.style.transform = 'scale(1)';
                            this.style.boxShadow = 'none';
                        });
                    });

                    const genreRows = document.querySelectorAll('.bg-gray-800');
                    const observerOptions = { threshold: 0.1, rootMargin: '0px 0px -50px 0px' };
                    const observer = new IntersectionObserver((entries) => {
                        entries.forEach(entry => {
                            if (entry.isIntersecting) {
                                entry.target.style.opacity = '1';
                                entry.target.style.transform = 'translateY(0)';
                            }
                        });
                    }, observerOptions);

                    genreRows.forEach(row => {
                        row.style.opacity = '0';
                        row.style.transform = 'translateY(30px)';
                        row.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                        observer.observe(row);
                    });

                    const letterNavs = document.querySelectorAll('.letter-nav');
                    letterNavs.forEach(nav => {
                        nav.addEventListener('click', function() {
                            const content = this.textContent;
                            this.innerHTML = '<div class="animate-spin rounded-full h-4 w-4 border-b-2 border-current"></div>';
                            setTimeout(() => { this.textContent = content; }, 1000);
                        });
                    });

                    document.addEventListener('keydown', function(e) {
                        if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
                            e.preventDefault();
                            const rows = document.querySelectorAll('.bg-gray-800');
                            const currentIndex = Array.from(rows).findIndex(row => row.getBoundingClientRect().top > 0);
                            let targetIndex = e.key === 'ArrowDown' ? Math.min(currentIndex + 1, rows.length - 1)
                                                                     : Math.max(currentIndex - 1, 0);
                            if (rows[targetIndex]) {
                                rows[targetIndex].scrollIntoView({ behavior: 'smooth', block: 'start' });
                            }
                        }
                    });
                });
            </script>
        </div>
    </div>
</x-app-layout>
