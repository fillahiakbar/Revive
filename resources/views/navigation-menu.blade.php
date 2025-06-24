<!-- Jetstream Navigation Layout -->
<script src="//unpkg.com/alpinejs" defer></script>

<div class="relative z-50" dir="rtl" x-data="{ openMenu: false, showInput: false, searchQuery: '' }">
    <!-- Header Kustom (Desktop) -->
    <div class="absolute top-0 left-0 z-30 w-full hidden md:flex items-center justify-between px-10 py-6 font-cairo text-white">
        <!-- Menu Navigasi Tengah -->
        <div class="flex items-center gap-20 pr-10 text-sm justify-end">
            <a href="/revive"><img src="{{ asset('img/logo.png') }}" alt="Logo" class="h-20 w-auto" /></a>
            <a href="/revive" class="hover:text-red-500 transition">الصفحة الرئيسية</a>
            
            <!-- Dropdown untuk قائمة الانمي -->
            <div class="relative group">
                <a href="/list"><button class="hover:text-red-500 transition flex items-center gap-1">قائمة الأعمال
                    <svg class="w-4 h-4 transform group-hover:rotate-180 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button></a>
                
                <!-- Dropdown Menu -->
                <div class="absolute top-full right-0 w-32 shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                    <div class="py-1">
                        <a href="/ongoing" class="block px-3 py-2 text-sm text-white hover:text-red-400 transition text-right">مكتمل</a>
                        <a href="#" class="block px-3 py-2 text-sm text-white hover:text-red-400 transition text-right">جارٍ</a>
                    </div>
                </div>
            </div>

            <a href="/advanced-search" class="hover:text-red-500 transition">البحث المتقدم</a>
            <a href="/about" class="hover:text-red-500 transition">عن الفريق</a>
        </div>

        <!-- Search & Profile -->
        <div class="flex items-center gap-2">
            <!-- Search Component -->
            <div class="relative">
                <button @click="showInput = !showInput; if(showInput) $nextTick(() => $refs.searchInput.focus())"
                    class="w-10 h-10 rounded-full bg-red-600 flex items-center justify-center hover:bg-red-700 transition">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>

                <!-- Search Form -->
                <form x-show="showInput" 
                      action="{{ route('anime.search') }}" 
                      method="GET" 
                      @submit="if(!searchQuery.trim()) { $event.preventDefault(); alert('Please enter a search term'); }"
                      x-transition:enter="transition ease-out duration-200"
                      x-transition:enter-start="opacity-0 transform scale-95 -translate-x-4"
                      x-transition:enter-end="opacity-100 transform scale-100 translate-x-0"
                      class="absolute left-12 top-0 z-10">
                    <input x-ref="searchInput"
                           x-model="searchQuery"
                           type="text" 
                           name="q"
                           placeholder="ابحث عن عمل..."
                           class="h-10 bg-gray-800 text-white border border-gray-600 rounded px-4 w-64 focus:outline-none focus:ring-2 focus:ring-red-500"
                           @keydown.escape="showInput = false; searchQuery = ''"
                           autocomplete="off" />
                </form>
            </div>

            <!-- Profile Icon -->
            @auth
            <a href="{{ route('profile.show') }}"
               class="w-10 h-10 rounded-full bg-red-600 flex items-center justify-center hover:bg-red-700 transition">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.657 6.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </a>
            @endauth
        </div>
    </div>

    <!-- Mobile & Tablet Header -->
    <div class="md:hidden bg-black/90 text-white px-4 py-4 flex items-center justify-between">
        <!-- Logo -->
        <a href="/revive">
            <img src="{{ asset('img/logo.png') }}" alt="Logo" class="h-14 w-auto" />
        </a>

        <!-- Hamburger -->
        <button @click="openMenu = !openMenu" class="focus:outline-none">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path x-show="!openMenu" stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                <path x-show="openMenu" stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <!-- Mobile Menu Panel -->
    <div x-show="openMenu" x-transition class="md:hidden px-6 pb-4 space-y-3 bg-gray-900 text-white text-sm">
        <a href="/revive" class="block hover:text-red-400">الصفحة الرئيسية</a>
        <a href="/list" class="block hover:text-red-400">قائمة الأعمال</a>
        <a href="/ongoing" class="block hover:text-red-400">مكتمل</a>
        <a href="#" class="block hover:text-red-400">جارٍ</a>
        <a href="/advanced-search" class="block hover:text-red-400">البحث المتقدم</a>
        <a href="/about" class="block hover:text-red-400">عن الفريق</a>

        <!-- Search Input -->
        <form action="{{ route('anime.search') }}" method="GET" @submit="if(!searchQuery.trim()) { $event.preventDefault(); alert('Please enter a search term'); }">
            <input x-model="searchQuery"
                   type="text"
                   name="q"
                   placeholder="ابحث عن عمل..."
                   class="w-full mt-2 bg-gray-800 text-white border border-gray-600 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-red-500"
                   autocomplete="off" />
        </form>

        <!-- Profile Link -->
        @auth
        <a href="{{ route('profile.show') }}" class="block text-right text-white hover:text-red-500 mt-2">
            الملف الشخصي
        </a>
        @endauth
    </div>
</div>
