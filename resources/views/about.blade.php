<x-app-layout>

<div class="relative text-white h-screen flex items-center justify-between overflow-hidden">
    <!-- Phoenix image positioned in the left corner -->
    <div class="absolute left-0 top-0 w-[75%] h-[150%] z-0 overflow-hidden">
    <img src="{{ asset('/img/about.png') }}" alt="Phoenix Background" class="w-full h-full object-cover object-left scale-110 -translate-x-4">
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
                                        نحن <span class="text-white font-semibold">REVIVE</span> - فريق متخصص في التصوير والإنتاج والتصميم والتقنيات المبتكرة. نقدم خدمات شاملة في مجال الإنتاج المرئي والصوتي.
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
                                        أن نكون الجسر الذي يصل بين الماضي والحاضر، بين اليابان والعالم العربي، بين المجهول والعاشق الباحث عن شيءٍ مُختلف.
                                    </p>
                                    <p>
                                        نُؤمن أنَّ في كُلِّ أنمي منسي كنزٌ دفين، وأنَّ وظيفتنا اكتشافه وترميمه وتقديمه في أبهى صورة.
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
                                        في الظلِّ نعمل، لا نبحث عن شهرة بل عن أثر. نحن مُترجمون ومُحرِّرون ومُدقِّقون، عُشَّاقٌ قبل أن نكون مُتطوِّعين. يجمعنا شغفٌ واحد: ألا يُنسى أيُّ عملٍ يستحقُّ أن يُروى.
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


   <div class="max-w-7xl mx-auto px-4 py-20 text-white">
    <div class="mt-8">
        <div class="bg-white/30 backdrop-blur-lg p-10 border border-white/20 text-center">
            <!-- Judul Kontak -->
            <h2 class="text-4xl font-bold mb-10 rtl:text-center text-white">تواصل معنا</h2>

            <!-- Ikon Media Sosial -->
            <div class="flex justify-center items-center gap-10 text-4xl rtl:flex-row-reverse">
                <a href="#" class="hover:text-red-500 transition"><i class="fab fa-discord"></i></a>
                <a href="#" class="hover:text-red-500 transition"><i class="fab fa-x-twitter"></i></a>
                <a href="#" class="hover:text-red-500 transition"><i class="fab fa-instagram"></i></a>
                <a href="#" class="hover:text-red-500 transition"><i class="fab fa-telegram-plane"></i></a>
            </div>
        </div>
    </div>
</div>

</x-app-layout>