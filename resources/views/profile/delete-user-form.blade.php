<div class="bg-gray-800/70 rounded-xl px-6 py-8 shadow">
    <x-action-section>
        {{-- Judul --}}
        <x-slot name="title">
            <span class="text-gray-200">{{ __('حذف الحساب') }}</span>
        </x-slot>

        {{-- Deskripsi --}}
        <x-slot name="description">
            <span class="text-gray-300">{{ __('احذف حسابك بشكل دائم.') }}</span>
        </x-slot>

        {{-- Konten --}}
        <x-slot name="content">
            <div class="max-w-xl text-sm text-gray-300">
                {{ __('عند حذف الحساب، سيتم حذف جميع البيانات بشكل دائم. تأكد من تنزيل أي بيانات تريد الاحتفاظ بها قبل المتابعة.') }}
            </div>

            <div class="mt-5">
                <x-danger-button wire:click="confirmUserDeletion" wire:loading.attr="disabled">
                    {{ __('حذف الحساب') }}
                </x-danger-button>
            </div>

            {{-- Dialog Konfirmasi --}}
            <x-dialog-modal wire:model.live="confirmingUserDeletion">
                <x-slot name="title">
                    <span class="text-gray-200">{{ __('تأكيد حذف الحساب') }}</span>
                </x-slot>

                <x-slot name="content">
                    <p class="text-gray-300">
                        {{ __('هل أنت متأكد أنك تريد حذف حسابك؟ هذا الإجراء لا يمكن التراجع عنه. يرجى إدخال كلمة المرور الخاصة بك لتأكيد الحذف النهائي.') }}
                    </p>

                    <div class="mt-4" x-data="{}" x-on:confirming-delete-user.window="setTimeout(() => $refs.password.focus(), 250)">
                        <x-input
                            type="password"
                            class="mt-1 block w-3/4 bg-gray-900 text-gray-100 border border-gray-600"
                            autocomplete="current-password"
                            placeholder="{{ __('كلمة المرور') }}"
                            x-ref="password"
                            wire:model="password"
                            wire:keydown.enter="deleteUser"
                        />
                        <x-input-error for="password" class="mt-2 text-red-400" />
                    </div>
                </x-slot>

                <x-slot name="footer">
                    <x-secondary-button wire:click="$toggle('confirmingUserDeletion')" wire:loading.attr="disabled">
                        {{ __('إلغاء') }}
                    </x-secondary-button>

                    <x-danger-button class="ms-3" wire:click="deleteUser" wire:loading.attr="disabled">
                        {{ __('نعم، احذف الحساب') }}
                    </x-danger-button>
                </x-slot>
            </x-dialog-modal>
        </x-slot>
    </x-action-section>
</div>
