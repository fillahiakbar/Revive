<x-app-layout>
    {{-- Header --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('الملف الشخصي') }}
        </h2>
    </x-slot>

    {{-- Wrapper with dark translucent background --}}
    <div class="min-h-screen">
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">

            {{-- Update Profile Information --}}
            @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                <div class="mb-10 rounded-xl px-6 py-8 shadow">
                    @livewire('profile.update-profile-information-form')
                </div>

                <x-section-border class="border-gray-700" />
            @endif

            {{-- Update Password --}}
            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                <div class="mt-10 sm:mt-0  rounded-xl px-6 py-8 shadow">
                    @livewire('profile.update-password-form')
                </div>

                <x-section-border class="border-gray-700" />
            @endif

            {{-- Two Factor Authentication --}}
            @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                <div class="mt-10 sm:mt-0 rounded-xl px-6 py-8 shadow">
                    @livewire('profile.two-factor-authentication-form')
                </div>

                <x-section-border class="border-gray-700" />
            @endif

            {{-- Browser Sessions --}}
            <div class="mt-10 sm:mt-0  rounded-xl px-6 py-8 shadow">
                @livewire('profile.logout-other-browser-sessions-form')
            </div>

            {{-- Account Deletion --}}
            @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                <x-section-border class="border-gray-700" />

                <div class="mt-10 sm:mt-0 rounded-xl px-6 py-8 shadow">
                    @livewire('profile.delete-user-form')
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
