<?php

declare(strict_types=1);

namespace HeliosLive\FilamentFeatureShowcase;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FeatureShowcaseServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-feature-showcase';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::$name)
            ->hasConfigFile()
            ->hasViews()
            ->hasRoute('web');
    }
}
