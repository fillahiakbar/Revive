<script src="//unpkg.com/alpinejs" defer></script>

<div class="relative z-50" dir="rtl"
     x-data="{
        openMenu: false,
        showInput: false,
        searchQuery: '',
        searchResults: [],
        isLoading: false,

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
        }
     }">


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
                        <a href="/completed" class="block px-3 py-2 text-sm text-white hover:text-red-400 transition text-right">مكتمل</a>
                        <a href="/ongoing" class="block px-3 py-2 text-sm text-white hover:text-red-400 transition text-right">جارٍ</a>
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
      action="#" 
      method="GET" 
      @submit="if(!searchQuery.trim()) { $event.preventDefault(); alert('Please enter a search term'); }"
      x-transition:enter="transition ease-out duration-200"
      x-transition:enter-start="opacity-0 transform scale-95 -translate-x-4"
      x-transition:enter-end="opacity-100 transform scale-100 translate-x-0"
      class="absolute left-12 top-0 z-10 w-72">

    <!-- Input -->
    <input x-ref="searchInput"
           x-model="searchQuery"
           @input.debounce.100ms="fetchResults"
           type="text" 
           name="q"
           placeholder="ابحث عن عمل..."
           class="h-10 bg-white text-black border border-gray-600 rounded px-4 w-full focus:outline-none focus:ring-2 focus:ring-red-500"
           @keydown.escape="showInput = false; searchQuery = ''; searchResults = []"
           autocomplete="off" />

    <!-- ✅ Loading Spinner -->
<div x-show="isLoading"
     class="mt-4 flex items-center justify-center">
    <i class="fas fa-spinner fa-lg animate-spin mr-2"></i>
</div>

    <!-- Autocomplete Results -->
    <div x-show="searchResults.length > 0"
         class="absolute mt-1 left-0 w-full bg-white/30 backdrop-blur-md border border-gray-300 rounded shadow-lg text-sm text-white max-h-60 overflow-y-auto z-50"
         x-transition>
        <template x-for="item in searchResults" :key="item.mal_id">
            <a :href="`/anime/mal/${item.mal_id}`" class="flex items-center gap-3 px-4 py-2 hover:bg-white/50 border-b border-gray-100">
                <img :src="item.poster" class="w-10 h-14 object-cover rounded" />
                <div class="flex flex-col">
                    <span class="font-semibold" x-text="item.title"></span>
                    <span class="text-xs text-white" x-show="item.title_english && item.title_english !== item.title" x-text="item.title_english"></span>
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
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
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
    <a href="/revive" class="flex items-center">
        <img src="{{ asset('img/logo.png') }}" alt="Logo" class="h-12 w-auto" />
    </a>

    <!-- Hamburger -->
    <button @click="openMenu = !openMenu" class="focus:outline-none p-2">
        <svg class="w-6 h-6 text-white transition-transform duration-300" 
             :class="{ 'rotate-90': openMenu }"
             fill="none" 
             stroke="currentColor" 
             stroke-width="2" 
             viewBox="0 0 24 24">
            <path x-show="!openMenu" stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
            <path x-show="openMenu" stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>
</div>

<!-- Mobile Menu Panel -->
<div x-show="openMenu" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 -translate-y-2"
     x-transition:enter-end="opacity-100 translate-y-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 translate-y-0"
     x-transition:leave-end="opacity-0 -translate-y-2"
     class="md:hidden bg-gray-900/95 backdrop-blur-sm text-white pb-6 shadow-xl">
    
    <!-- Search Input -->
    <div class="px-4 pt-4">
        <form action="{{ route('anime.search') }}" method="GET"
              @submit="if(!searchQuery.trim()) { $event.preventDefault(); alert('الرجاء إدخال كلمة البحث'); }">
            <div class="relative">
                <input x-model="searchQuery"
                       type="text"
                       name="q"
                       placeholder="ابحث عن عمل..."
                       class="w-full bg-gray-800/90 text-white border border-gray-700 rounded-lg px-4 py-3 pr-10 focus:outline-none focus:ring-2 focus:ring-red-500"
                       autocomplete="off" />
                <button type="submit" class="absolute right-3 top-1/2 transform -translate-y-1/2">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>
            </div>
        </form>
    </div>

    <!-- Menu Links -->
    <nav class="mt-4 px-4 space-y-3">
        <a href="/revive" class="flex items-center px-3 py-2 rounded-lg hover:bg-gray-800 hover:text-red-400 transition-colors duration-200">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            الصفحة الرئيسية
        </a>
        
        <!-- Dropdown Menu -->
        <div class="relative" x-data="{ openDropdown: false }">
            <button @click="openDropdown = !openDropdown" 
                    class="w-full flex items-center justify-between px-3 py-2 rounded-lg hover:bg-gray-800 hover:text-red-400 transition-colors duration-200">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    قائمة الأعمال
                </div>
                <svg class="w-4 h-4 transition-transform duration-200" 
                     :class="{ 'rotate-180': openDropdown }"
                     fill="none" 
                     stroke="currentColor" 
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            
            <div x-show="openDropdown" 
                 x-transition
                 class="pl-8 mt-1 space-y-2">
                <a href="/complete" class="block px-3 py-2 rounded-lg hover:bg-gray-800 hover:text-red-400 transition-colors duration-200">
                    مكتمل
                </a>
                <a href="/ongoing" class="block px-3 py-2 rounded-lg hover:bg-gray-800 hover:text-red-400 transition-colors duration-200">
                    جارٍ
                </a>
            </div>
        </div>

        <a href="/advanced-search" class="flex items-center px-3 py-2 rounded-lg hover:bg-gray-800 hover:text-red-400 transition-colors duration-200">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
            </svg>
            البحث المتقدم
        </a>

        <a href="/about" class="flex items-center px-3 py-2 rounded-lg hover:bg-gray-800 hover:text-red-400 transition-colors duration-200">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            عن الفريق
        </a>

        <!-- Profile Link -->
        @auth
        <a href="{{ route('profile.show') }}" class="flex items-center px-3 py-2 rounded-lg hover:bg-gray-800 hover:text-red-400 transition-colors duration-200">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            الملف الشخصي
        </a>
        @endauth
    </nav>
</div>
</div>
