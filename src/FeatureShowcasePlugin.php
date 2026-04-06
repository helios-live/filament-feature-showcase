<?php

declare(strict_types=1);

namespace HeliosLive\FilamentFeatureShowcase;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\View\PanelsRenderHook;

class FeatureShowcasePlugin implements Plugin
{
    protected bool $showSidebarVersion = true;

    protected bool $showButton = true;

    protected string $buttonPosition = 'bottom-left';

    final public function __construct() {}

    public static function make(): static
    {
        return app(static::class);
    }

    public function getId(): string
    {
        return 'filament-feature-showcase';
    }

    public function showSidebarVersion(bool $show = true): static
    {
        $this->showSidebarVersion = $show;

        return $this;
    }

    public function getShowSidebarVersion(): bool
    {
        return $this->showSidebarVersion;
    }

    public function showButton(bool $show = true): static
    {
        $this->showButton = $show;

        return $this;
    }

    public function getShowButton(): bool
    {
        return $this->showButton;
    }

    public function buttonPosition(string $position): static
    {
        $this->buttonPosition = $position;

        return $this;
    }

    public function getButtonPosition(): string
    {
        return $this->buttonPosition;
    }

    public function register(Panel $panel): void
    {
        $panel->renderHook(
            PanelsRenderHook::BODY_END,
            fn () => view('filament-feature-showcase::feature-showcase', [
                'buttonPosition' => $this->buttonPosition,
                'showButton' => $this->showButton,
            ]),
        );

        if ($this->showSidebarVersion) {
            $panel->renderHook(
                PanelsRenderHook::SIDEBAR_FOOTER,
                fn () => view('filament-feature-showcase::sidebar-version'),
            );
        }
    }

    public function boot(Panel $panel): void {}
}
