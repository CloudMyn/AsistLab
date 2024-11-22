<?php

namespace App\Filament\Admin\Themes;

use Filament\Panel;
use Filament\Support\Colors\Color;
use Hasnayeen\Themes\Contracts\CanModifyPanelConfig;
use Hasnayeen\Themes\Contracts\Theme;

class SknorTheme implements CanModifyPanelConfig, Theme
{
    public static function getName(): string
    {
        return 'sknor-theme';
    }

    public static function getPath(): string
    {
        return 'resources/css/filament/admin/themes/sknor-theme.css';
    }

    public function getThemeColor(): array
    {
        return [
            'primary' => Color::hex('#8FBCBB'),
            'secondary' => Color::hex('#2E3440'),
            'info' => Color::hex('#5E81AC'),
            'success' => Color::hex('#A3BE8C'),
            'warning' => Color::hex('#D08770'),
            'danger' => Color::hex('#BF616A'),
        ];
    }

    public function modifyPanelConfig(Panel $panel): Panel
    {
        return $panel
            ->topNavigation()
            ->sidebarCollapsibleOnDesktop(false)
            ->renderHook('panels::page.start', fn() => view('themes::filament.hooks.tenant-menu'));
    }
}
