<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\Login;
use App\Filament\Pages\Dashboard;
use App\Filament\Plugins\AuthUIEnhancerPlugin\AuthUIEnhancerPlugin;
use App\Filament\Resources\Academy\StudentResource;
use App\Filament\Resources\Academy\StudentResource\Pages\ListStudents;
use App\Filament\Resources\Academy\TeacherResource;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationItem;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Enums\Platform;
use Filament\View\PanelsRenderHook;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Vite;
use Illuminate\View\Middleware\ShareErrorsFromSession;

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
            ->colors([
                'primary' => '#671CC9'
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                // Pages\Dashboard::class,
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
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
            ])
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->brandLogo(Vite::asset('resources/assets/images/logo-dark.png'))
            ->favicon(Vite::asset('resources/assets/images/logo-dark.png'))
            ->brandLogoHeight('3rem')
            ->unsavedChangesAlerts()
            ->sidebarCollapsibleOnDesktop()
            ->sidebarWidth('16rem')
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->globalSearchFieldSuffix(fn (): ?string => match (Platform::detect()) {
                Platform::Windows, Platform::Linux => 'CTRL+K',
                Platform::Mac => '⌘K',
                default => null,
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
                    ->icon('heroicon-o-briefcase'),

                NavigationItem::make(TeacherResource::getPluralModelLabel())
                    ->url(fn() => TeacherResource::getUrl())
                    ->icon('heroicon-o-briefcase'),
            ]);
    }
}
