<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\Login;
use App\Filament\Pages\Dashboard;
use App\Filament\Plugins\AuthUIEnhancerPlugin\AuthUIEnhancerPlugin;
use App\Filament\Resources\Academy\StudentResource;
use App\Filament\Resources\Academy\TeacherResource;
use Devonab\FilamentEasyFooter\EasyFooterPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationItem;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Platform;
use Filament\View\PanelsRenderHook;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\HtmlString;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use function App\Support\setting;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->spa()
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(Login::class)
            ->profile()
            ->colors(self::getColor())
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                // Pages\Dashboard::class,
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                // Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugins([
                AuthUIEnhancerPlugin::make()
                    ->formPanelPosition('left')
                    ->showEmptyPanelOnMobile(true)
                    ->emptyPanelBackgroundImageUrl(Vite::asset('resources/assets/images/banner.svg')),

                // EasyFooterPlugin::make()
                //     ->withBorder()
                //     ->withSentence(new HtmlString('<img src="' . self::getBrandLogo() . '" width="40"> ' . setting('copyright'))),

                EasyFooterPlugin::make()
                    // ->withFooterPosition('sidebar.footer')
                    ->withBorder()
                    ->withSentence(setting('copyright'))
                    ->withLoadTime()
                    ->withGithub()
                    ->withLogo(url(setting('site_logo')),null, 40)
                    ->withLinks([
                        ['title' => 'طراحی و توسعه توسط اردوان شام روشن', 'url' => 'mailto:ardavanshamroshan@yahoo.com'],
                        ['title' => 'نسخه 1.0.0', 'url' => '#'],
                    ]),
            ])
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->brandLogo(self::getBrandLogo())
            ->favicon(self::getFavicon())
            ->brandLogoHeight('3rem')
            ->unsavedChangesAlerts()
            ->sidebarCollapsibleOnDesktop()
            ->sidebarWidth('16rem')
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->globalSearchFieldSuffix(fn(): ?string => match (Platform::detect()) {
                Platform::Windows, Platform::Linux => 'CTRL+K',
                Platform::Mac                      => '⌘K',
                default                            => null,
            })
            ->renderHook(
                PanelsRenderHook::SIDEBAR_NAV_END,
                fn() => view('filament.pages.render-hooks.panel-sidebar-nav-end'),
            )
            ->renderHook(
                PanelsRenderHook::PAGE_HEADER_ACTIONS_AFTER,
                fn() => view('filament.pages.render-hooks.page-start'),
            )
            ->navigationItems([
                NavigationItem::make(StudentResource::getPluralModelLabel())
                    ->url(fn() => StudentResource::getUrl())
                    ->icon('heroicon-o-user-group'),

                NavigationItem::make(TeacherResource::getPluralModelLabel())
                    ->url(fn() => TeacherResource::getUrl())
                    ->icon('heroicon-o-briefcase'),
            ]);
    }

    public static function getBrandLogo(): string
    {
        if ($brandLogo = setting('site_logo')) {
            return asset('storage/' . $brandLogo);
        }

        return Vite::asset('resources/assets/images/logo-dark.png');
    }

    public static function getFavicon(): string
    {
        if ($favicon = setting('site_favicon')) {
            return asset('storage/' . $favicon);
        }

        return Vite::asset('resources/assets/images/logo-dark.png');
    }

    public static function getColor(): array
    {
        return [
            'primary' => '#671CC9' ?? setting('theme_color') ?? Color::Blue,
            'danger'  => '#DC2626',
        ];
    }
}
