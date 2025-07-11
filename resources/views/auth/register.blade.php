<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center px-4 py-6 sm:px-6">
        <div class="w-full max-w-md sm:max-w-lg md:max-w-xl px-4 sm:px-6 py-8 sm:py-10 rounded-xl">

            {{-- Logo & Heading --}}
            <div class="flex flex-col items-center justify-center mb-6 sm:mb-8 text-center">
                <x-authentication-card-logo class="w-16 h-16 sm:w-20 sm:h-20" />
                <h1 class="text-xl sm:text-2xl font-bold mt-3 sm:mt-4 text-white">إنشاء حساب</h1>
            </div>

            {{-- Validation --}}
            <x-validation-errors class="mb-4" />

            <form method="POST" action="{{ route('register') }}" class="space-y-4 sm:space-y-5">
                @csrf

                {{-- Name --}}
                <div>
                    <x-label for="name" value="الاسم الكامل:" class="block text-white text-right mb-1 text-sm sm:text-base" />
                    <div class="relative">
                        <input id="name" name="name" type="text" :value="old('name')" required autofocus autocomplete="name"
                            class="w-full pr-10 pl-4 py-2 sm:py-3 rounded-lg border border-gray-600 text-black placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500 text-sm text-right"
                            placeholder="أدخل اسمك الكامل" />
                        <span class="absolute inset-y-0 right-3 flex items-center text-gray-400">
                            <i class="fas fa-user text-sm sm:text-base"></i>
                        </span>
                    </div>
                </div>

                {{-- Email --}}
                <div>
                    <x-label for="email" value="البريد الإلكتروني:" class="block text-white text-right mb-1 text-sm sm:text-base" />
                    <div class="relative">
                        <input id="email" name="email" type="email" :value="old('email')" required autocomplete="username"
                            class="w-full pr-10 pl-4 py-2 sm:py-3 rounded-lg border border-gray-600 text-black placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500 text-sm text-right"
                            placeholder="عنوان بريدك الإلكتروني" />
                        <span class="absolute inset-y-0 right-3 flex items-center text-gray-400">
                            <i class="fas fa-envelope text-sm sm:text-base"></i>
                        </span>
                    </div>
                </div>

                {{-- Password --}}
                <div>
                    <x-label for="password" value="كلمة المرور:" class="block text-white text-right mb-1 text-sm sm:text-base" />
                    <div class="relative">
                        <input id="password" name="password" type="password" required autocomplete="new-password"
                            class="w-full pr-10 pl-10 py-2 sm:py-3 rounded-lg border border-gray-600 text-black placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500 text-sm text-right"
                            placeholder="كلمة المرور الخاصة بك" />
                        <span class="absolute inset-y-0 right-3 flex items-center text-gray-400">
                            <i class="fas fa-lock text-sm sm:text-base"></i>
                        </span>
                        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400 cursor-pointer" onclick="togglePassword('password', 'passwordIcon')">
                            <i class="fas fa-eye text-sm sm:text-base" id="passwordIcon"></i>
                        </span>
                    </div>
                </div>

                {{-- Confirm Password --}}
                <div>
                    <x-label for="password_confirmation" value="تأكيد كلمة المرور:" class="block text-white text-right mb-1 text-sm sm:text-base" />
                    <div class="relative">
                        <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                            class="w-full pr-10 pl-10 py-2 sm:py-3 rounded-lg border border-gray-600 text-black placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500 text-sm text-right"
                            placeholder="أعد كتابة كلمة المرور" />
                        <span class="absolute inset-y-0 right-3 flex items-center text-gray-400">
                            <i class="fas fa-lock text-sm sm:text-base"></i>
                        </span>
                        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400 cursor-pointer" onclick="togglePassword('password_confirmation', 'confirmPasswordIcon')">
                            <i class="fas fa-eye text-sm sm:text-base" id="confirmPasswordIcon"></i>
                        </span>
                    </div>
                </div>

                {{-- Terms --}}
                @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                    <div class="text-xs sm:text-sm text-white text-right mt-2">
                        <x-label for="terms">
                            <div class="flex items-center">
                                <x-checkbox name="terms" id="terms" required class="h-4 w-4" />
                                <div class="ms-2 rtl:mr-2 rtl:ml-0">
                                    {!! __('أوافق على :terms_of_service و :privacy_policy', [
                                            'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-blue-400 hover:text-blue-300">'.__('شروط الخدمة').'</a>',
                                            'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-blue-400 hover:text-blue-300">'.__('سياسة الخصوصية').'</a>',
                                    ]) !!}
                                </div>
                            </div>
                        </x-label>
                    </div>
                @endif

                {{-- Register Button --}}
                <div>
                    <x-button class="w-full items-center justify-center bg-red-600 hover:bg-red-700 text-white font-medium sm:font-semibold py-2 sm:py-3 rounded-lg text-base sm:text-lg">
                        {{ __('تسجيل الحساب') }}
                    </x-button>
                </div>

                {{-- Link to login --}}
                <div class="mt-3 sm:mt-4 text-right text-xs sm:text-sm text-white">
                    <span>هل لديك حساب؟</span>
                    <a class="text-blue-400 hover:text-blue-300 hover:underline" href="{{ route('login') }}">سجِّل الدخول</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Toggle Password Script --}}
    <script>
        function togglePassword(fieldId, iconId) {
            const input = document.getElementById(fieldId);
            const icon = document.getElementById(iconId);
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        }
    </script>
</x-guest-layout>