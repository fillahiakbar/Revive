<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <h1 class="text-lg font-bold text-white">Admin Login</h1>
        </x-slot>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('admin.login.submit') }}">
            @csrf

            <!-- Email Address -->
            <div class="mt-4">
                <x-label for="email" value="Email" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" required autofocus />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-label for="password" value="Password" />
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required />
            </div>

            <!-- Submit Button -->
            <div class="mt-4">
                <x-button class="w-full justify-center">
                    Login Admin
                </x-button>
            </div>

            <!-- Register Link -->
<div class="mt-4 text-center">
    <a href="{{ route('admin.register') }}" class="underline text-sm text-white hover:text-black">
        Belum punya akun admin? Daftar di sini
    </a>
</div>
        </form>
    </x-authentication-card>
</x-guest-layout>
