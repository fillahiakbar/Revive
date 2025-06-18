<!-- Jetstream Navigation Layout -->
<div class="relative z-50" dir="rtl">
    <!-- Header Kustom -->
    <div class="absolute top-0 left-0 z-30 w-full flex items-center justify-between px-10 py-6 font-cairo text-white">
        <!-- Menu Navigasi Tengah -->
        <div class="flex items-center gap-20 pr-10 text-sm justify-end">
            <img src="{{ asset('img/logo.png') }}" alt="Logo" class="h-20 w-auto" />
            <a href="/revive" class="hover:text-red-500 transition">الصفحة الرئيسية</a>
            
            <!-- Dropdown untuk قائمة الانمي -->
            <div class="relative group">
                <button class="hover:text-red-500 transition flex items-center gap-1">
                    قائمة الانمي
                    <svg class="w-4 h-4 transform group-hover:rotate-180 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                
                <!-- Dropdown Menu -->
                <div class="absolute top-full right-0 w-32 shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                    <div class="py-1">
                        <a href="/list" class="block px-3 py-2 text-sm text-white hover:text-red-400 transition text-right">
                            مكتمل
                        </a>
                        <a href="/ongoing" class="block px-3 py-2 text-sm text-white hover:text-red-400 transition text-right">
                            جاري
                        </a>
                        <a href="/genres" class="block px-3 py-2 text-sm text-white hover:text-red-400 transition text-right">
                            حسب النوع
                        </a>
                    </div>
                </div>
            </div>
            
            <a href="/advenced-search" class="hover:text-red-500 transition">البحث المتقدم</a>
            <a href="/about" class="hover:text-red-500 transition">عن الفريق</a>
        </div>

        <!-- Ikon Kiri -->
        <div class="flex items-center gap-4 pl-10">
            <!-- Search Icon -->
            <a href="/genres" class="group">
                <button class="w-10 h-10 rounded-full bg-red-600 flex items-center justify-center hover:bg-red-700 transition">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </a>
 
            <!-- User Dropdown Jetstream -->
            @auth
                <x-dropdown align="left" width="48">
                    <x-slot name="trigger">
                        <button class="w-10 h-10 rounded-full bg-red-600 flex items-center justify-center hover:bg-red-700 transition">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.657 6.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <!-- Manage Account -->
                        <div class="block px-4 py-2 text-xs text-gray-400">
                            {{ __('Manage Account') }}
                        </div>

                        <x-dropdown-link href="{{ route('profile.show') }}">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                            <x-dropdown-link href="{{ route('api-tokens.index') }}">
                                {{ __('API Tokens') }}
                            </x-dropdown-link>
                        @endif

                        <div class="border-t border-gray-200 my-1"></div>

                        <!-- Logout -->
                        <form method="POST" action="{{ route('logout') }}" x-data>
                            @csrf
                            <x-dropdown-link href="{{ route('logout') }}"
                                             @click.prevent="$root.submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            @endauth
        </div>
    </div>
</div>