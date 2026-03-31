<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center px-4 py-6 sm:px-6">
        <div class="w-full max-w-md sm:max-w-lg md:max-w-xl px-4 sm:px-6 py-8 sm:py-10 rounded-xl">

            {{-- Logo & Heading --}}
            <div class="flex flex-col items-center justify-center mb-6 sm:mb-8 text-center">
                <x-authentication-card-logo class="w-16 h-16 sm:w-20 sm:h-20" />
                <h1 class="text-xl sm:text-2xl font-bold mt-3 sm:mt-4 text-white">السلام عليكم</h1>
                <p class="text-xs sm:text-sm text-gray-300 mt-1">أهلاً وسهلاً! أدخل بياناتك</p>
            </div>

            {{-- Validation --}}
            <x-validation-errors class="mb-4" />
            @if (session('status'))
                <div class="mb-4 font-medium text-sm text-green-500">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Form --}}
            <form method="POST" action="{{ route('login') }}" class="space-y-4 sm:space-y-6">
                @csrf

                {{-- Email --}}
                <div>
                    <x-label for="email" value="عنوان البريد الإلكتروني:" class="block text-right text-white mb-1 text-sm sm:text-base" />
                    <div class="relative">
                        <input id="email" 
                               type="email" 
                               name="email" 
                               :value="old('email')" 
                               required 
                               autofocus 
                               autocomplete="username"
                               class="w-full pr-10 pl-4 py-2 sm:py-3 rounded-lg border border-gray-600 focus:outline-none focus:ring-2 focus:ring-red-500 text-right text-sm text-black placeholder-gray-400"
                               placeholder="عنوان بريدك الإلكتروني" />
                        <span class="absolute inset-y-0 right-3 flex items-center text-gray-400">
                            <i class="fas fa-envelope text-sm sm:text-base"></i>
                        </span>
                    </div>
                </div>

                {{-- Password --}}
                <div>
                    <x-label for="password" value="كلمة المرور:" class="block text-right text-white mb-1 text-sm sm:text-base" />
                    <div class="relative">
                        <input id="password" 
                               type="password" 
                               name="password" 
                               required 
                               autocomplete="current-password"
                               class="w-full pr-10 pl-10 py-2 sm:py-3 rounded-lg border border-gray-600 focus:outline-none focus:ring-2 focus:ring-red-500 text-right text-sm text-black placeholder-gray-400 selection:bg-red-500 selection:text-white"
                               placeholder="كلمة المرور الخاصة بك" />

                        <span class="absolute inset-y-0 right-3 flex items-center text-gray-400">
                            <i class="fas fa-lock text-sm sm:text-base"></i>
                        </span>
                        <span class="absolute inset-y-0 left-3 flex items-center cursor-pointer text-gray-400" onclick="togglePassword()">
                            <i class="fas fa-eye text-sm sm:text-base" id="togglePasswordIcon"></i>
                        </span>
                    </div>
                </div>

                {{-- Remember Me + Forgot --}}
                <div class="flex justify-between items-center text-sm text-white">
                    <label class="flex items-center space-x-2 space-x-reverse">
                        <input type="checkbox" name="remember" class="form-checkbox text-red-600 rounded" checked>
                        <span>تذكّرني</span>
                    </label>
                    @if (Route::has('password.request'))
                        <a class="text-blue-500 hover:underline" href="{{ route('password.request') }}">
                            هل نسيت كلمة المرور؟
                        </a>
                    @endif
                </div>

                {{-- Turnstile --}}
                <div class="flex flex-col items-center justify-center">
                    <div class="cf-turnstile" data-sitekey="{{ config('services.turnstile.site_key') }}" data-theme="dark"></div>
                    @error('cf-turnstile-response')
                        <span class="text-red-500 text-sm mt-2 text-center">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Submit --}}
                <div>
                    <button type="submit"
                            class="w-full bg-red-600 hover:bg-red-700 text-white font-medium sm:font-semibold py-2 sm:py-3 rounded-lg text-base sm:text-lg transition">
                        تسجيل الدخول
                    </button>
                </div>
            </form>

            {{-- Register --}}
            @if (isPublicRegistrationEnabled())
                <div class="mt-4 text-right">
                    <a href="{{ route('register') }}" class="text-blue-400 hover:text-blue-300 hover:underline text-sm sm:text-base">
                        تسجيل حساب جديد
                    </a>
                </div>
            @else
                <p class="text-red-400 text-xs sm:text-sm mt-3 text-center drop-shadow-lg">
                    🚫التسجيل مغلق الآن، تفقّد حساباتنا على منصّات التواصل الاجتماعي لمزيد من المعلومات
                </p>
            @endif

            {{-- Ikon Media Sosial --}}
            <div class="flex justify-center flex-wrap gap-3 sm:gap-4 text-white text-2xl sm:text-3xl mt-4 sm:mt-6">
                @foreach ($socialMedias as $media)
                    <a href="{{ $media->url }}" class="hover:text-red-500 transition" target="_blank" title="{{ $media->platform }}">
                        <i class="fab fa-{{ getSocialIcon($media->platform) }}"></i>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Toggle Password Script --}}
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const icon = document.getElementById('togglePasswordIcon');
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        }
    </script>
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
</x-guest-layout>