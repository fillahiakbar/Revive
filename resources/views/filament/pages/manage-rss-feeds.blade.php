<x-filament-panels::page>
    <div class="space-y-6">
        
        {{-- Search Bar --}}
        <div class="flex justify-between items-center">
            <x-filament::input.wrapper class="w-full max-w-sm">
                <x-slot name="prefix">
                    <x-filament::icon
                        icon="heroicon-m-magnifying-glass"
                        class="w-5 h-5 text-gray-400 dark:text-gray-500"
                    />
                </x-slot>
                
                <x-filament::input
                    type="search"
                    wire:model.live="searchQuery"
                    placeholder="Search by Anime Name or Batch Name..."
                />
            </x-filament::input.wrapper>
        </div>

        {{-- Table Container --}}
        <div class="fi-ta-content bg-white ring-1 ring-gray-950/5 rounded-xl shadow-sm dark:bg-gray-900 dark:ring-white/10 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="fi-ta-table w-full text-start divide-y divide-gray-200 dark:divide-white/5">
                    <thead class="bg-gray-50 dark:bg-white/5">
                        <tr>
                            <th class="fi-ta-header-cell px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6" style="width: 1%;">
                                <span class="group flex w-full items-center gap-x-1 whitespace-nowrap justify-start">
                                    <span class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">Poster</span>
                                </span>
                            </th>
                            <th class="fi-ta-header-cell px-3 py-3.5">
                                <span class="group flex w-full items-center gap-x-1 whitespace-nowrap justify-start">
                                    <span class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">Anime Name</span>
                                </span>
                            </th>
                            <th class="fi-ta-header-cell px-3 py-3.5">
                                <span class="group flex w-full items-center gap-x-1 whitespace-nowrap justify-start">
                                    <span class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">Batch Name</span>
                                </span>
                            </th>
                            <th class="fi-ta-header-cell px-3 py-3.5">
                                <span class="group flex w-full items-center gap-x-1 whitespace-nowrap justify-start">
                                    <span class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">Created At</span>
                                </span>
                            </th>
                            <th class="fi-ta-header-cell px-3 py-3.5 sm:last-of-type:pe-6" style="width: 1%;">
                                <span class="group flex w-full items-center gap-x-1 whitespace-nowrap justify-end">
                                    <span class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">Actions</span>
                                </span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-white/5">
                        @forelse ($this->filteredFeeds as $feed)
                            <tr class="fi-ta-row transition duration-75 hover:bg-gray-50 dark:hover:bg-white/5">
                                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                    <div class="fi-ta-col-wrp flex items-center p-3 sm:p-4">
                                        @if (!empty($feed['poster']))
                                            <img src="{{ asset('storage/' . $feed['poster']) }}" alt="Poster" class="h-12 w-16 object-cover rounded-md shadow-sm">
                                        @else
                                            <div class="h-12 w-16 bg-gray-200 dark:bg-gray-800 flex items-center justify-center rounded-md text-gray-400">
                                                <x-filament::icon icon="heroicon-o-photo" class="w-6 h-6" />
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                    <div class="fi-ta-col-wrp flex flex-col p-3 sm:p-4 text-sm text-gray-950 dark:text-white">
                                        {{ $feed['anime_name'] ?? '-' }}
                                    </div>
                                </td>
                                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                    <div class="fi-ta-col-wrp flex flex-col p-3 sm:p-4 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $feed['batch_name'] ?? '-' }}
                                    </div>
                                </td>
                                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                    <div class="fi-ta-col-wrp flex flex-col p-3 sm:p-4 text-sm text-gray-500 dark:text-gray-400">
                                        @php
                                            $createdAt = $feed['created_at'] ?? null;
                                            $date = null;
                                            if ($createdAt) {
                                                try {
                                                    $date = $createdAt instanceof \Carbon\Carbon ? $createdAt : \Carbon\Carbon::parse($createdAt);
                                                } catch (\Exception $e) {}
                                            }
                                        @endphp
                                        {{ $date ? $date->format('d M Y, H:i') : '-' }}
                                    </div>
                                </td>
                                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3 whitespace-nowrap text-end">
                                    <div class="fi-ta-actions flex shrink-0 items-center justify-end gap-x-3 p-3">
                                        <x-filament::button
                                            wire:click="mountAction('edit', { id: '{{ $feed['id'] ?? '' }}' })"
                                            color="warning"
                                            size="sm"
                                            icon="heroicon-s-pencil-square"
                                            labeled-from="sm"
                                        >
                                            Edit
                                        </x-filament::button>

                                        <x-filament::button
                                            wire:click="mountAction('delete', { id: '{{ $feed['id'] ?? '' }}' })"
                                            color="danger"
                                            size="sm"
                                            icon="heroicon-s-trash"
                                            labeled-from="sm"
                                        >
                                            Delete
                                        </x-filament::button>
                                        
                                        @if(!empty($feed['link']))
                                            <x-filament::button
                                                href="{{ $feed['link'] }}"
                                                tag="a"
                                                target="_blank"
                                                color="gray"
                                                size="sm"
                                                icon="heroicon-o-link"
                                            >
                                                Link
                                            </x-filament::button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center justify-center space-y-3">
                                        <div class="h-12 w-12 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                                            <x-filament::icon icon="heroicon-o-x-mark" class="w-6 h-6" />
                                        </div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">No RSS Feeds found.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- Render modals for actions --}}
    <x-filament-actions::modals />
</x-filament-panels::page>
