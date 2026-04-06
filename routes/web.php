<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])->group(function () {
    Route::post(config('filament-feature-showcase.dismiss_route', '/admin/dismiss-version-showcase'), function () {
        $user = auth()->user();
        $version = request()->input('version');
        $userColumn = config('filament-feature-showcase.user_column', 'preferences');
        $preferenceKey = config('filament-feature-showcase.preference_key', 'last_seen_version');

        if ($user && $version) {
            $data = $user->{$userColumn} ?? [];
            $data[$preferenceKey] = $version;
            $user->{$userColumn} = $data;
            $user->save();
        }

        return response()->json(['ok' => true]);
    })->name('filament-feature-showcase.dismiss');
});
