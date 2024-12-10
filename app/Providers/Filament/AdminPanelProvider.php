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
use Swis\Filament\Backgrounds\FilamentBackgroundsPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(\App\Filament\Auth\CustomLogin::class)
            ->brandLogo('/fikom.jpg')
            ->brandName('Fakultas Ilmu Komputer')
            ->brandLogoHeight('30px')
            ->passwordReset()
            ->darkMode(false)
            ->colors([
                'primary' => Color::Sky,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->authMiddleware([
                Authenticate::class,
            ])
            ->widgets([
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
                    ->label(fn() => get_auth_user()->name)
                    ->url(fn(): string => EditProfilePage::getUrl())
                    ->icon('heroicon-m-user-circle')
                    //If you are using tenancy need to check with the visible method where ->company() is the relation between the user and tenancy model as you called
                    ->visible(function (): bool {
                        return true;
                    }),
            ])
            ->plugins([
                FilamentBackgroundsPlugin::make(),

                \Saade\FilamentFullCalendar\FilamentFullCalendarPlugin::make()
                    ->selectable()
                    ->editable()
                    ->timezone('Asia/Jakarta')
                    ->locale('id'),

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

                // \Hasnayeen\Themes\ThemesPlugin::make()
                //     ->canViewThemesPage(fn() => true)
                //     ->registerTheme(
                //         [
                //             // SknorTheme::class,
                //             // \Hasnayeen\Themes\Themes\Nord::class,
                //             \Hasnayeen\Themes\Themes\Sunset::class,
                //         ],
                //         override: true,
                //     ),

                FilamentEnvEditorPlugin::make()
                    ->navigationGroup(fn() => __('app.settings'))
                    ->navigationLabel(fn() => __('app.env_editor'))
                    ->navigationIcon('heroicon-o-cog-8-tooth')
                    ->navigationSort(1)
                    ->authorize(false)
                    ->slug('env-editor'),
            ])
            ->spa(config('dashboard.panel.single_page_aplication'))
            ->databaseNotifications()
            ->navigationGroups([
                __('app.navigation.user_management'),
            ])
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->favicon('/favicon.png')
            ->domain(env('ADMIN_DOMAIN', null))
            ->topNavigation(config('dashboard.panel.top_navigation'));
    }
}
