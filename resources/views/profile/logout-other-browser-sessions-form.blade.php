<div class="bg-gray-800/70 rounded-xl px-6 py-8 shadow">
    <x-action-section>
        <x-slot name="title">
            <span class="text-gray-200">{{ __('الجلسات النشطة') }}</span>
        </x-slot>

        <x-slot name="description">
            <span class="text-gray-300">{{ __('قم بإدارة الجلسات النشطة وتسجيل الخروج من الحساب إن لزم.') }}</span>
        </x-slot>

        <x-slot name="content">
            <div class="max-w-xl text-sm text-gray-300">
                {{ __('فيما يلي قائمة بجلساتك الحديثة. إذا لاحظت أي شيء غير مألوف، يُنصح بتسجيل الخروج أو تغيير كلمة المرور.') }}
            </div>

            @if (count($this->sessions) > 0)
                <div class="mt-5 space-y-6">
                    @foreach ($this->sessions as $session)
                        <div class="flex items-center bg-gray-900 rounded-lg p-4 shadow-sm">
                            {{-- Device Icon --}}
                            <div>
                                @if ($session->agent->isDesktop())
                                    <svg class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M9 17.25v1.007..." />
                                    </svg>
                                @else
                                    <svg class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M10.5 1.5H8.25..." />
                                    </svg>
                                @endif
                            </div>

                            {{-- Session Info --}}
                            <div class="ms-3">
                                <div class="text-sm text-gray-100 font-medium">
                                    {{ $session->agent->platform() ?? __('غير معروف') }} - {{ $session->agent->browser() ?? __('غير معروف') }}
                                </div>

                                <div class="text-xs text-gray-400">
                                    {{ $session->ip_address }},
                                    @if ($session->is_current_device)
                                        <span class="text-green-400 font-semibold">{{ __('هذا الجهاز') }}</span>
                                    @else
                                        {{ __('آخر نشاط') }}: {{ $session->last_active }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Logout Button --}}
            <div class="flex items-center mt-6">
                <form method="POST" action="{{ route('logout') }}" x-data>
                    @csrf
                    <x-button type="submit" class="bg-red-600 hover:bg-red-700 focus:ring-red-500">
                        {{ __('تسجيل الخروج من كل الجلسات') }}
                    </x-button>
                </form>
            </div>
        </x-slot>
    </x-action-section>
</div>
