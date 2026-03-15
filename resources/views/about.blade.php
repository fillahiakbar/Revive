<x-app-layout>

    <div class="relative text-white h-screen flex items-center justify-between overflow-hidden">
        <!-- Phoenix image positioned in the left corner -->
        <div class="absolute left-0 top-0 w-[75%] h-[150%] z-0 overflow-hidden">
            <img src="{{ asset('/img/about.png') }}" alt="Phoenix Background"
                class="w-full h-full object-cover object-left scale-110 -translate-x-4">
        </div>

        <!-- Content positioned on the right side with dark background -->
        <div class="relative z-10 w-full h-screen flex items-center justify-center px-12">
            <div class="text-right ml-auto max-w-2xl">
                <h1 class="text-7xl font-extrabold mb-6">عن الفريق</h1>
                <p class="text-3xl font-medium">نترجم الذكريات... ونخلّدها</p>
            </div>
        </div>
    </div>

    <!-- Main Content Section - Dark Blue Background -->
    <div class="min-h-screen">
        <div class="container mx-auto px-8 py-20">
            <div class="w-full h-px bg-gradient-to-r from-transparent via-white to-transparent opacity-50 mb-8"></div>

            <!-- Section 1 -->
            <div class="flex flex-col lg:flex-row gap-8 items-start">
                <div class="text-white text-right mb-16 pr-24">
                    <div class="relative">
                        <div class="w-32 h-1 bg-red-500 mb-4 ml-auto"></div>
                        <h2 class="text-5xl font-bold mb-8 text-white">من نحن؟</h2>
                        <div class="space-y-6 text-lg leading-relaxed"></div>
                    </div>
                </div>
                <div class="w-full lg:w-64 flex-shrink-0"></div>
                <div class="flex-1">
                    <div class="relative">
                        <div class="mb-8">
                            <div class="flex items-start justify-between ml-20 mb-4">
                                <div class="space-y-6 text-2xl leading-relaxed">
                                    <p>
                                        نحن <span class="text-white font-semibold">REVIVE</span> - فريق متخصص في التصوير
                                        والإنتاج والتصميم والتقنيات المبتكرة. نقدم خدمات شاملة في مجال الإنتاج المرئي
                                        والصوتي.
                                    </p>
                                    <p>
                                        نحن لا نقوم بتوثيق اللحظات فحسب، بل نحييها من جديد. وسنحرص وننجز المهام الخاصة.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 2 -->
            <div class="w-full h-px bg-gradient-to-r from-transparent via-white to-transparent opacity-50 mb-8"></div>
            <div class="flex flex-col lg:flex-row gap-8 items-start">
                <div class="text-white text-right mb-16 pr-24">
                    <div class="relative">
                        <div class="w-32 h-1 bg-red-500 mb-4 ml-auto"></div>
                        <h2 class="text-5xl font-bold mb-8 text-white">رؤيتنا</h2>
                        <div class="space-y-6 text-lg leading-relaxed"></div>
                    </div>
                </div>
                <div class="w-full lg:w-64 flex-shrink-0"></div>
                <div class="flex-1">
                    <div class="relative">
                        <div class="mb-8">
                            <div class="flex items-start justify-between ml-20 mb-4">
                                <div class="space-y-6 text-2xl leading-relaxed rtl:text-right text-white/90 pr-10">
                                    <p>
                                        أن نكون الجسر الذي يصل بين الماضي والحاضر، بين اليابان والعالم العربي، بين
                                        المجهول والعاشق الباحث عن شيءٍ مُختلف.
                                    </p>
                                    <p>
                                        نُؤمن أنَّ في كُلِّ أنمي منسي كنزٌ دفين، وأنَّ وظيفتنا اكتشافه وترميمه وتقديمه
                                        في أبهى صورة.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 3 -->
            <div class="w-full h-px bg-gradient-to-r from-transparent via-white to-transparent opacity-50 mb-8"></div>
            <div class="flex flex-col lg:flex-row gap-8 items-start">
                <div class="text-white text-right mb-16 pr-24">
                    <div class="relative">
                        <div class="w-32 h-1 bg-red-500 mb-4 ml-auto"></div>
                        <h2 class="text-5xl font-bold mb-8 text-white">أهدافنا</h2>
                        <div class="space-y-6 text-lg leading-relaxed"></div>
                    </div>
                </div>
                <div class="w-full lg:w-64 flex-shrink-0"></div>
                <div class="flex-1">
                    <div class="relative">
                        <div class="mb-8">
                            <div class="flex items-start justify-between ml-20 mb-4">
                                <div class="text-2xl leading-relaxed">
                                    <ul class="space-y-6 list-disc list-inside rtl:pr-4 rtl:text-right text-white/90">
                                        <li>إحياء اﻷعمال الكلاسيكية التي لم يُنصفها الزمن.</li>
                                        <li>الحفاظ على جوهر العمل وأصالته دون تحريفٍ أو اجتزاء.</li>
                                        <li>إعادة تقديم العمل بجودةٍ بصريةٍ ولغويةٍ تعكس الاحترام للفن وللمُشاهد.</li>
                                        <li>أرشفةٌ ذكيةٌ تُسهِّل الوصول وتخلد هذه الأعمال للأجيال القادمة.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 4 -->
            <div class="w-full h-px bg-gradient-to-r from-transparent via-white to-transparent opacity-50 mb-8"></div>
            <div class="flex flex-col lg:flex-row gap-8 items-start">
                <div class="text-white text-right mb-16 pr-24">
                    <div class="relative">
                        <div class="w-32 h-1 bg-red-500 mb-4 ml-auto"></div>
                        <h2 class="text-5xl font-bold mb-8 text-white">فريقنا</h2>
                        <div class="space-y-6 text-lg leading-relaxed"></div>
                    </div>
                </div>
                <div class="w-full lg:w-64 flex-shrink-0"></div>
                <div class="flex-1">
                    <div class="relative">
                        <div class="mb-8">
                            <div class="flex items-start justify-between ml-20 mb-4">
                                <div class="space-y-6 text-2xl leading-relaxed pr-10">
                                    <p>
                                        في الظلِّ نعمل، لا نبحث عن شهرة بل عن أثر. نحن مُترجمون ومُحرِّرون ومُدقِّقون،
                                        عُشَّاقٌ قبل أن نكون مُتطوِّعين. يجمعنا شغفٌ واحد: ألا يُنسى أيُّ عملٍ يستحقُّ
                                        أن يُروى.
                                    </p>
                                    <p></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="max-w-7xl mx-auto px-4 py-20 text-white" x-data="{
        donateOpen: false,
        selectedMethod: null,
        activeCoin: null,
    
        selectMethod(method) {
            if (typeof method.options === 'string') {
                try { method.options = JSON.parse(method.options); } catch (e) {}
            }
            this.selectedMethod = method;
            this.activeCoin = null;
            if (method.type === 'crypto' && method.options && method.options.coins && method.options.coins.length > 0) {
                this.activeCoin = method.options.coins[0];
            }
        },
    
        copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                alert('تم النسخ بنجاح! / Copied successfully!');
            });
        }
    }">

        <style>
            .about-neon-border {
                box-shadow: 0 0 5px theme('colors.red.500'), 0 0 10px theme('colors.red.500');
            }

            .about-hover-neon:hover {
                box-shadow: 0 0 10px theme('colors.red.500'), 0 0 20px theme('colors.red.500');
            }

            .about-glass-panel {
                background: rgba(17, 24, 39, 0.7);
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.1);
            }
        </style>

        <div class="mt-8">
            <div class="bg-white/30 backdrop-blur-lg border border-white/20 overflow-hidden">

                {{-- Desktop --}}
                <div class="hidden md:grid" style="grid-template-columns: 1fr 1px 1fr;">

                    {{-- Kolom Kiri --}}
                    <div class="p-10 text-center flex flex-col items-center justify-center gap-6">
                        <h2 class="text-4xl font-bold text-white">تواصل معنا</h2>
                        <p class="text-white/60 text-sm">تابعنا على منصات التواصل الاجتماعي</p>

                        <div class="flex justify-center items-center gap-8 text-4xl rtl:flex-row-reverse flex-wrap">
                            @foreach ($socialMedias as $media)
                                <a href="{{ $media->url }}"
                                    class="text-white/80 hover:text-red-500 transition-colors duration-300"
                                    target="_blank" title="{{ $media->platform }}">
                                    <i class="fab fa-{{ getSocialIcon($media->platform) }}"></i>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    {{-- Separator Vertikal --}}
                    <div class="w-px bg-gradient-to-b from-transparent via-white to-transparent opacity-50 my-6"></div>

                    {{-- Kolom Kanan --}}
                    <div class="p-10 text-center flex flex-col items-center justify-center gap-4">
                        <p class="text-white text-lg">دعمكم يعني لنا الكثير!</p>

                        <button @click="donateOpen = true"
                            class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white font-bold px-6 py-2 rounded-lg transition-all duration-300 hover:scale-105 shadow-lg shadow-red-900/30 text-base cursor-pointer">
                            <span>ادعمنا</span>
                        </button>
                    </div>

                </div>

                {{-- Mobile --}}
                <div class="flex flex-col md:hidden">

                    <div class="p-8 text-center flex flex-col items-center justify-center gap-6">
                        <h2 class="text-4xl font-bold text-white">تواصل معنا</h2>
                        <p class="text-white/60 text-sm">تابعنا على منصات التواصل الاجتماعي</p>

                        <div class="flex justify-center items-center gap-8 text-4xl flex-wrap">
                            @foreach ($socialMedias as $media)
                                <a href="{{ $media->url }}"
                                    class="text-white/80 hover:text-red-500 transition-colors duration-300"
                                    target="_blank" title="{{ $media->platform }}">
                                    <i class="fab fa-{{ getSocialIcon($media->platform) }}"></i>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <div class="p-8 text-center flex flex-col items-center justify-center gap-4">
                        <p class="text-white text-lg">دعمكم يعني لنا الكثير!</p>

                        <button @click="donateOpen = true"
                            class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white font-bold px-6 py-2 rounded-lg transition-all duration-300 hover:scale-105 shadow-lg shadow-red-900/30 text-base cursor-pointer">
                            <span>ادعمنا</span>
                        </button>
                    </div>

                </div>

            </div>
        </div>


        <template x-teleport="body">
            <div x-show="donateOpen" style="display: none;" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-[100] flex items-center justify-center bg-black/80 backdrop-blur-sm p-4">

                <div @click.away="donateOpen = false; selectedMethod = null"
                    :class="selectedMethod ? 'max-w-5xl h-[90vh] md:h-[80vh]' : 'max-w-2xl h-auto'"
                    class="z-999 about-glass-panel w-full rounded-2xl shadow-2xl overflow-hidden relative text-white transition-all duration-300">

                    {{-- Header --}}
                    <div x-show="!selectedMethod"
                        class="p-6 border-b border-red-900/40 flex justify-between items-center bg-gradient-to-r from-black via-red-950/30 to-black">
                        <h2 class="text-2xl font-bold text-red-600 drop-shadow-md">ادعمنا</h2>
                        <button @click="donateOpen = false; selectedMethod = null"
                            class="text-gray-400 hover:text-white transition transform hover:rotate-90">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    {{-- Body --}}
                    <div :class="selectedMethod ? 'p-0 h-full' : 'p-6'">
                        {{-- Method Selection Grid --}}
                        <div x-show="!selectedMethod" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach ($paymentMethods as $method)
                                <div @click="@if ($method->type === 'paypal') window.open('{{ $method->content }}', '_blank') @else selectMethod({{ json_encode($method) }}) @endif"
                                    class="cursor-pointer group relative p-6 rounded-xl bg-black/60 border border-red-900/30 hover:border-red-600 transition-all duration-300 about-hover-neon flex flex-col items-center gap-4 text-center">
                                    @if ($method->icon)
                                        <img src="/storage/{{ $method->icon }}" referrerpolicy="no-referrer"
                                            class="w-16 h-16 object-contain drop-shadow-lg group-hover:scale-110 transition-transform duration-300"
                                            alt="{{ $method->name }}">
                                    @else
                                        <div
                                            class="w-16 h-16 rounded-full bg-gray-700 flex items-center justify-center text-2xl group-hover:bg-red-900/50 transition-colors">
                                            🎁</div>
                                    @endif
                                    <h3 class="font-bold text-lg group-hover:text-red-400 transition-colors">
                                        {{ $method->name }}</h3>
                                    @if ($method->type === 'link')
                                        <span class="text-xs text-gray-400 flex items-center gap-1">
                                            فتح الرابط <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                                </path>
                                            </svg>
                                        </span>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        {{-- Method Detail View --}}
                        <template x-if="selectedMethod">
                            <div class="animate-fadeIn w-full h-full flex flex-col md:flex-row gap-0">
                                <button @click="selectedMethod = null"
                                    class="absolute top-4 left-4 z-50 md:hidden p-2 bg-black/50 rounded-full text-white hover:bg-red-600 transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>

                                {{-- Sidebar --}}
                                <div
                                    class="w-full md:w-1/3 bg-black/40 border-b md:border-b-0 md:border-l border-white/5 p-6 md:p-8 flex flex-col items-center justify-center text-center relative overflow-hidden shrink-0">
                                    <div class="absolute inset-0 bg-gradient-to-b from-red-900/10 to-transparent">
                                    </div>
                                    <div class="relative z-10 w-full">
                                        <template x-if="selectedMethod.icon">
                                            <img :src="'/storage/' + selectedMethod.icon" referrerpolicy="no-referrer"
                                                class="w-16 h-16 md:w-24 md:h-24 object-contain mx-auto mb-4 md:mb-6 drop-shadow-2xl">
                                        </template>
                                        <h2 class="text-xl md:text-2xl font-bold text-white mb-2"
                                            x-text="selectedMethod.name"></h2>
                                        <p class="text-xs md:text-sm text-gray-400 mb-6 px-4"
                                            x-text="selectedMethod.type === 'crypto' ? 'حول العملات الرقمية بأمان وسرعة' : (selectedMethod.type === 'stc_pay' ? 'الدفع عبر STC Pay' : 'الدفع المحلي المباشر')">
                                        </p>
                                        <template
                                            x-if="selectedMethod.type === 'crypto' && selectedMethod.options && selectedMethod.options.coins && selectedMethod.options.coins.length > 0">
                                            <div class="grid grid-cols-2 gap-2 w-full">
                                                <template x-for="coin in selectedMethod.options.coins">
                                                    <button @click="activeCoin = coin"
                                                        :class="activeCoin === coin ?
                                                            'bg-red-600 text-white border-red-500 shadow-lg shadow-red-900/50' :
                                                            'bg-white/5 text-gray-400 border-white/10 hover:bg-white/10'"
                                                        class="py-2 px-3 md:py-3 md:px-4 rounded-xl border font-bold text-xs md:text-sm transition-all duration-300 flex items-center justify-center gap-2">
                                                        <span x-text="coin.coin_name"></span>
                                                    </button>
                                                </template>
                                            </div>
                                        </template>
                                        <template
                                            x-if="selectedMethod.type === 'crypto' && (!selectedMethod.options || !selectedMethod.options.coins || selectedMethod.options.coins.length === 0)">
                                            <div
                                                class="text-center p-4 bg-red-900/20 rounded-xl border border-red-500/30">
                                                <p class="text-red-400 text-xs">No coins configured yet.</p>
                                            </div>
                                        </template>
                                    </div>
                                </div>

                                {{-- Main Content Area --}}
                                <div
                                    class="flex-1 w-full md:w-2/3 p-6 md:p-10 overflow-y-auto bg-gradient-to-br from-gray-900 to-black relative">
                                    <button @click="selectedMethod = null"
                                        class="absolute top-4 left-4 hidden md:flex items-center text-sm text-gray-400 hover:text-white transition">
                                        <svg class="w-4 h-4 ml-1 rotate-180" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7"></path>
                                        </svg>
                                        العودة
                                    </button>

                                    {{-- STC Pay --}}
                                    <template x-if="selectedMethod.type === 'stc_pay'">
                                        <div class="space-y-8 animate-fadeIn mt-8">
                                            <div class="bg-white/5 rounded-2xl p-6 border border-white/10 text-center">
                                                <p class="text-gray-400 text-sm mb-4 uppercase tracking-widest">رقم
                                                    الحساب</p>
                                                <div
                                                    class="flex items-center justify-center gap-4 bg-black/50 p-4 rounded-xl border border-gray-700 mx-auto max-w-sm group focus-within:border-red-500 transition-colors">
                                                    <span class="text-2xl font-mono text-white tracking-wider"
                                                        x-text="selectedMethod.content"></span>
                                                    <button @click="copyToClipboard(selectedMethod.content)"
                                                        class="text-gray-500 hover:text-white transition-colors">
                                                        <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z">
                                                            </path>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                            <template x-if="selectedMethod.qr_code">
                                                <div class="text-center">
                                                    <p class="text-gray-400 text-xs mb-4">مسح الرمز QR للإرسال السريع
                                                    </p>
                                                    <div class="bg-white p-4 rounded-xl inline-block shadow-xl">
                                                        <img :src="'/storage/' + selectedMethod.qr_code"
                                                            referrerpolicy="no-referrer"
                                                            class="w-48 h-48 object-contain">
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </template>

                                    {{-- Crypto --}}
                                    <template x-if="selectedMethod.type === 'crypto' && activeCoin">
                                        <div class="space-y-6 animate-fadeIn mt-8">
                                            <div class="flex items-center justify-between mb-2">
                                                <h3 class="text-xl font-bold text-white flex items-center gap-2">
                                                    <span class="text-red-500">عملة</span> <span
                                                        x-text="activeCoin.coin_name"></span>
                                                </h3>
                                                <span
                                                    class="text-xs text-gray-500 bg-black/50 px-3 py-1 rounded-full border border-gray-800">تأكد
                                                    من اختيار الشبكة الصحيحة</span>
                                            </div>
                                            <template x-for="network in activeCoin.networks">
                                                <div
                                                    class="bg-white/5 rounded-2xl p-0 border border-white/10 overflow-hidden hover:border-red-500/30 transition-colors">
                                                    <div
                                                        class="bg-black/30 px-6 py-3 border-b border-white/5 flex justify-between items-center">
                                                        <span class="font-mono text-blue-400 font-bold"
                                                            x-text="network.network_name"></span>
                                                        <span
                                                            class="text-[10px] text-gray-500 uppercase tracking-wider">Network</span>
                                                    </div>
                                                    <div class="p-6 flex flex-col md:flex-row gap-6 items-center">
                                                        <template x-if="network.qr_image">
                                                            <div class="bg-white p-2 rounded-lg flex-shrink-0">
                                                                <img :src="'/storage/' + network.qr_image"
                                                                    referrerpolicy="no-referrer"
                                                                    class="w-24 h-24 object-contain">
                                                            </div>
                                                        </template>
                                                        <div class="flex-1 w-full min-w-0">
                                                            <label class="block text-xs text-gray-400 mb-2">Wallet
                                                                Address</label>
                                                            <div class="flex items-center gap-3 bg-black/50 p-3 rounded-lg border border-gray-700 group cursor-pointer hover:border-gray-500 transition-colors"
                                                                @click="copyToClipboard(network.wallet_address)">
                                                                <p class="text-xs md:text-sm font-mono text-gray-200 truncate select-all"
                                                                    x-text="network.wallet_address"></p>
                                                                <button
                                                                    class="ml-auto text-gray-500 group-hover:text-white transition-colors">
                                                                    <svg class="w-5 h-5" fill="none"
                                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z">
                                                                        </path>
                                                                    </svg>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </template>

                                    {{-- Legacy Manual/Generic --}}
                                    <template
                                        x-if="selectedMethod.type !== 'crypto' && selectedMethod.type !== 'stc_pay'">
                                        <div class="space-y-6 animate-fadeIn mt-8">
                                            <div>
                                                <label class="block text-sm text-gray-400 mb-2">Details /
                                                    العنوان</label>
                                                <div
                                                    class="flex items-center gap-2 bg-black/50 p-3 rounded-lg border border-gray-700 group focus-within:border-red-500 transition">
                                                    <input type="text" readonly :value="selectedMethod.content"
                                                        class="bg-transparent border-none text-white w-full focus:ring-0 font-mono text-sm">
                                                    <button @click="copyToClipboard(selectedMethod.content)"
                                                        class="p-2 text-gray-400 hover:text-white hover:bg-white/10 rounded-lg transition"
                                                        title="Copy">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3">
                                                            </path>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                            <template x-if="selectedMethod.qr_code">
                                                <div class="text-center">
                                                    <p class="text-gray-400 text-xs mb-4">QR Code</p>
                                                    <div class="bg-white p-4 rounded-xl inline-block shadow-xl">
                                                        <img :src="'/storage/' + selectedMethod.qr_code"
                                                            referrerpolicy="no-referrer"
                                                            class="w-48 h-48 object-contain">
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </template>

                                    {{-- Instructions --}}
                                    <template x-if="selectedMethod.instruction">
                                        <div class="mt-8 pt-8 border-t border-white/10">
                                            <h4 class="text-lg font-bold text-white mb-4">تعليمات هامة</h4>
                                            <div class="prose prose-invert prose-sm max-w-none text-gray-400 bg-black/20 p-6 rounded-xl border border-white/5"
                                                x-html="selectedMethod.instruction"></div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </template>
    </div>

</x-app-layout>
