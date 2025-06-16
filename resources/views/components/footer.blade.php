<footer class="text-white relative mt-10">

    {{-- Overlay content --}}
    <div class="relative z-10 max-w-7xl mx-auto px-6 py-10 flex flex-col md:flex-row md:justify-between items-center md:items-start text-center md:text-right space-y-6 md:space-y-0">

        {{-- Link List (kanan dalam RTL) --}}
        <div class="space-y-2 text-sm text-right md:order-1 pt-10">
            <p><a href="#" class="hover:text-red-500">الشروط والأحكام</a></p>
            <p><a href="#" class="hover:text-red-500">سياسة الخصوصية</a></p>
            <p><a href="#" class="hover:text-red-500">سياسة ملفات تعريف الارتباط</a></p>
        </div>

        {{-- Logo & Center Text --}}
        <div class="flex flex-col items-center space-y-2 md:order-2">
            <img src="{{ asset('img/logo.png') }}" alt="logo" class="h-20">
            <p class="text-sm">جميع الحقوق محفوظة ⌊ REVIVE </p>
        </div>

{{-- Social Icons (sekarang di kiri) --}}
<div class="flex space-x-6 rtl:space-x-reverse text-4xl md:order-3 pt-20">
    <a href="#" class="hover:text-red-500"><i class="fab fa-telegram-plane"></i></a>
    <a href="#" class="hover:text-red-500"><i class="fab fa-instagram"></i></a>
    <a href="#" class="hover:text-red-500"><i class="fab fa-x-twitter"></i></a>
    <a href="#" class="hover:text-red-500"><i class="fab fa-discord"></i></a>
</div>

    </div>
</footer>
