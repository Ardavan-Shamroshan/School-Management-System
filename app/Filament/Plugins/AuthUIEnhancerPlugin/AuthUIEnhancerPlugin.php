<?php

namespace App\Filament\Plugins\AuthUIEnhancerPlugin;

use App\Filament\Plugins\AuthUIEnhancerPlugin\Concerns\BackgroundAppearance;
use App\Filament\Plugins\AuthUIEnhancerPlugin\Concerns\FormPanelWidth;
use App\Filament\Plugins\AuthUIEnhancerPlugin\Concerns\FormPosition;
use App\Filament\Plugins\AuthUIEnhancerPlugin\Concerns\MobileFormPosition;
use App\Filament\Plugins\AuthUIEnhancerPlugin\Concerns\ShowEmptyPanelOnMobile;
use Filament\Contracts\Plugin;
use Filament\Pages\Auth\Login;
use Filament\Pages\Auth\Register;
use Filament\Panel;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;

class AuthUIEnhancerPlugin implements Plugin
{
    use BackgroundAppearance;
    use FormPanelWidth;
    use FormPosition;
    use MobileFormPosition;
    use ShowEmptyPanelOnMobile;

    public function getId(): string
    {
        return 'filament-auth-ui-enhancer';
    }

    public function register(Panel $panel): void
    {
        if ($panel->getLoginRouteAction() === Login::class) {
            $panel
                ->login(Login::class);
        }

        if ($panel->getRegistrationRouteAction() === Register::class) {
            $panel
                ->registration(Register::class);
        }

        // if ($panel->getRequestPasswordResetRouteAction() === RequestPasswordReset::class && $panel->getResetPasswordRouteAction() === ResetPassword::class) {
        //     $panel
        //         ->passwordReset(AuthUiEnhancerRequestPasswordReset::class, AuthUiEnhancerResetPassword::class);
        // }
        //
        // if ($panel->getEmailVerificationPromptRouteAction() === EmailVerificationPrompt::class) {
        //     $panel
        //         ->emailVerification(AuthUiEnhancerEmailVerificationPrompt::class);
        // }
    }

    public function boot(Panel $panel): void
    {
        FilamentView::registerRenderHook(
            PanelsRenderHook::HEAD_END,
            function () {
                return '
                    <style>
                    :root {
                    --form-panel-width: ' . $this->getFormPanelWidth() . ';
                    --form-panel-background-color: ' . $this->getFormPanelBackgroundColor() . ';
                    --empty-panel-background-color: ' . $this->getEmptyPanelBackgroundColor() . ';
                    }
                    </style>
                ';
            }
        );
    }

    public static function make(): static
    {
        return app(static::class);
    }
}