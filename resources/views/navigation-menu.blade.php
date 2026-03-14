<div class="relative z-50" dir="rtl" x-data="{
    openMenu: false,
    showInput: false,
    searchQuery: '',
    searchResults: [],
    isLoading: false,

    openDonationModal: false,
    selectedMethod: null,
    activeCoin: null,

    selectMethod(method) {
        if (typeof method.options === 'string') {
            try { method.options = JSON.parse(method.options); } catch (e) {}
        }
        this.selectedMethod = method;
        this.activeCoin = null;

        if (method.type === 'crypto' && method.options && method.options.coins && method.options.coins.length > 0) {
            this.activeCoin = method.options.coins[0];
        }
    },

    copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            alert('تم النسخ بنجاح! / Copied successfully!');
        });
    },

    async fetchResults() {
        if (this.searchQuery.trim().length < 2) {
            this.searchResults = [];
            return;
        }

        this.isLoading = true;

        try {
            const response = await fetch(`/autocomplete?q=${encodeURIComponent(this.searchQuery)}`);
            const data = await response.json();
            this.searchResults = data;
        } catch (error) {
            console.error('Autocomplete fetch failed:', error);
            this.searchResults = [];
        } finally {
            this.isLoading = false;
        }
    },

    init() {}
}">
    <style>
        [x-cloak] {
            display: none !important;
        }

        .neon-border {
            box-shadow: 0 0 5px theme('colors.red.500'), 0 0 10px theme('colors.red.500');
        }

        .hover-neon:hover {
            box-shadow: 0 0 10px theme('colors.red.500'), 0 0 20px theme('colors.red.500');
        }

        .glass-panel {
            background: rgba(17, 24, 39, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>


    <!-- Header Kustom (Desktop) -->
    <div
        class="absolute top-0 left-0 z-30 w-full hidden md:flex items-center justify-between px-10 py-6 font-cairo text-white">
        <!-- Menu Navigasi Tengah -->
        <div class="flex items-center gap-20 pr-10 text-sm justify-end">
            <a href="/"><img src="https://i.imgur.com/kowI5K8.png" referrerpolicy="no-referrer" alt="Logo"
                    class="h-20 w-auto" /></a>
            <a href="/" class="hover:text-red-500 transition">الصفحة الرئيسية</a>

            <!-- Dropdown untuk قائمة الانمي -->
            <div class="relative group">
                <a href="/list"><button class="hover:text-red-500 transition flex items-center gap-1">قائمة الأعمال
                        <svg class="w-4 h-4 transform group-hover:rotate-180 transition-transform duration-200"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button></a>

                <!-- Dropdown Menu -->
                <div
                    class="absolute top-full right-0 w-32 shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                    <div class="py-1">
                        <a href="/completed"
                            class="block px-3 py-2 text-sm text-white hover:text-red-400 transition text-right">مكتمل</a>
                        <a href="/ongoing"
                            class="block px-3 py-2 text-sm text-white hover:text-red-400 transition text-right">جارٍ</a>
                        <a href="{{ route('collections.index') }}"
                            class="block px-3 py-2 text-sm text-white hover:text-red-400 transition text-right">السلاسل</a>
                    </div>
                </div>
            </div>



            <a href="/advanced-search" class="hover:text-red-500 transition">البحث المتقدم</a>
            <a href="/about" class="hover:text-red-500 transition">عن الفريق</a>
            @if(request()->is('anime/*') || request()->routeIs('anime.show'))
            <!-- Support Us button (hanya di halaman anime) -->
            <button @click.prevent="openDonationModal = true"
               class="bg-red-600 hover:bg-red-700 text-white px-8 py-1 text-lg rounded-2xl transition-colors flex items-center justify-center font-bold shadow-lg shadow-red-900/40 hidden md:flex min-w-[160px]">
               <span>ادعمنا</span>
            </button>
            @endif
        </div>

        <!-- Search & Profile -->
        <div class="flex items-center gap-2">
            <!-- Search Component -->
            <div class="relative">
                <button @click="showInput = !showInput; if(showInput) $nextTick(() => $refs.searchInput.focus())"
                    class="w-10 h-10 rounded-full bg-red-600 flex items-center justify-center hover:bg-red-700 transition">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>

                <!-- Search Form -->
                <form x-show="showInput" action="#" method="GET"
                    @submit="if(!searchQuery.trim()) { $event.preventDefault(); alert('Please enter a search term'); }"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 transform scale-95 -translate-x-4"
                    x-transition:enter-end="opacity-100 transform scale-100 translate-x-0"
                    class="absolute left-12 top-0 z-10 w-72">

                    <!-- Input -->
                    <input x-ref="searchInput" x-model="searchQuery" @input.debounce.100ms="fetchResults" type="text"
                        name="q" placeholder="ابحث عن عمل..."
                        class="h-10 bg-white text-black border border-gray-600 rounded px-4 w-full focus:outline-none focus:ring-2 focus:ring-red-500"
                        @keydown.escape="showInput = false; searchQuery = ''; searchResults = []" autocomplete="off" />

                    <!-- ✅ Loading Spinner -->
                    <div x-show="isLoading" class="mt-4 flex items-center justify-center">
                        <i class="fas fa-spinner fa-lg animate-spin mr-2"></i>
                    </div>

                    <!-- Autocomplete Results -->
                    <div x-show="searchResults.length > 0"
                        class="absolute mt-1 left-0 w-full bg-white/30 backdrop-blur-md border border-gray-300 rounded shadow-lg text-sm text-white max-h-60 overflow-y-auto z-50"
                        x-transition>
                        <template x-for="item in searchResults" :key="item.mal_id">
                            <a :href="`/anime/mal/${item.mal_id}`"
                                class="flex items-center gap-3 px-4 py-2 hover:bg-white/50 border-b border-gray-100">
                                <img :src="item.poster" class="w-10 h-14 object-cover rounded" />
                                <div class="flex flex-col">
                                    <span class="font-semibold" x-text="item.title"></span>
                                    <span class="text-xs text-white"
                                        x-show="item.title_english && item.title_english !== item.title"
                                        x-text="item.title_english"></span>
                                </div>
                            </a>
                        </template>
                    </div>

                </form>
            </div>

            <!-- Profile Icon -->
            @auth
                <a href="{{ route('profile.show') }}"
                    class="w-10 h-10 rounded-full bg-red-600 flex items-center justify-center hover:bg-red-700 transition">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.657 6.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </a>
            @endauth
        </div>
    </div>

    <!-- Mobile & Tablet Header -->
    <div class="md:hidden bg-black/90 text-white px-4 py-3 flex items-center justify-between sticky top-0 z-50">
        <!-- Logo -->
        <a href="/" class="flex items-center">
            <img src="https://i.imgur.com/kowI5K8.png" referrerpolicy="no-referrer" alt="Logo"
                class="h-12 w-auto" />
        </a>

        <!-- Hamburger -->
        <button @click="openMenu = !openMenu" class="focus:outline-none p-2">
            <svg class="w-6 h-6 text-white transition-transform duration-300" :class="{ 'rotate-90': openMenu }"
                fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path x-show="!openMenu" stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                <path x-show="openMenu" stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Mobile Menu Panel -->
    <div x-show="openMenu" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="md:hidden bg-gray-900/95 backdrop-blur-sm text-white pb-6 shadow-xl">

        <!-- Search Input -->
        <div class="px-4 pt-4">
            <form action="{{ route('anime.search') }}" method="GET"
                @submit="if(!searchQuery.trim()) { $event.preventDefault(); alert('الرجاء إدخال كلمة البحث'); }">
                <div class="relative">
                    <input x-model="searchQuery" type="text" name="q" placeholder="ابحث عن عمل..."
                        class="w-full bg-gray-800/90 text-white border border-gray-700 rounded-lg px-4 py-3 pr-10 focus:outline-none focus:ring-2 focus:ring-red-500"
                        autocomplete="off" />
                    <button type="submit" class="absolute right-3 top-1/2 transform -translate-y-1/2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                </div>
            </form>
        </div>

        <!-- Menu Links -->
        <nav class="mt-4 px-4 space-y-3">
            <a href="/"
                class="flex items-center px-3 py-2 rounded-lg hover:bg-gray-800 hover:text-red-400 transition-colors duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                الصفحة الرئيسية
            </a>


            <a href="{{ route('collections.index') }}"
                class="flex items-center px-3 py-2 rounded-lg hover:bg-gray-800 hover:text-red-400 transition-colors duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                    </path>
                </svg>
                السلاسل
            </a>

            <!-- Dropdown Menu -->
            <div class="relative" x-data="{ openDropdown: false }">
                <button @click="openDropdown = !openDropdown"
                    class="w-full flex items-center justify-between px-3 py-2 rounded-lg hover:bg-gray-800 hover:text-red-400 transition-colors duration-200">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        قائمة الأعمال
                    </div>
                    <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': openDropdown }"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div x-show="openDropdown" x-transition class="pl-8 mt-1 space-y-2">
                    <a href="/complete"
                        class="block px-3 py-2 rounded-lg hover:bg-gray-800 hover:text-red-400 transition-colors duration-200">
                        مكتمل
                    </a>
                    <a href="/ongoing"
                        class="block px-3 py-2 rounded-lg hover:bg-gray-800 hover:text-red-400 transition-colors duration-200">
                        جارٍ
                    </a>
                    <a href="{{ route('collections.index') }}"
                        class="block px-3 py-2 rounded-lg hover:bg-gray-800 hover:text-red-400 transition-colors duration-200">
                        السلاسل
                    </a>
                </div>
            </div>

            @if(request()->is('anime/*') || request()->routeIs('anime.show'))
            <!-- Support Us button for Mobile (hanya di halaman anime) -->
            <button @click.prevent="openDonationModal = true; openMenu = false"
                class="w-full flex items-center px-3 py-2 rounded-lg bg-red-900/20 text-red-500 hover:bg-red-900/40 hover:text-red-400 border border-red-500/30 transition-colors duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
                تبرع / Donate
            </button>
            @endif

            <a href="/advanced-search"
                class="flex items-center px-3 py-2 rounded-lg hover:bg-gray-800 hover:text-red-400 transition-colors duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                البحث المتقدم
            </a>

            <a href="/about"
                class="flex items-center px-3 py-2 rounded-lg hover:bg-gray-800 hover:text-red-400 transition-colors duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                عن الفريق
            </a>

            <!-- Profile Link -->
            @auth
                <a href="{{ route('profile.show') }}"
                    class="flex items-center px-3 py-2 rounded-lg hover:bg-gray-800 hover:text-red-400 transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    الملف الشخصي
                </a>
            @endauth
        </nav>
    </div>

    <!-- Donation Modal Overlay -->
    @if(request()->is('anime/*') || request()->routeIs('anime.show'))
        @php
            if (!isset($paymentMethods)) {
                $paymentMethods = \App\Models\PaymentMethod::where('is_active', true)->get();
            }
        @endphp
        <template x-teleport="body">
            <div x-show="openDonationModal" style="display: none;" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-[100] flex items-center justify-center bg-black/80 backdrop-blur-sm p-4">

                <!-- Modal Content -->
            <div @click.away="openDonationModal = false; selectedMethod = null"
                :class="selectedMethod ? 'max-w-5xl h-[90vh] md:h-[80vh]' : 'max-w-2xl h-auto'"
                class="z-999 glass-panel w-full rounded-2xl shadow-2xl overflow-hidden relative text-white transition-all duration-300">

                <!-- Header -->
                <div x-show="!selectedMethod"
                    class="p-6 border-b border-red-900/40 flex justify-between items-center bg-gradient-to-r from-black via-red-950/30 to-black">
                    <h2
                        class="text-2xl font-bold text-red-600 drop-shadow-md">
                        ادعمنا
                    </h2>
                    <button @click="openDonationModal = false; selectedMethod = null"
                        class="text-gray-400 hover:text-white transition transform hover:rotate-90">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Body -->
                <div :class="selectedMethod ? 'p-0 h-full' : 'p-6'">
                    <!-- Method Selection Grid -->
                    <div x-show="!selectedMethod" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach ($paymentMethods as $method)
                            <div @click="@if ($method->type === 'paypal') window.open('{{ $method->content }}', '_blank') @else selectMethod({{ json_encode($method) }}) @endif"
                                class="cursor-pointer group relative p-6 rounded-xl bg-black/60 border border-red-900/30 hover:border-red-600 transition-all duration-300 hover-neon flex flex-col items-center gap-4 text-center">

                                @if ($method->icon)
                                    <img src="/storage/{{ $method->icon }}" referrerpolicy="no-referrer"
                                        class="w-16 h-16 object-contain drop-shadow-lg group-hover:scale-110 transition-transform duration-300"
                                        alt="{{ $method->name }}">
                                @else
                                    <div class="w-16 h-16 rounded-full bg-gray-700 flex items-center justify-center text-2xl group-hover:bg-red-900/50 transition-colors">
                                        🎁</div>
                                @endif

                                <h3 class="font-bold text-lg group-hover:text-red-400 transition-colors">
                                    {{ $method->name }}</h3>

                                @if ($method->type === 'link')
                                    <span class="text-xs text-gray-400 flex items-center gap-1">
                                        فتح الرابط <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                            </path>
                                        </svg>
                                    </span>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <!-- Method Detail View -->
                    <template x-if="selectedMethod">
                        <div class="animate-fadeIn w-full h-full flex flex-col md:flex-row gap-0">
                            <button @click="selectedMethod = null"
                                class="absolute top-4 left-4 z-50 md:hidden p-2 bg-black/50 rounded-full text-white hover:bg-red-600 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>

                            <!-- Sidebar (Visual / Selection) -->
                            <div class="w-full md:w-1/3 bg-black/40 border-b md:border-b-0 md:border-l border-white/5 p-6 md:p-8 flex flex-col items-center justify-center text-center relative overflow-hidden shrink-0">
                                <div class="absolute inset-0 bg-gradient-to-b from-red-900/10 to-transparent"></div>

                                <div class="relative z-10 w-full">
                                    <template x-if="selectedMethod.icon">
                                        <img :src="'/storage/' + selectedMethod.icon" referrerpolicy="no-referrer"
                                            class="w-16 h-16 md:w-24 md:h-24 object-contain mx-auto mb-4 md:mb-6 drop-shadow-2xl">
                                    </template>
                                    <h2 class="text-xl md:text-2xl font-bold text-white mb-2" x-text="selectedMethod.name"></h2>
                                    <p class="text-xs md:text-sm text-gray-400 mb-6 px-4"
                                        x-text="selectedMethod.type === 'crypto' ? 'حول العملات الرقمية بأمان وسرعة' : (selectedMethod.type === 'stc_pay' ? 'الدفع عبر STC Pay' : 'الدفع المحلي المباشر')">
                                    </p>

                                    <template x-if="selectedMethod.type === 'crypto' && selectedMethod.options && selectedMethod.options.coins && selectedMethod.options.coins.length > 0">
                                        <div class="grid grid-cols-2 gap-2 w-full">
                                            <template x-for="coin in selectedMethod.options.coins">
                                                <button @click="activeCoin = coin"
                                                    :class="activeCoin === coin ? 'bg-red-600 text-white border-red-500 shadow-lg shadow-red-900/50' : 'bg-white/5 text-gray-400 border-white/10 hover:bg-white/10'"
                                                    class="py-2 px-3 md:py-3 md:px-4 rounded-xl border font-bold text-xs md:text-sm transition-all duration-300 flex items-center justify-center gap-2">
                                                    <span x-text="coin.coin_name"></span>
                                                </button>
                                            </template>
                                        </div>
                                    </template>
                                    <template x-if="selectedMethod.type === 'crypto' && (!selectedMethod.options || !selectedMethod.options.coins || selectedMethod.options.coins.length === 0)">
                                        <div class="text-center p-4 bg-red-900/20 rounded-xl border border-red-500/30">
                                            <p class="text-red-400 text-xs">No coins configured yet.</p>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- Main Content Area -->
                            <div class="flex-1 w-full md:w-2/3 p-6 md:p-10 overflow-y-auto custom-scroll bg-gradient-to-br from-gray-900 to-black relative">
                                <button @click="selectedMethod = null"
                                    class="absolute top-4 left-4 hidden md:flex items-center text-sm text-gray-400 hover:text-white transition">
                                    <svg class="w-4 h-4 ml-1 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                    العودة
                                </button>

                                <!-- STC Pay Layout -->
                                <template x-if="selectedMethod.type === 'stc_pay'">
                                    <div class="space-y-8 animate-fadeIn mt-8">
                                        <div class="bg-white/5 rounded-2xl p-6 border border-white/10 text-center">
                                            <p class="text-gray-400 text-sm mb-4 uppercase tracking-widest">رقم الهاتف / Phone Number</p>
                                            <div class="flex items-center justify-center gap-4 bg-black/50 p-4 rounded-xl border border-gray-700 mx-auto max-w-sm group focus-within:border-red-500 transition-colors">
                                                <span class="text-2xl font-mono text-white tracking-wider" x-text="selectedMethod.content"></span>
                                                <button @click="copyToClipboard(selectedMethod.content)" class="text-gray-500 hover:text-white transition-colors">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>

                                        <template x-if="selectedMethod.qr_code">
                                            <div class="text-center">
                                                <p class="text-gray-400 text-xs mb-4">مسح الرمز QR للإرسال السريع</p>
                                                <div class="bg-white p-4 rounded-xl inline-block shadow-xl">
                                                    <img :src="'/storage/' + selectedMethod.qr_code" referrerpolicy="no-referrer" class="w-48 h-48 object-contain">
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </template>

                                <!-- Crypto Layout -->
                                <template x-if="selectedMethod.type === 'crypto' && activeCoin">
                                    <div class="space-y-6 animate-fadeIn mt-8">
                                        <div class="flex items-center justify-between mb-2">
                                            <h3 class="text-xl font-bold text-white flex items-center gap-2">
                                                <span class="text-red-500">عملة</span>
                                                <span x-text="activeCoin.coin_name"></span>
                                            </h3>
                                            <span class="text-xs text-gray-500 bg-black/50 px-3 py-1 rounded-full border border-gray-800">تأكد من اختيار الشبكة الصحيحة</span>
                                        </div>

                                        <template x-for="network in activeCoin.networks">
                                            <div class="bg-white/5 rounded-2xl p-0 border border-white/10 overflow-hidden hover:border-red-500/30 transition-colors">
                                                <div class="bg-black/30 px-6 py-3 border-b border-white/5 flex justify-between items-center">
                                                    <span class="font-mono text-blue-400 font-bold" x-text="network.network_name"></span>
                                                    <span class="text-[10px] text-gray-500 uppercase tracking-wider">Network</span>
                                                </div>
                                                <div class="p-6 flex flex-col md:flex-row gap-6 items-center">
                                                    <!-- QR -->
                                                    <template x-if="network.qr_image">
                                                        <div class="bg-white p-2 rounded-lg flex-shrink-0">
                                                            <img :src="'/storage/' + network.qr_image" referrerpolicy="no-referrer" class="w-24 h-24 object-contain">
                                                        </div>
                                                    </template>

                                                    <!-- Address -->
                                                    <div class="flex-1 w-full min-w-0">
                                                        <label class="block text-xs text-gray-400 mb-2">Wallet Address</label>
                                                        <div class="flex items-center gap-3 bg-black/50 p-3 rounded-lg border border-gray-700 group cursor-pointer hover:border-gray-500 transition-colors"
                                                            @click="copyToClipboard(network.wallet_address)">
                                                            <p class="text-xs md:text-sm font-mono text-gray-200 truncate select-all" x-text="network.wallet_address"></p>
                                                            <button class="ml-auto text-gray-500 group-hover:text-white transition-colors">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                        d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </template>

                                <!-- Legacy Manual/Generic Layout -->
                                <template x-if="selectedMethod.type !== 'crypto' && selectedMethod.type !== 'stc_pay'">
                                    <div class="space-y-6 animate-fadeIn mt-8">
                                        <div>
                                            <label class="block text-sm text-gray-400 mb-2">Details / العنوان</label>
                                            <div class="flex items-center gap-2 bg-black/50 p-3 rounded-lg border border-gray-700 group focus-within:border-red-500 transition">
                                                <input type="text" readonly :value="selectedMethod.content"
                                                    class="bg-transparent border-none text-white w-full focus:ring-0 font-mono text-sm">
                                                <button @click="copyToClipboard(selectedMethod.content)"
                                                    class="p-2 text-gray-400 hover:text-white hover:bg-white/10 rounded-lg transition" title="Copy">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>

                                        <template x-if="selectedMethod.qr_code">
                                            <div class="text-center">
                                                <p class="text-gray-400 text-xs mb-4">QR Code</p>
                                                <div class="bg-white p-4 rounded-xl inline-block shadow-xl">
                                                    <img :src="'/storage/' + selectedMethod.qr_code" referrerpolicy="no-referrer" class="w-48 h-48 object-contain">
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </template>

                                <!-- Instructions Block (Common) -->
                                <template x-if="selectedMethod.instruction">
                                    <div class="mt-8 pt-8 border-t border-white/10">
                                        <h4 class="text-lg font-bold text-white mb-4">تعليمات هامة</h4>
                                        <div class="prose prose-invert prose-sm max-w-none text-gray-400 bg-black/20 p-6 rounded-xl border border-white/5"
                                            x-html="selectedMethod.instruction"></div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </template>
    @endif
</div>
