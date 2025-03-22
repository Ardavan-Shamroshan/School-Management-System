@php
    use Filament\Support\Enums\MaxWidth;$formPanelPosition = filament('filament-auth-ui-enhancer')->getFormPanelPosition();
    $mobileFormPanelPosition = filament('filament-auth-ui-enhancer')->getMobileFormPanelPosition();
    $emptyPanelBackgroundImageUrl = filament('filament-auth-ui-enhancer')->getEmptyPanelBackgroundImageUrl();
    $emptyPanelBackgroundImageOpacity = filament('filament-auth-ui-enhancer')->getEmptyPanelBackgroundImageOpacity();
    $showEmptyPanelOnMobile = filament('filament-auth-ui-enhancer')->getShowEmptyPanelOnMobile();
    $maxWidth = MaxWidth::ExtraLarge;
@endphp
<x-filament-panels::layout.base :livewire="$livewire">
    <div
            @class([
              'custom-auth-wrapper flex w-full min-h-screen',
              'lg:flex-row-reverse' => $formPanelPosition === 'left',
              'lg:flex-row' => $formPanelPosition === 'right',
              'flex-col' => $mobileFormPanelPosition === 'bottom' && $showEmptyPanelOnMobile,
              'flex-col-reverse' => $mobileFormPanelPosition === 'top' && $showEmptyPanelOnMobile,
            ])
    >
        <!-- Empty Container -->
        <div @class([
            'custom-auth-empty-panel relative justify-center px-4',
            'bg-[var(--empty-panel-background-color)]',
            'hidden lg:flex lg:flex-col lg:flex-grow' => $showEmptyPanelOnMobile === false,
            'flex flex-col flex-grow' => $showEmptyPanelOnMobile === true
            ])
        >
            @if($emptyPanelBackgroundImageUrl)
                <div class="absolute inset-0 bg-cover bg-center rounded-xl shadow-lg m-6 transition-all duration-300 filter delay-50 ease-in-out hover:scale-105"
                     style="background-image: url('{{ $emptyPanelBackgroundImageUrl }}'); opacity: {{ $emptyPanelBackgroundImageOpacity }}; background-position: center;">
                </div>
            @endif
        </div>

        <!-- Form Container -->
        <div class="custom-auth-form-panel flex flex-col justify-center px-4 py-12 sm:px-6 lg:px-20 xl:px-36 w-full lg:w-[var(--form-panel-width)] bg-[var(--form-panel-background-color)]">
            <div class="custom-auth-form-wrapper mx-auto w-full max-w-xl">
                {{ $slot }}
            </div>
        </div>


        {{--            <main--}}
        {{--                    @class([--}}
        {{--                        'fi-simple-main my-16 mx-10 w-full bg-white px-6 py-12 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 sm:rounded-xl sm:px-12',--}}
        {{--                        match ($maxWidth ??= (filament()->getSimplePageMaxContentWidth() ?? MaxWidth::Large)) {--}}
        {{--                            MaxWidth::ExtraSmall, 'xs' => 'max-w-xs',--}}
        {{--                            MaxWidth::Small, 'sm' => 'max-w-sm',--}}
        {{--                            MaxWidth::Medium, 'md' => 'max-w-md',--}}
        {{--                            MaxWidth::Large, 'lg' => 'max-w-lg',--}}
        {{--                            MaxWidth::ExtraLarge, 'xl' => 'max-w-xl',--}}
        {{--                            MaxWidth::TwoExtraLarge, '2xl' => 'max-w-2xl',--}}
        {{--                            MaxWidth::ThreeExtraLarge, '3xl' => 'max-w-3xl',--}}
        {{--                            MaxWidth::FourExtraLarge, '4xl' => 'max-w-4xl',--}}
        {{--                            MaxWidth::FiveExtraLarge, '5xl' => 'max-w-5xl',--}}
        {{--                            MaxWidth::SixExtraLarge, '6xl' => 'max-w-6xl',--}}
        {{--                            MaxWidth::SevenExtraLarge, '7xl' => 'max-w-7xl',--}}
        {{--                            MaxWidth::Full, 'full' => 'max-w-full',--}}
        {{--                            MaxWidth::MinContent, 'min' => 'max-w-min',--}}
        {{--                            MaxWidth::MaxContent, 'max' => 'max-w-max',--}}
        {{--                            MaxWidth::FitContent, 'fit' => 'max-w-fit',--}}
        {{--                            MaxWidth::Prose, 'prose' => 'max-w-prose',--}}
        {{--                            MaxWidth::ScreenSmall, 'screen-sm' => 'max-w-screen-sm',--}}
        {{--                            MaxWidth::ScreenMedium, 'screen-md' => 'max-w-screen-md',--}}
        {{--                            MaxWidth::ScreenLarge, 'screen-lg' => 'max-w-screen-lg',--}}
        {{--                            MaxWidth::ScreenExtraLarge, 'screen-xl' => 'max-w-screen-xl',--}}
        {{--                            MaxWidth::ScreenTwoExtraLarge, 'screen-2xl' => 'max-w-screen-2xl',--}}
        {{--                            default => $maxWidth,--}}
        {{--                        },--}}
        {{--                    ])--}}
        {{--            >--}}
        {{--                {{ $slot }}--}}
        {{--            </main>--}}
    </div>

</x-filament-panels::layout.base>