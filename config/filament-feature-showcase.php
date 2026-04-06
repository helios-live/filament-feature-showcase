<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application Version
    |--------------------------------------------------------------------------
    |
    | The current version of your application. Bump this value with every
    | release. The feature showcase modal will automatically appear for
    | users who haven't seen the current version yet.
    |
    */

    'current' => '1.0.0',

    /*
    |--------------------------------------------------------------------------
    | User Preference Key
    |--------------------------------------------------------------------------
    |
    | The key used to store the last seen version in the user's preferences
    | JSON column. Change this if it conflicts with your existing schema.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | User Column
    |--------------------------------------------------------------------------
    |
    | The JSON column on your User model used to store preferences.
    | This column must be cast to 'array' or 'json' on your model.
    |
    */

    'user_column' => 'preferences',

    'preference_key' => 'last_seen_version',

    /*
    |--------------------------------------------------------------------------
    | Dismiss Route
    |--------------------------------------------------------------------------
    |
    | The URI for the POST endpoint that marks a version as seen.
    | This route is automatically registered by the package.
    |
    */

    'dismiss_route' => '/admin/dismiss-version-showcase',

    /*
    |--------------------------------------------------------------------------
    | Changelog
    |--------------------------------------------------------------------------
    |
    | Each entry is keyed by its version string and contains a title,
    | optional description, and a list of features. Each feature has
    | a title, description, and an optional icon (Heroicon name).
    |
    | Example:
    |
    | '1.0.0' => [
    |     'title' => 'Initial Release',
    |     'description' => 'Welcome to our platform!',
    |     'features' => [
    |         [
    |             'icon' => 'heroicon-o-sparkles',
    |             'title' => 'Feature Name',
    |             'description' => 'What this feature does.',
    |         ],
    |     ],
    | ],
    |
    */

    'changelog' => [

        '1.0.0' => [
            'title' => 'Welcome',
            'description' => 'We are excited to introduce the first release.',
            'features' => [
                [
                    'icon' => 'heroicon-o-sparkles',
                    'title' => 'Feature Showcase',
                    'description' => 'A beautiful changelog modal that keeps your users informed about new features.',
                ],
            ],
        ],

    ],

];
