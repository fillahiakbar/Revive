<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <div class="mb-4 text-sm text-white">
            {{ __('قبل المتابعة، يُرجى تأكيد عنوان بريدك الإلكتروني بالنقر على الرابط الذي أرسلناه إليك للتو.
إن لم تصلك الرسالة، سنُرسل رسالة تحققٍ أخرى.') }}
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ __('أُرسِلَ رابط تأكيدٍ جديد إلى عنوان البريد الإلكتروني الذي أدرجته في إعدادات ملفك الشخصي. إن لم تجده في صندوق الوارد، تفقد مجلد الرسائل غير المرغوب فيها.') }}
            </div>
        @endif

        <div class="mt-4 flex items-center justify-between">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf

                <div>
                    <x-button type="submit">
                        {{ __('إعادة إرسال رسالة التحقّق') }}
                    </x-button>
                </div>
            </form>

            <div>
                <a
                    href="{{ route('profile.show') }}"
                    class="underline text-sm text-white hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    {{ __('تعديل الملف الشخصي') }}</a>

                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf

                    <button type="submit" class="underline text-sm text-white hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 ms-2">
                        {{ __('تسجيل الخروج') }}
                    </button>
                </form>
            </div>
        </div>
    </x-authentication-card>
</x-guest-layout>
