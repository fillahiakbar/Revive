<div class="min-h-screen flex items-center justify-center px-4 py-6">
    <div class="w-full max-w-3xl sm:px-20 px-6 py-12 rounded-2xl">
        <div class="flex flex-col items-center justify-center mb-6 text-center">
            {{ $logo }}
        </div>

        {{-- Isi Form --}}
        {{ $slot }}
    </div>
</div>
