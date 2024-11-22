<?php

namespace App\Providers\Filament;

use Althinect\FilamentSpatieRolesPermissions\FilamentSpatieRolesPermissionsPlugin;
use App\Filament\Admin\Themes\SknorTheme;
use App\Filament\Widgets\AccountWidget;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Navigation\NavigationItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use GeoSot\FilamentEnvEditor\FilamentEnvEditorPlugin;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Joaopaulolndev\FilamentEditProfile\FilamentEditProfilePlugin;
use Joaopaulolndev\FilamentEditProfile\Pages\EditProfilePage;
use pxlrbt\FilamentEnvironmentIndicator\EnvironmentIndicatorPlugin;
use Swis\Filament\Backgrounds\FilamentBackgroundsPlugin;
use Swis\Filament\Backgrounds\ImageProviders\MyImages;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->brandLogo('/logo.png')
            ->login(\App\Filament\Auth\CustomLogin::class)
            ->passwordReset()
            ->registration()
            ->colors([
                'primary' => Color::Orange,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                // Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,
                AccountWidget::class
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
                \Hasnayeen\Themes\Http\Middleware\SetTheme::class
            ])
            ->userMenuItems([
                'profile' => MenuItem::make()
                    ->label(fn() => auth()->user()->name)
                    ->url(fn(): string => EditProfilePage::getUrl())
                    ->icon('heroicon-m-user-circle')
                    //If you are using tenancy need to check with the visible method where ->company() is the relation between the user and tenancy model as you called
                    ->visible(function (): bool {
                        return true;
                    }),
            ])
            ->plugins([
                FilamentBackgroundsPlugin::make(),

                FilamentEditProfilePlugin::make()
                    ->slug('my-profile')
                    ->shouldRegisterNavigation(false)
                    ->canAccess(fn() => true)
                    ->shouldShowBrowserSessionsForm()
                    ->shouldShowDeleteAccountForm(false)
                    ->shouldShowAvatarForm(
                        value: true,
                        directory: 'avatars', // image will be stored in 'storage/app/public/avatars
                        rules: 'mimes:jpeg,png|max:' . 1024 * 3 //only accept jpeg and png files with a maximum size of 3MB
                    ),

                \BezhanSalleh\FilamentExceptions\FilamentExceptionsPlugin::make(),

                \Hasnayeen\Themes\ThemesPlugin::make()
                    ->canViewThemesPage(fn() => true)
                    ->registerTheme(
                        [
                            // SknorTheme::class,
                            \Hasnayeen\Themes\Themes\Nord::class,
                            // \Hasnayeen\Themes\Themes\Sunset::class,
                        ],
                        override: true,
                    ),

                FilamentSpatieRolesPermissionsPlugin::make(),

                EnvironmentIndicatorPlugin::make()
                    ->showBadge(true)
                    ->showBorder(false)
                    ->showGitBranch(),

                FilamentEnvEditorPlugin::make()
                    ->navigationGroup(fn () => __('app.settings'))
                    ->navigationLabel(fn () => __('app.env_editor'))
                    ->navigationIcon('heroicon-o-cog-8-tooth')
                    ->navigationSort(1)
                    ->slug('env-editor')
            ])
            ->spa(config('dashboard.panel.single_page_aplication'))
            ->databaseNotifications()
            ->authMiddleware([
                Authenticate::class,
            ])
            ->navigationGroups([
                __('app.navigation.user_management'),
                __('filament-spatie-roles-permissions::filament-spatie.section.roles_and_permissions'),
                __('app.settings'),
            ])
            ->favicon('/favicon.png')
            ->topNavigation(config('dashboard.panel.top_navigation'));;
    }
}
