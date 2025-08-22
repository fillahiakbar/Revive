@props(['socialMedias'])
<footer class="text-white relative mt-10">
    {{-- Overlay content --}}
    <div class="relative z-10 max-w-7xl mx-auto px-6 py-10 flex flex-col items-center text-center md:flex-row md:justify-between md:text-right space-y-6 md:space-y-0">

        {{-- Logo & Center Text (always first on mobile) --}}
        <div class="flex flex-col items-center space-y-2 order-1 md:order-2">
            <img src="{{ asset('img/logo.png') }}" alt="logo" class="h-16 md:h-20">
            <p class="text-sm">جميع الحقوق محفوظة لفريق REVIVE</p>
        </div>

        {{-- Social Icons (middle on mobile) --}}
        <div class="flex space-x-6 rtl:space-x-reverse text-3xl md:text-4xl order-2 md:order-3">
            @foreach ($socialMedias as $media)
                <a href="{{ $media->url }}" class="hover:text-red-500" target="_blank" title="{{ $media->platform }}">
                    <i class="fab fa-{{ getSocialIcon($media->platform) }}"></i>
                </a>
            @endforeach
        </div>

        {{-- Link List (bottom on mobile) --}}
        <div class="space-y-2 text-sm order-3 md:order-1 pt-6 md:pt-0">
            <p><a href="/terms" class="hover:text-red-500">الشروط والأحكام</a></p>
            <p><a href="/privacy" class="hover:text-red-500">سياسة الخصوصية</a></p>
            <p><a href="/cookies" class="hover:text-red-500">سياسة ملفات تعريف الارتباط</a></p>
        </div>
    </div>
</footer>