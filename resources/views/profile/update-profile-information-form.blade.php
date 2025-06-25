<div class="bg-gray-800/70 text-white rounded-xl px-6 py-8 shadow max-w-3xl mx-auto mt-24">
    <x-form-section submit="updateProfileInformation">
        <x-slot name="title">
            <span class="text-gray-200">{{ __('معلومات الحساب') }}</span>
        </x-slot>

        <x-slot name="description">
            <span class="text-gray-300">{{ __('قم بتحديث معلومات حسابك وعنوان البريد الإلكتروني.') }}</span>
        </x-slot>

        <x-slot name="form">
            {{-- صورة الملف الشخصي --}}
            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                <div x-data="{photoName: null, photoPreview: null}" class="col-span-6 sm:col-span-4">
                    <input type="file" id="photo" class="hidden"
                        wire:model.live="photo"
                        x-ref="photo"
                        x-on:change="
                            photoName = $refs.photo.files[0].name;
                            const reader = new FileReader();
                            reader.onload = (e) => {
                                photoPreview = e.target.result;
                            };
                            reader.readAsDataURL($refs.photo.files[0]);
                        " />

                    <x-label for="photo" value="{{ __('الصورة') }}" class="text-gray-200" />

                    {{-- الصورة الحالية --}}
                    <div class="mt-2" x-show="! photoPreview">
                        <img src="{{ $this->user->profile_photo_url }}" alt="{{ $this->user->name }}" class="rounded-full size-20 object-cover">
                    </div>

                    {{-- معاينة جديدة --}}
                    <div class="mt-2" x-show="photoPreview" style="display: none;">
                        <span class="block rounded-full size-20 bg-cover bg-no-repeat bg-center"
                              x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                        </span>
                    </div>

                    <x-secondary-button class="mt-2 me-2" type="button" x-on:click.prevent="$refs.photo.click()">
                        {{ __('اختر صورة جديدة') }}
                    </x-secondary-button>

                    @if ($this->user->profile_photo_path)
                        <x-secondary-button type="button" class="mt-2" wire:click="deleteProfilePhoto">
                            {{ __('إزالة الصورة') }}
                        </x-secondary-button>
                    @endif

                    <x-input-error for="photo" class="mt-2 text-red-400" />
                </div>
            @endif

            {{-- الاسم --}}
            <div class="col-span-6 sm:col-span-4">
                <x-label for="name" value="{{ __('الاسم') }}" class="text-white" />
                <x-input id="name" type="text"
                         class="mt-1 block w-full bg-gray-900 text-gray-100 border border-gray-600"
                         wire:model="state.name" required autocomplete="name" />
                <x-input-error for="name" class="mt-2 text-red-400" />
            </div>

            {{-- البريد الإلكتروني --}}
            <div class="col-span-6 sm:col-span-4">
                <x-label for="email" value="{{ __('البريد الإلكتروني') }}" class="text-white" />
                <x-input id="email" type="email"
                         class="mt-1 block w-full bg-gray-900 text-gray-100 border border-gray-600"
                         wire:model="state.email" required autocomplete="username" />
                <x-input-error for="email" class="mt-2 text-red-400" />

                @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::emailVerification()) && ! $this->user->hasVerifiedEmail())
                    <p class="text-sm mt-2 text-gray-300">
                        {{ __('عنوان بريدك الإلكتروني غير مؤكد.') }}
                        <button type="button"
                                class="underline text-sm text-white hover:text-gray-100 focus:outline-none"
                                wire:click.prevent="sendEmailVerification">
                            {{ __('انقر هنا لإعادة إرسال رسالة التحقق.') }}
                        </button>
                    </p>

                    @if ($this->verificationLinkSent)
                        <p class="mt-2 font-medium text-sm text-green-400">
                            {{ __('تم إرسال رابط تحقق جديد إلى بريدك الإلكتروني.') }}
                        </p>
                    @endif
                @endif
            </div>
        </x-slot>

        <x-slot name="actions">
            <x-action-message class="me-3 text-green-400" on="saved">
                {{ __('تم الحفظ.') }}
            </x-action-message>

            <x-button wire:loading.attr="disabled" wire:target="photo">
                {{ __('حفظ') }}
            </x-button>
        </x-slot>
    </x-form-section>
</div>
