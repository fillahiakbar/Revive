<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <div class="flex flex-col items-center justify-center mb-6">
                <x-authentication-card-logo class="w-24 h-24" />
                <h1 class="text-2xl font-bold mt-1">لنبدأ</h1>
                <p class="text-sm text-gray-500 mt-1">مرحباً بعودتك! يُرجى إدخال بياناتك</p>
            </div>
        </x-slot>

        <x-validation-errors class="mb-4 " />

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            {{-- Email --}}
            <div>
                <x-label for="email" value="{{ __('البريد الإلكتروني') }}" />
                <x-input id="email" class="block mt-1 w-full text-right" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            </div>

            {{-- Password --}}
            <div class="mt-4">
                <x-label for="password" value="{{ __('كلمة المرور') }}" />
                <x-input id="password" class="block mt-1 w-full text-right" type="password" name="password" required autocomplete="current-password" />
            </div>

            {{-- Forgot Password --}}
            <div class="mt-2 text-sm text-right">
                @if (Route::has('password.request'))
                    <a class="text-blue-600 hover:underline" href="{{ route('password.request') }}">
                        {{ __('هل نسيت كلمة المرور؟') }}
                    </a>
                @endif
            </div>

            {{-- Submit --}}
            <div class="flex justify-center mt-6">
                <x-button class="bg-red-600 text-white hover:bg-red-700 w-full justify-center py-2 rounded-lg">
                    {{ __('تسجيل الدخول') }}
                </x-button>
            </div>
        </form>

        {{-- Register --}}
        <div class="mt-6 text-center text-sm">
            <span>أليس لديك حساب؟</span>
            <a href="{{ route('register') }}" class="text-blue-600 hover:underline">{{ __('سجّل الآن') }}</a>
        </div>
    </x-authentication-card>
</x-guest-layout>
