# Filament Feature Showcase

A beautiful Filament plugin that displays a version changelog modal with an always-accessible "What's New" button. Keep your users informed about new features with an elegant, accordion-style changelog.

## Features

- Auto-show modal when a new version is released
- Accordion-style changelog showing all versions (newest first)
- Persistent floating "What's New" button with unseen version indicator
- Version label in the sidebar footer
- Fully configurable via a publishable config file
- Dark mode support
- Respects your Filament theme colors

## Installation

```bash
composer require helios-live/filament-feature-showcase
```

## Setup

### 1. Register the plugin

Add the plugin to your `AdminPanelProvider`:

```php
use HeliosLive\FilamentFeatureShowcase\FeatureShowcasePlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->plugin(FeatureShowcasePlugin::make());
}
```

### 2. Publish the config

```bash
php artisan vendor:publish --tag=filament-feature-showcase-config
```

### 3. User model requirement

Your `User` model must have a `preferences` JSON column (cast to `array`):

```php
protected $casts = [
    'preferences' => 'array',
];
```

If you don't have one, create a migration:

```bash
php artisan make:migration add_preferences_to_users_table
```

```php
Schema::table('users', function (Blueprint $table) {
    $table->json('preferences')->nullable();
});
```

## Configuration

Edit `config/filament-feature-showcase.php` to define your versions and features:

```php
return [
    'current' => '1.2.0',

    'preference_key' => 'last_seen_version',

    'dismiss_route' => '/admin/dismiss-version-showcase',

    'changelog' => [
        '1.2.0' => [
            'title' => 'Dark Mode Support',
            'description' => 'Full dark mode support across the platform.',
            'features' => [
                [
                    'icon' => 'heroicon-o-moon',
                    'title' => 'Dark Mode',
                    'description' => 'Toggle between light and dark themes.',
                ],
            ],
        ],

        '1.1.0' => [
            'title' => 'Search Improvements',
            'description' => 'Find what you need faster.',
            'features' => [
                [
                    'icon' => 'heroicon-o-magnifying-glass',
                    'title' => 'Global Search',
                    'description' => 'Search across all resources instantly.',
                ],
            ],
        ],
    ],
];
```

## Plugin Options

```php
FeatureShowcasePlugin::make()
    ->showSidebarVersion(true)     // Show version in sidebar footer (default: true)
    ->showButton(true)             // Show the floating "What's New" button (default: true)
    ->buttonPosition('bottom-left') // Button position: bottom-left, bottom-right, top-left, top-right
```

## How It Works

1. When a user logs in, the plugin checks their `last_seen_version` preference against `config('filament-feature-showcase.current')`
2. If the versions don't match, the modal auto-opens showing the latest version's features
3. Clicking "Got it, let's go!" marks the version as seen
4. The floating sparkles button is always available to re-open the changelog
5. All versions are shown in a collapsible accordion, newest first

## License

MIT
