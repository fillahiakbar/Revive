<x-app-layout>
    @push('styles')
    <style>
        [x-cloak] { display: none !important; }
        .neon-text {
            text-shadow: 0 0 10px rgba(239, 68, 68, 0.7), 0 0 20px rgba(239, 68, 68, 0.5);
        }
        .neon-box {
            box-shadow: 0 0 15px rgba(239, 68, 68, 0.15);
        }
        .neon-box:hover {
            box-shadow: 0 0 25px rgba(239, 68, 68, 0.3);
        }
        .glass-dark {
            background: rgba(10, 10, 20, 0.85);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        /* Custom scrollbar for modal content */
        .custom-scroll::-webkit-scrollbar {
            width: 8px;
        }
        .custom-scroll::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.3);
        }
        .custom-scroll::-webkit-scrollbar-thumb {
            background: rgba(239, 68, 68, 0.5);
            border-radius: 4px;
        }
    </style>
    @endpush

    <div class="min-h-screen bg-black text-white font-cairo pt-24 pb-12 relative overflow-hidden" dir="rtl"
         x-data="{
            modalOpen: false,
            activeMethod: null,
            activeCoin: null,
            
            openModal(method) {
                this.activeMethod = method;
                this.modalOpen = true;
                if(method.type === 'crypto' && method.options && method.options.coins && method.options.coins.length > 0) {
                    this.activeCoin = method.options.coins[0];
                }
            },
            closeModal() {
                this.modalOpen = false;
                setTimeout(() => {
                    this.activeMethod = null;
                    this.activeCoin = null;
                }, 300);
            },
            copyToClipboard(text) {
                navigator.clipboard.writeText(text).then(() => {
                    // Show toast or feedback
                    let toast = document.createElement('div');
                    toast.innerText = 'تم النسخ بنجاح!';
                    toast.className = 'fixed bottom-5 right-5 bg-red-600 text-white px-6 py-3 rounded-lg shadow-lg z-[100] animate-bounce';
                    document.body.appendChild(toast);
                    setTimeout(() => toast.remove(), 2000);
                });
            }
         }">
        
        <!-- Background Elements -->
        <div class="fixed inset-0 z-0 pointer-events-none">
            <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-red-900/20 rounded-full blur-[120px]"></div>
            <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-blue-900/10 rounded-full blur-[120px]"></div>
            <div class="absolute inset-0 bg-[url('/img/pattern.png')] opacity-5"></div>
        </div>

        <div class="container mx-auto px-4 relative z-10">
            
            <!-- Hero Section -->
            <div class="text-center max-w-3xl mx-auto mb-16 space-y-6">
                <span class="inline-block py-1 px-3 rounded-full bg-red-900/30 border border-red-500/30 text-red-400 text-sm font-bold tracking-wider mb-2 animate-pulse">
                    دعم الموقع OFFICIAL SUPPORT
                </span>
                <h1 class="text-4xl md:text-6xl font-black neon-text bg-clip-text text-transparent bg-gradient-to-r from-white via-gray-200 to-gray-400 leading-tight">
                    ساهم في استمرار<br>
                    <span class="text-red-600">عالم الأنمي</span> المفضل لديك
                </h1>
                <p class="text-gray-400 text-lg md:text-xl leading-relaxed max-w-2xl mx-auto">
                    دعمكم هو الوقود الذي يجعلنا نستمر في تقديم أفضل تجربة مشاهدة. 
                    كل مساهمة تساعدنا في تطوير الخوادم وتحسين الجودة.
                </p>
                <div class="w-24 h-1 bg-gradient-to-r from-transparent via-red-600 to-transparent mx-auto rounded-full mt-8 opacity-50"></div>
            </div>

            <!-- Donation Methods Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-6xl mx-auto">
                @foreach($paymentMethods as $method)
                    <div class="group relative rounded-2xl glass-dark p-1 overflow-hidden transition-all duration-300 hover:scale-[1.02] neon-box cursor-pointer"
                         @click="{{ $method->type === 'paypal' ? 'window.open(\''.$method->content.'\', \'_blank\')' : 'openModal('.json_encode($method).')' }}">
                        
                        <!-- Hover Glow Effect -->
                        <div class="absolute inset-0 bg-gradient-to-br from-red-600/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>

                        <div class="relative h-full bg-black/40 rounded-xl p-8 flex flex-col items-center text-center space-y-6">
                            <!-- Icon -->
                            <div class="w-24 h-24 rounded-full bg-white/5 flex items-center justify-center border border-white/10 group-hover:border-red-500/50 transition-colors duration-300 shadow-lg">
                                @if($method->icon)
                                    <img src="/storage/{{ $method->icon }}" alt="{{ $method->name }}" referrerpolicy="no-referrer" class="w-14 h-14 object-contain opacity-80 group-hover:opacity-100 transition-opacity">
                                @else
                                    <span class="text-4xl">💎</span>
                                @endif
                            </div>

                            <!-- Content -->
                            <div class="space-y-2">
                                <h3 class="text-2xl font-bold text-white group-hover:text-red-400 transition-colors">{{ $method->name }}</h3>
                                <p class="text-xs text-gray-500 uppercase tracking-widest font-semibold">
                                    {{ $method->type === 'paypal' ? 'Direct Transfer' : ($method->type === 'crypto' ? 'Secure Crypto' : 'Local Payment') }}
                                </p>
                            </div>

                            <!-- Action Hint -->
                            @if($method->type === 'paypal')
                                <div class="mt-auto pt-4 flex items-center text-red-400 text-sm font-bold opacity-0 group-hover:opacity-100 transform translate-y-2 group-hover:translate-y-0 transition-all">
                                    <span>تبرع الآن</span>
                                    <svg class="w-4 h-4 mr-2 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                </div>
                            @elseif($method->type === 'crypto')
                                <div class="mt-auto pt-4 flex gap-2 justify-center">
                                    <span class="px-2 py-1 bg-white/5 rounded text-xs text-gray-400 border border-white/5">USDT</span>
                                    <span class="px-2 py-1 bg-white/5 rounded text-xs text-gray-400 border border-white/5">BTC</span>
                                    <span class="px-2 py-1 bg-white/5 rounded text-xs text-gray-400 border border-white/5">ETH</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Footer Note -->
            <div class="text-center mt-16 text-gray-500 text-sm">
                <p>شكراً لكل من ساهم، أنتم أبطالنا الحقيقيون ❤️</p>
            </div>
        </div>

        <!-- MODAL OVERLAY -->
        <div x-show="modalOpen" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[100] flex items-center justify-center bg-black/90 backdrop-blur-md p-4"
             x-cloak>
            
            <!-- Modal Content -->
            <div x-show="modalOpen"
                 x-transition:enter="transition cubic-bezier(0.34, 1.56, 0.64, 1) duration-500"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-10"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-10"
                 class="w-full max-w-4xl max-h-[90vh] glass-dark rounded-3xl shadow-2xl flex flex-col md:flex-row overflow-hidden relative"
                 @click.away="closeModal()">
                
                <!-- Close Button -->
                <button @click="closeModal()" class="absolute top-4 left-4 z-50 p-2 bg-black/50 hover:bg-red-600 rounded-full text-white transition-colors duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>

                <!-- Sidebar (Visual / Selection) -->
                <div class="w-full md:w-1/3 bg-black/40 border-l border-white/5 p-8 flex flex-col items-center justify-center text-center relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-b from-red-900/10 to-transparent"></div>
                    
                    <template x-if="activeMethod">
                        <div class="relative z-10 w-full">
                            <img :src="'/storage/' + activeMethod.icon" referrerpolicy="no-referrer" class="w-24 h-24 object-contain mx-auto mb-6 drop-shadow-2xl">
                            <h2 class="text-2xl font-bold text-white mb-2" x-text="activeMethod.name"></h2>
                            <p class="text-sm text-gray-400 mb-8 px-4" x-text="activeMethod.type === 'crypto' ? 'حول العملات الرقمية بأمان وسرعة' : 'الدفع المحلي المباشر'"></p>

                            <template x-if="activeMethod.type === 'crypto' && activeMethod.options && activeMethod.options.coins && activeMethod.options.coins.length > 0">
                                <div class="grid grid-cols-2 gap-2 w-full">
                                    <template x-for="coin in activeMethod.options.coins">
                                        <button @click="activeCoin = coin"
                                                :class="activeCoin === coin ? 'bg-red-600 text-white border-red-500 shadow-lg shadow-red-900/50' : 'bg-white/5 text-gray-400 border-white/10 hover:bg-white/10'"
                                                class="py-3 px-4 rounded-xl border font-bold text-sm transition-all duration-300 flex items-center justify-center gap-2">
                                            <span x-text="coin.coin_name"></span>
                                        </button>
                                    </template>
                                </div>
                            </template>
                            <template x-if="activeMethod.type === 'crypto' && (!activeMethod.options || !activeMethod.options.coins || activeMethod.options.coins.length === 0)">
                                <div class="text-center p-4 bg-red-900/20 rounded-xl border border-red-500/30">
                                    <p class="text-red-400 text-xs">No coins configured yet.</p>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>

                <!-- Main Content Area -->
                <div class="w-full md:w-2/3 p-8 md:p-10 overflow-y-auto custom-scroll bg-gradient-to-br from-gray-900 to-black">
                    
                    <!-- STC Pay Layout -->
                    <template x-if="activeMethod && activeMethod.type === 'stc_pay'">
                        <div class="space-y-8 animate-fadeIn">
                            <div class="bg-white/5 rounded-2xl p-6 border border-white/10 text-center">
                                <p class="text-gray-400 text-sm mb-4 uppercase tracking-widest">رقم الهاتف / Phone Number</p>
                                <div class="flex items-center justify-center gap-4 bg-black/50 p-4 rounded-xl border border-gray-700 mx-auto max-w-sm group focus-within:border-red-500 transition-colors">
                                    <span class="text-2xl font-mono text-white tracking-wider" x-text="activeMethod.content"></span>
                                    <button @click="copyToClipboard(activeMethod.content)" class="text-gray-500 hover:text-white transition-colors">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                    </button>
                                </div>
                            </div>
                            
                            <template x-if="activeMethod.qr_code">
                                <div class="text-center">
                                    <p class="text-gray-400 text-xs mb-4">مسح الرمز QR للإرسال السريع</p>
                                    <div class="bg-white p-4 rounded-xl inline-block shadow-xl">
                                        <img :src="'/storage/' + activeMethod.qr_code" referrerpolicy="no-referrer" class="w-48 h-48 object-contain">
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>

                    <!-- Crypto Layout -->
                    <template x-if="activeMethod && activeMethod.type === 'crypto' && activeCoin">
                        <div class="space-y-6 animate-fadeIn">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="text-xl font-bold text-white flex items-center gap-2">
                                    <span class="text-red-500"> شبكات</span> <span x-text="activeCoin.coin_name"></span>
                                </h3>
                                <span class="text-xs text-gray-500 bg-black/50 px-3 py-1 rounded-full border border-gray-800">تأكد من اختيار الشبكة الصحيحة</span>
                            </div>

                            <template x-for="network in activeCoin.networks">
                                <div class="bg-white/5 rounded-2xl p-0 border border-white/10 overflow-hidden hover:border-red-500/30 transition-colors">
                                    <div class="bg-black/30 px-6 py-3 border-b border-white/5 flex justify-between items-center">
                                        <span class="font-mono text-blue-400 font-bold" x-text="network.network_name"></span>
                                        <span class="text-[10px] text-gray-500 uppercase tracking-wider">Network</span>
                                    </div>
                                    <div class="p-6 flex flex-col md:flex-row gap-6 items-center">
                                        <!-- QR -->
                                        <template x-if="network.qr_image">
                                             <div class="bg-white p-2 rounded-lg flex-shrink-0">
                                                <img :src="'/storage/' + network.qr_image" referrerpolicy="no-referrer" class="w-24 h-24 object-contain">
                                            </div>
                                        </template>
                                        
                                        <!-- Address -->
                                        <div class="flex-1 w-full min-w-0">
                                            <label class="block text-xs text-gray-400 mb-2">Wallet Address</label>
                                            <div class="flex items-center gap-3 bg-black/50 p-3 rounded-lg border border-gray-700 group cursor-pointer hover:border-gray-500 transition-colors"
                                                 @click="copyToClipboard(network.wallet_address)">
                                                <p class="text-xs md:text-sm font-mono text-gray-200 truncate select-all" x-text="network.wallet_address"></p>
                                                <button class="ml-auto text-gray-500 group-hover:text-white transition-colors">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                                </button>
                                            </div>
                                            <p class="text-[10px] text-red-500mt-2 mt-2 opacity-70">
                                                * إرسال العملات إلى شبكة خاطئة قد يؤدي إلى فقدانها للأبد.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>

                    <!-- Instructions Block -->
                    <template x-if="activeMethod && activeMethod.instruction">
                        <div class="mt-8 pt-8 border-t border-white/10">
                            <h4 class="text-lg font-bold text-white mb-4">تعليمات هامة</h4>
                            <div class="prose prose-invert prose-sm max-w-none text-gray-400 bg-black/20 p-6 rounded-xl border border-white/5"
                                 x-html="activeMethod.instruction"></div>
                        </div>
                    </template>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
