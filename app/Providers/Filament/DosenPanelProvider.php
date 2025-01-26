<?php

namespace App\Providers\Filament;

use App\Filament\Resources\AssesstmentResource\Widgets\AssestmentChart;
use App\Filament\Widgets\AccountWidget;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
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

class DosenPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('dosen')
            ->path('')
            ->login()
            ->brandLogo('/fikom.jpg')
            ->brandName('Fakultas Ilmu Komputer')
            ->brandLogoHeight('30px')
            ->colors([
                'primary' => Color::Indigo,
            ])
            ->discoverResources(in: app_path('Filament/Dosen/Resources'), for: 'App\\Filament\\Dosen\\Resources')
            ->discoverPages(in: app_path('Filament/Dosen/Pages'), for: 'App\\Filament\\Dosen\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Dosen/Widgets'), for: 'App\\Filament\\Dosen\\Widgets')
            ->widgets([
                AccountWidget::class,
                AssestmentChart::class
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
                FilamentBackgroundsPlugin::make()
                    ->imageProvider(
                        \Swis\Filament\Backgrounds\ImageProviders\MyImages::make()
                            ->directory('images/backgrounds')
                    )
                    ->showAttribution(false),

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
            ])
            ->darkMode(false)
            ->spa(config('dashboard.panel.single_page_aplication'))
            ->databaseNotifications()
            ->navigationGroups([])
            ->favicon('/favicon.png')
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->domain(env('DOSEN_DOMAIN', null))
            ->topNavigation(config('dashboard.panel.top_navigation'));
    }
}
