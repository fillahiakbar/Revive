<x-filament-panels::page>
    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                📡 إنشاء موجز RSS مخصص
            </h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                أضف عناصر أنمي جديدة إلى موجز RSS. سيتم إضافة جميع العناصر إلى نفس ملف RSS.
            </p>
            
            <form wire:submit="generate">
                {{ $this->form }}
                
                <div class="mt-6 flex justify-between items-center">
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        💡 <strong>نصيحة:</strong> سيكون رابط RSS متاحًا على:
                        <code class="bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded text-xs">
                            {{ url('rss/custom-feeds.xml') }}
                        </code>
                    </div>
                </div>
            </form>
        </div>

        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h4 class="text-sm font-medium text-blue-800 dark:text-blue-200">
                        كيف يعمل موجز RSS
                    </h4>
                    <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                        <ul class="list-disc list-inside space-y-1">
                            <li>يتم إضافة كل عنصر جديد إلى <strong>أعلى</strong> موجز RSS</li>
                            <li>يحتوي ملف RSS واحد على جميع عناصر الأنمي</li>
                            <li>العناصر الأحدث تظهر أولًا في قارئ RSS</li>
                            <li>يمكن الوصول إلى موجز RSS من خلال الرابط:
                                <code>{{ url('rss/custom-feeds.xml') }}</code>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
