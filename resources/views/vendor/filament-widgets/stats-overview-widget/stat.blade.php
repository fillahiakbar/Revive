@php
    use Filament\Support\Enums\IconPosition;
    use Filament\Support\Facades\FilamentView;

    $chartColor = $getChartColor() ?? 'gray';
    $descriptionColor = $getDescriptionColor() ?? 'gray';
    $descriptionIcon = $getDescriptionIcon();
    $descriptionIconPosition = $getDescriptionIconPosition();
    $url = $getUrl();
    $tag = $url ? 'a' : 'div';
    $dataChecksum = $generateDataChecksum();

    $extraClasses = $getExtraAttributeBag()->get('class', '');
    $isGradient = is_string($extraClasses) && str_contains($extraClasses, 'stat-gradient');

    $containerClasses = $isGradient
        ? 'relative rounded-2xl bg-gradient-to-br from-red-600 to-black p-6 shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10 text-white overflow-hidden'
        : 'relative rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 overflow-hidden';

    $labelClasses = $isGradient
        ? 'text-sm font-medium text-white/80'
        : 'text-sm font-medium text-gray-500 dark:text-gray-400';

    $valueClasses = $isGradient
        ? 'text-4xl font-bold tracking-tight text-white mt-1'
        : 'text-4xl font-bold tracking-tight text-gray-950 dark:text-white mt-1';
        
    $descriptionClasses = $isGradient
        ? 'text-xs text-white/80 font-medium'
        : 'text-xs text-gray-500 dark:text-gray-400 font-medium';

    $iconWrapperClasses = $isGradient
        ? 'p-1.5 rounded-full bg-white text-black shrink-0'
        : 'p-1.5 rounded-full bg-transparent border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white shrink-0';
        
    $descriptionIconClasses = \Illuminate\Support\Arr::toCssClasses([
        'h-4 w-4',
        $isGradient ? 'text-white' : match ($descriptionColor) {
            'gray' => 'text-gray-400 dark:text-gray-500',
            default => 'text-custom-500',
        },
    ]);

    $descriptionIconStyles = \Illuminate\Support\Arr::toCssStyles([
        \Filament\Support\get_color_css_variables(
            $descriptionColor,
            shades: [500],
            alias: 'widgets::stats-overview-widget.stat.description.icon',
        ) => $descriptionColor !== 'gray' && !$isGradient,
    ]);
@endphp

<{!! $tag !!}
    @if ($url)
        {{ \Filament\Support\generate_href_html($url, $shouldOpenUrlInNewTab()) }}
    @endif
    {{
        $getExtraAttributeBag()->class([$containerClasses])
    }}
>
    <div class="flex justify-between items-start relative z-10">
        <div class="flex flex-col">
            <span class="{{ $labelClasses }}">
                {{ $getLabel() }}
            </span>
            <div class="{{ $valueClasses }}">
                {{ $getValue() }}
            </div>
        </div>

        <div class="{{ $iconWrapperClasses }}">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
              <path fill-rule="evenodd" d="M5.22 14.78a.75.75 0 001.06 0l7.22-7.22v5.69a.75.75 0 001.5 0v-7.5a.75.75 0 00-.75-.75h-7.5a.75.75 0 000 1.5h5.69l-7.22 7.22a.75.75 0 000 1.06z" clip-rule="evenodd" />
            </svg>
        </div>
    </div>
    
    @if ($description = $getDescription())
        <div class="flex items-center gap-x-2 mt-6 text-sm font-medium relative z-10">
            @if ($descriptionIcon)
                <div class="shrink-0 flex items-center justify-center rounded bg-gray-100/10 p-1">
                    <x-filament::icon
                        :icon="$descriptionIcon"
                        :class="$descriptionIconClasses"
                        :style="$descriptionIconStyles"
                    />
                </div>
            @endif

            <span class="{{ $descriptionClasses }}" @if(!$isGradient) @style([
                        \Filament\Support\get_color_css_variables(
                            $descriptionColor,
                            shades: [400, 600],
                            alias: 'widgets::stats-overview-widget.stat.description',
                        ) => $descriptionColor !== 'gray',
                    ]) @endif>
                {{ $description }}
            </span>
        </div>
    @endif
    
    @if ($chart = $getChart())
        {{-- Custom tweaked chart positioning to be an accent rather than the full card line --}}
        <div x-data="{ statsOverviewStatChart: function () {} }">
            <div
                @if (FilamentView::hasSpaMode())
                    x-load="visible"
                @else
                    x-load
                @endif
                x-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('stats-overview/stat/chart', 'filament/widgets') }}"
                x-data="statsOverviewStatChart({
                            dataChecksum: @js($dataChecksum),
                            labels: @js(array_keys($chart)),
                            values: @js(array_values($chart)),
                        })"
                @class([
                    'fi-wi-stats-overview-stat-chart absolute inset-x-0 bottom-0 overflow-hidden rounded-b-2xl opacity-40',
                    match ($chartColor) {
                        'gray' => null,
                        default => 'fi-color-custom',
                    },
                    is_string($chartColor) ? "fi-color-{$chartColor}" : null,
                ])
                @style([
                    \Filament\Support\get_color_css_variables(
                        $chartColor,
                        shades: [50, 400, 500],
                        alias: 'widgets::stats-overview-widget.stat.chart',
                    ) => $chartColor !== 'gray',
                ])
            >
                <canvas x-ref="canvas" class="h-12"></canvas>

                <span
                    x-ref="backgroundColorElement"
                    @class([
                        $isGradient ? 'text-white/20 dark:text-white/20' : match ($chartColor) {
                            'gray' => 'text-gray-100 dark:text-gray-800',
                            default => 'text-custom-50 dark:text-custom-400/10',
                        },
                    ])
                ></span>

                <span
                    x-ref="borderColorElement"
                    @class([
                        $isGradient ? 'text-white/50 dark:text-white/50' : match ($chartColor) {
                            'gray' => 'text-gray-400',
                            default => 'text-custom-500 dark:text-custom-400',
                        },
                    ])
                ></span>
            </div>
        </div>
    @endif
</{!! $tag !!}>
