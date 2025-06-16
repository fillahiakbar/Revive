<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <h1 class="text-lg font-bold text-white">Admin Register</h1>
        </x-slot>

        <form method="POST" action="{{ route('admin.register.submit') }}">
            @csrf

            <!-- Name -->
            <div>
                <x-label for="name" value="Name" />
                <x-input id="name" class="block mt-1 w-full" type="text" name="name" required autofocus />
            </div>

            <!-- Email -->
            <div class="mt-4">
                <x-label for="email" value="Email" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" required />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-label for="password" value="Password" />
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-label for="password_confirmation" value="Confirm Password" />
                <x-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required />
            </div>

            <div class="mt-4">
                <x-button>
                    Register Admin
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
