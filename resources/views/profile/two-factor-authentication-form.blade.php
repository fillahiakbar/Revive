<div class="bg-gray-800/70 text-white rounded-xl px-6 py-8 shadow max-w-3xl mx-auto mt-24">
    <x-action-section>
        <x-slot name="title">
            <span class="text-gray-200">{{ __('توثيق العاملين') }}</span>
        </x-slot>

        <x-slot name="description">
            <span class="text-gray-300">{{ __('أضف طبقة أمان إضافية إلى حسابك باستخدام التوثيق بعاملين.') }}</span>
        </x-slot>

        <x-slot name="content">
            <h3 class="text-lg font-medium text-white">
                @if ($this->enabled)
                    @if ($showingConfirmation)
                        {{ __('أكمل تفعيل التوثيق بعاملين.') }}
                    @else
                        {{ __('تم تفعيل التوثيق بعاملين.') }}
                    @endif
                @else
                    {{ __('لم يتم تفعيل التوثيق بعاملين.') }}
                @endif
            </h3>

            <div class="mt-3 max-w-xl text-sm text-gray-300">
                <p>
                    {{ __('عند تفعيل التوثيق بعاملين، سيُطلب منك إدخال رمز آمن وعشوائي يتم إنشاؤه من خلال تطبيق المصادقة على هاتفك.') }}
                </p>
            </div>

            @if ($this->enabled)
                @if ($showingQrCode)
                    <div class="mt-4 max-w-xl text-sm text-gray-300">
                        <p class="font-semibold">
                            @if ($showingConfirmation)
                                {{ __('لإكمال التفعيل، امسح رمز QR التالي أو أدخل مفتاح الإعداد، ثم أدخل رمز التحقق.') }}
                            @else
                                {{ __('تم تفعيل التوثيق بعاملين. امسح رمز QR التالي أو أدخل مفتاح الإعداد.') }}
                            @endif
                        </p>
                    </div>

                    <div class="mt-4 p-2 inline-block bg-gray-700 rounded-lg">
                        {!! $this->user->twoFactorQrCodeSvg() !!}
                    </div>

                    <div class="mt-4 max-w-xl text-sm text-gray-200">
                        <p class="font-semibold">
                            {{ __('مفتاح الإعداد') }}: <span class="font-mono">{{ decrypt($this->user->two_factor_secret) }}</span>
                        </p>
                    </div>

                    @if ($showingConfirmation)
                        <div class="mt-4">
                            <x-label for="code" class="text-gray-200" value="{{ __('الرمز') }}" />
                            <x-input
                                id="code"
                                type="text"
                                name="code"
                                class="block mt-1 w-1/2 bg-gray-900 text-gray-100 border border-gray-600"
                                inputmode="numeric"
                                autofocus
                                autocomplete="one-time-code"
                                wire:model="code"
                                wire:keydown.enter="confirmTwoFactorAuthentication"
                            />
                            <x-input-error for="code" class="mt-2 text-red-400" />
                        </div>
                    @endif
                @endif

                @if ($showingRecoveryCodes)
                    <div class="mt-4 max-w-xl text-sm text-gray-200">
                        <p class="font-semibold">
                            {{ __('قم بحفظ رموز الاسترداد في مدير كلمات المرور الخاص بك. يمكنك استخدامها لاستعادة الوصول إلى حسابك.') }}
                        </p>
                    </div>

                    <div class="grid gap-1 max-w-xl mt-4 px-4 py-4 font-mono text-sm bg-gray-900 rounded-lg text-white">
                        @foreach (json_decode(decrypt($this->user->two_factor_recovery_codes), true) as $code)
                            <div>{{ $code }}</div>
                        @endforeach
                    </div>
                @endif
            @endif

            <div class="mt-5">
                @if (! $this->enabled)
                    <x-confirms-password wire:then="enableTwoFactorAuthentication">
                        <x-button type="button" wire:loading.attr="disabled">
                            {{ __('تفعيل') }}
                        </x-button>
                    </x-confirms-password>
                @else
                    @if ($showingRecoveryCodes)
                        <x-confirms-password wire:then="regenerateRecoveryCodes">
                            <x-secondary-button class="me-3">
                                {{ __('توليد رموز جديدة') }}
                            </x-secondary-button>
                        </x-confirms-password>
                    @elseif ($showingConfirmation)
                        <x-confirms-password wire:then="confirmTwoFactorAuthentication">
                            <x-button type="button" class="me-3" wire:loading.attr="disabled">
                                {{ __('تأكيد') }}
                            </x-button>
                        </x-confirms-password>
                    @else
                        <x-confirms-password wire:then="showRecoveryCodes">
                            <x-secondary-button class="me-3">
                                {{ __('عرض رموز الاسترداد') }}
                            </x-secondary-button>
                        </x-confirms-password>
                    @endif

                    @if ($showingConfirmation)
                        <x-confirms-password wire:then="disableTwoFactorAuthentication">
                            <x-secondary-button wire:loading.attr="disabled">
                                {{ __('إلغاء') }}
                            </x-secondary-button>
                        </x-confirms-password>
                    @else
                        <x-confirms-password wire:then="disableTwoFactorAuthentication">
                            <x-danger-button wire:loading.attr="disabled">
                                {{ __('تعطيل') }}
                            </x-danger-button>
                        </x-confirms-password>
                    @endif
                @endif
            </div>
        </x-slot>
    </x-action-section>
</div>
