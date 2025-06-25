<div class="bg-gray-800/70 text-white rounded-xl px-6 py-8 shadow max-w-3xl mx-auto mt-24">
    <x-form-section submit="updatePassword">
        <x-slot name="title">
            <span class="text-gray-200">{{ __('تحديث كلمة المرور') }}</span>
        </x-slot>

        <x-slot name="description">
            <span class="text-gray-300">{{ __('تأكد من أن حسابك يستخدم كلمة مرور طويلة وعشوائية للبقاء آمناً.') }}</span>
        </x-slot>

        <x-slot name="form">
            {{-- كلمة المرور الحالية --}}
            <div class="col-span-6 sm:col-span-4">
                <x-label for="current_password" class="text-gray-200" value="{{ __('كلمة المرور الحالية') }}" />
                <x-input
                    id="current_password"
                    type="password"
                    class="mt-1 block w-full bg-gray-900 text-gray-100 border border-gray-600"
                    wire:model="state.current_password"
                    autocomplete="current-password"
                />
                <x-input-error for="current_password" class="mt-2 text-red-400" />
            </div>

            {{-- كلمة المرور الجديدة --}}
            <div class="col-span-6 sm:col-span-4">
                <x-label for="password" class="text-gray-200" value="{{ __('كلمة المرور الجديدة') }}" />
                <x-input
                    id="password"
                    type="password"
                    class="mt-1 block w-full bg-gray-900 text-gray-100 border border-gray-600"
                    wire:model="state.password"
                    autocomplete="new-password"
                />
                <x-input-error for="password" class="mt-2 text-red-400" />
            </div>

            {{-- تأكيد كلمة المرور --}}
            <div class="col-span-6 sm:col-span-4">
                <x-label for="password_confirmation" class="text-gray-200" value="{{ __('تأكيد كلمة المرور') }}" />
                <x-input
                    id="password_confirmation"
                    type="password"
                    class="mt-1 block w-full bg-gray-900 text-gray-100 border border-gray-600"
                    wire:model="state.password_confirmation"
                    autocomplete="new-password"
                />
                <x-input-error for="password_confirmation" class="mt-2 text-red-400" />
            </div>
        </x-slot>

        <x-slot name="actions">
            <x-action-message class="me-3 text-green-400" on="saved">
                {{ __('تم الحفظ.') }}
            </x-action-message>

            <x-button wire:loading.attr="disabled">
                {{ __('حفظ') }}
            </x-button>
        </x-slot>
    </x-form-section>
</div>
