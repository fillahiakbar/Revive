<x-admin-layout>
    <div class="min-h-screen flex justify-center px-4 py-6">
        <div class="w-[500px] px-6 sm:px-10 py-10 rounded-xl">

            {{-- Logo & Heading --}}
            <div class="flex flex-col items-center justify-center mb-8 text-center">
                <x-authentication-card-logo class="w-20 h-20" />
                <h1 class="text-2xl font-bold mt-4 text-white">مرحبًا بك، مسؤول</h1>
                <p class="text-sm text-gray-300 mt-1">يرجى إدخال بيانات تسجيل الدخول</p>
            </div>

            {{-- Validation --}}
            <x-validation-errors class="mb-4" />
            @if (session('status'))
                <div class="mb-4 font-medium text-sm text-green-500">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Form --}}
            <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-6">
                @csrf

                {{-- Email --}}
                <div>
                    <x-label for="email" value="البريد الإلكتروني:" class="block text-right text-white mb-1" />
                    <div class="relative">
                        <input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username"
                            class="w-full pr-10 pl-4 py-3 rounded-lg border border-gray-600 focus:outline-none focus:ring-2 focus:ring-red-500 text-right text-sm text-black placeholder-gray-400"
                            placeholder="أدخل بريدك الإلكتروني" />
                        <span class="absolute inset-y-0 right-3 flex items-center text-gray-400">
                            <i class="fas fa-envelope"></i>
                        </span>
                    </div>
                </div>

                {{-- Password --}}
                <div>
                    <x-label for="password" value="كلمة المرور:" class="block text-right text-white mb-1" />
                    <div class="relative">
                        <input id="password" type="password" name="password" required autocomplete="current-password"
                            class="w-full pr-10 pl-10 py-3 rounded-lg border border-gray-600 focus:outline-none focus:ring-2 focus:ring-red-500 text-right text-sm text-black placeholder-gray-400 selection:bg-red-500 selection:text-white"
                            placeholder="كلمة المرور الخاصة بك" />
                        <span class="absolute inset-y-0 right-3 flex items-center text-gray-400">
                            <i class="fas fa-lock"></i>
                        </span>
                        <span class="absolute inset-y-0 left-3 flex items-center cursor-pointer text-gray-400" onclick="togglePassword()">
                            <i class="fas fa-eye" id="togglePasswordIcon"></i>
                        </span>
                    </div>
                </div>

                {{-- Submit --}}
                <div>
                    <button type="submit"
                        class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-3 rounded-lg text-lg transition">
                        دخول المشرف
                    </button>
                </div>
            </form>

            {{-- Optional Register or Info --}}
            <div class="mt-6 text-center text-sm text-white">
                <span>ليس لديك حساب مسؤول؟</span>
                <a href="{{ route('admin.register') }}" class="text-blue-500 hover:underline">اطلب الوصول</a>
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
</x-admin-layout>
