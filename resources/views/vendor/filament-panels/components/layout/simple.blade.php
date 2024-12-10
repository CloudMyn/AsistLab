@php
    use Filament\Support\Enums\MaxWidth;
@endphp

<x-filament-panels::layout.base :livewire="$livewire">
    @props([
        'after' => null,
        'heading' => null,
        'subheading' => null,
    ])

    <div class="fi-simple-layout flex min-h-screen flex-col items-center">
        @if (($hasTopbar ?? true) && filament()->auth()->check())
            <div class="absolute end-0 top-0 flex h-16 items-center gap-x-4 pe-4 md:pe-6 lg:pe-8">
                @if (filament()->hasDatabaseNotifications())
                    @livewire(Filament\Livewire\DatabaseNotifications::class, [
                        'lazy' => filament()->hasLazyLoadedDatabaseNotifications(),
                    ])
                @endif

                <x-filament-panels::user-menu />
            </div>
        @endif

        <div class="fi-simple-main-ctn flex w-full flex-grow items-center justify-center">

            <div id="login-container">
                <main id="login-form">
                    {{ $slot }}
                </main>
                <div id="logo-login">
                    <img src="/lab.jpg" alt="">
                </div>
            </div>

        </div>

        {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::FOOTER, scopes: $livewire->getRenderHookScopes()) }}
    </div>
</x-filament-panels::layout.base>
