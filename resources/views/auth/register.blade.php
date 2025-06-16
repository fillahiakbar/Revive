<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center px-4 py-6">
        <div class="w-full max-w-lg sm:max-w-lg rounded-2xl bg-white/80 backdrop-blur-md shadow-xl px-20 py-12">
            
            {{-- Logo & Heading --}}
            <div class="flex flex-col items-center justify-center mb-6 text-center">
                <x-authentication-card-logo class="w-16 h-16" />
                <h1 class="text-2xl font-bold mt-4">إنشاء حساب</h1>
            </div>

            <x-validation-errors class="mb-4" />

            <form method="POST" action="{{ route('register') }}">
                @csrf

                {{-- Name --}}
                <div>
                    <x-label for="name" value="{{ __('اسم:') }}" />
                    <x-input id="name" class="block mt-1 w-full text-right" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                </div>

                {{-- Email --}}
                <div class="mt-4">
                    <x-label for="email" value="{{ __('البريد الإلكتروني:') }}" />
                    <x-input id="email" class="block mt-1 w-full text-right" type="email" name="email" :value="old('email')" required autocomplete="username" />
                </div>

                {{-- Password --}}
                <div class="mt-4">
                    <x-label for="password" value="{{ __('كلمة المرور:') }}" />
                    <x-input id="password" class="block mt-1 w-full text-right" type="password" name="password" required autocomplete="new-password" />
                </div>

                {{-- Password Confirmation --}}
                <div class="mt-4">
                    <x-label for="password_confirmation" value="{{ __('تأكيد كلمة المرور:') }}" />
                    <x-input id="password_confirmation" class="block mt-1 w-full text-right" type="password" name="password_confirmation" required autocomplete="new-password" />
                </div>

                {{-- Terms (Optional) --}}
                @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                    <div class="mt-4 text-sm text-right">
                        <x-label for="terms">
                            <div class="flex items-center">
                                <x-checkbox name="terms" id="terms" required />
                                <div class="ms-2">
                                    {!! __('أوافق على :terms_of_service و :privacy_policy', [
                                            'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-gray-600 hover:text-gray-900">'.__('شروط الخدمة').'</a>',
                                            'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-gray-600 hover:text-gray-900">'.__('سياسة الخصوصية').'</a>',
                                    ]) !!}
                                </div>
                            </div>
                        </x-label>
                    </div>
                @endif

                {{-- Actions --}}
                <div class="mt-6">
                    <x-button class="bg-red-600 text-white hover:bg-red-700 w-full justify-center py-2 rounded-lg">
                        {{ __('تسجيل الدخول') }}
                    </x-button>
                </div>

                <div class="mt-4 text-center text-sm">
                    <a class="text-blue-600 hover:underline" href="{{ route('login') }}">
                        {{ __('سجِّل دخول من هنا') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
