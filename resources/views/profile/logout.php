<div class="bg-gray-800/70 rounded-xl px-6 py-8 shadow">
    <x-action-section>
        <x-slot name="content">
            {{-- Logout Button --}}
            <div class="flex items-center mt-6">
                <form method="POST" action="{{ route('logout') }}" x-data>
                    @csrf
                    <x-button type="submit" class="bg-red-600 hover:bg-red-700 focus:ring-red-500">
                        {{ __('تسجيل الخروج') }}
                    </x-button>
                </form>
            </div>
        </x-slot>
    </x-action-section>
</div>
