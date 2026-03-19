<x-filament::page>
    <form wire:submit.prevent="save">
        {{ $this->form }}
        <div style="margin-top: 2rem;">
            <x-filament::button type="submit">
                حفظ
            </x-filament::button>
        </div>
    </form>
</x-filament::page>
