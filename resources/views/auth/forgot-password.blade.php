<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center px-4 py-6 sm:px-6">
        <div class="w-full max-w-md sm:max-w-lg px-4 sm:px-6 py-8 sm:py-10 rounded-xl">

            {{-- Logo --}}
            <div class="flex justify-center mb-6">
                <x-authentication-card-logo class="w-16 h-16 sm:w-20 sm:h-20" />
            </div>

            {{-- Instruction Text --}}
            <div class="mb-4 text-sm sm:text-base text-gray-300 text-right leading-relaxed">
                {{ __('نسيت كلمة مرورك؟ لا مشكلة. ما عليك سوى تزويدنا بعنوان بريدك الإلكتروني وسنرسل لك رابط إعادة تعيين كلمة المرور لاختيار كلمة مرور جديدة.') }}
            </div>

            {{-- Status Message --}}
            @if (session('status'))
                <div class="mb-4 font-medium text-sm sm:text-base text-green-500 text-right">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Validation Errors --}}
            <x-validation-errors class="mb-4" />

            {{-- Form --}}
            <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                @csrf

                {{-- Email Input --}}
                <div class="block">
                    <x-label for="email" value="{{ __('البريد الإلكتروني') }}" class="block text-right text-white mb-1 text-sm sm:text-base" />
                    <div class="relative">
                        <x-input id="email" 
                                class="block w-full pr-10 pl-4 py-2 sm:py-3 rounded-lg border border-gray-600 text-black placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500 text-sm text-right" 
                                type="email" 
                                name="email" 
                                :value="old('email')" 
                                required 
                                autofocus 
                                autocomplete="username"
                                placeholder="أدخل بريدك الإلكتروني" />
                        <span class="absolute inset-y-0 right-3 flex items-center text-gray-400">
                            <i class="fas fa-envelope text-sm sm:text-base"></i>
                        </span>
                    </div>
                </div>

                {{-- Submit Button --}}
                <div class="flex items-center justify-center mt-6">
                    <x-button class="bg-red-600 hover:bg-red-700 text-white font-medium sm:font-semibold w-full justify-center py-2 sm:py-3 rounded-lg text-sm sm:text-base">
                        {{ __('رابط إعادة تعيين كلمة المرور') }}
                    </x-button>
                </div>
            </form>

            {{-- Back to Login Link --}}
            <div class="mt-6 text-center text-xs sm:text-sm text-gray-300">
                <a href="{{ route('login') }}" class="text-blue-400 hover:text-blue-300 hover:underline">
                    {{ __('العودة إلى صفحة تسجيل الدخول') }}
                </a>
            </div>
        </div>
    </div>
</x-guest-layout>