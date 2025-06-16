<div class="min-h-screen flex items-center justify-center px-4 py-6">
    <div class="w-full max-w-lg sm:max-w-lg rounded-2xl px-20 py-12">
        {{-- Logo & Header --}}
        <div class="flex flex-col items-center justify-center mb-6 text-center">
            {{ $logo }}
        </div>

        {{-- Isi Form --}}
        {{ $slot }}
    </div>
</div>
