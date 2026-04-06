@php
    $user = auth()->user();
    $currentVersion = config('filament-feature-showcase.current');
    $changelog = config('filament-feature-showcase.changelog', []);
    $userColumn = config('filament-feature-showcase.user_column', 'preferences');
    $preferenceKey = config('filament-feature-showcase.preference_key', 'last_seen_version');
    $dismissRoute = config('filament-feature-showcase.dismiss_route', '/admin/dismiss-version-showcase');
    $lastSeen = $user ? ($user->{$userColumn}[$preferenceKey] ?? null) : null;
    $showOnLoad = $user && $currentVersion && $lastSeen !== $currentVersion && isset($changelog[$currentVersion]);
    $hasUnseen = $showOnLoad;

    // Sort versions newest first
    $versions = collect($changelog)->keys()->sort(function ($a, $b) {
        return version_compare($b, $a);
    })->values()->all();

    $position = $buttonPosition ?? 'bottom-left';
    $positionClasses = match ($position) {
        'bottom-right' => 'bottom-14 right-4',
        'top-left' => 'top-4 left-4',
        'top-right' => 'top-4 right-4',
        default => 'bottom-14 left-4',
    };
@endphp

<div
    x-data="{
        open: {{ $showOnLoad ? 'true' : 'false' }},
        expanded: '{{ $currentVersion }}',
        toggle(version) {
            this.expanded = this.expanded === version ? null : version;
        }
    }"
>
    {{-- Persistent "What's New" button --}}
    @if($showButton ?? true)
    <button
        x-show="!open"
        x-on:click="open = true; expanded = '{{ $currentVersion }}';"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-90"
        x-transition:enter-end="opacity-100 scale-100"
        class="fixed {{ $positionClasses }} z-40 flex h-10 w-10 items-center justify-center rounded-full shadow-lg transition hover:scale-110 focus:outline-none focus:ring-2 focus:ring-offset-2"
        style="background: var(--primary-400); --tw-ring-color: var(--primary-400); color: white;"
        title="What's New"
    >
        <x-filament::icon icon="heroicon-m-sparkles" class="h-5 w-5" />
        @if($hasUnseen)
            <span class="absolute -right-0.5 -top-0.5 flex h-3 w-3">
                <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-red-400 opacity-75"></span>
                <span class="relative inline-flex h-3 w-3 rounded-full bg-red-500"></span>
            </span>
        @endif
    </button>
    @endif

    {{-- Modal backdrop --}}
    <template x-teleport="body">
        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            x-on:keydown.escape.window="open = false"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
            style="display: none;"
        >
            {{-- Modal --}}
            <div
                x-show="open"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="relative mx-4 w-full max-w-2xl rounded-2xl bg-white shadow-2xl dark:bg-gray-900"
                @click.outside="open = false"
            >
                {{-- Header --}}
                <div class="relative overflow-hidden rounded-t-2xl px-8 py-8 text-white"
                     style="background: linear-gradient(135deg, var(--primary-400), var(--primary-600));">
                    <div class="relative z-10">
                        <div class="mb-1 inline-block rounded-full bg-white/20 px-3 py-1 text-xs font-semibold uppercase tracking-wider">
                            Version {{ $currentVersion }}
                        </div>
                        <h2 class="text-2xl font-bold">What's New</h2>
                        <p class="mt-1 text-sm text-white/80">See what's changed across all versions</p>
                    </div>
                    {{-- Decorative circles --}}
                    <div class="absolute -right-6 -top-6 h-32 w-32 rounded-full bg-white/10"></div>
                    <div class="absolute -bottom-4 -right-2 h-20 w-20 rounded-full bg-white/5"></div>
                </div>

                {{-- Versions accordion --}}
                <div class="max-h-[55vh] overflow-y-auto px-8 py-6">
                    @foreach($versions as $version)
                        @php $entry = $changelog[$version]; @endphp
                        <div class="mb-3 last:mb-0">
                            {{-- Version header (clickable) --}}
                            <button
                                x-on:click="toggle('{{ $version }}')"
                                class="flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-left transition hover:bg-gray-50 dark:hover:bg-gray-800"
                            >
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold"
                                          style="{{ $version === $currentVersion ? 'background: color-mix(in oklch, var(--primary-400) 15%, white); color: var(--primary-600);' : 'background: rgb(243 244 246); color: rgb(107 114 128);' }}">
                                        v{{ $version }}
                                        @if($version === $currentVersion)
                                            <span class="ml-1 text-[10px] opacity-70">latest</span>
                                        @endif
                                    </span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $entry['title'] ?? "Version {$version}" }}
                                    </span>
                                </div>
                                <x-filament::icon
                                    icon="heroicon-m-chevron-down"
                                    class="ml-auto h-4 w-4 text-gray-400 transition-transform duration-200"
                                    x-bind:class="expanded === '{{ $version }}' ? 'rotate-180' : ''"
                                />
                            </button>

                            {{-- Version features (expandable) --}}
                            <div
                                x-show="expanded === '{{ $version }}'"
                                x-collapse
                            >
                                @if(!empty($entry['description']))
                                    <p class="px-3 pb-3 text-xs text-gray-500 dark:text-gray-400">{{ $entry['description'] }}</p>
                                @endif
                                <div class="grid grid-cols-1 gap-3 px-3 pb-4 sm:grid-cols-2">
                                    @foreach($entry['features'] ?? [] as $feature)
                                        <div class="rounded-xl border border-gray-100 p-4 transition hover:border-gray-300 hover:bg-gray-50 dark:border-gray-700 dark:hover:border-gray-600 dark:hover:bg-gray-800/50">
                                            <div class="mb-2 flex items-center gap-3">
                                                @if(!empty($feature['icon']))
                                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg"
                                                         style="background: color-mix(in oklch, var(--primary-400) 15%, white);">
                                                        <x-filament::icon
                                                            :icon="$feature['icon']"
                                                            class="h-5 w-5"
                                                            style="color: var(--primary-500);"
                                                        />
                                                    </div>
                                                @endif
                                                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">
                                                    {{ $feature['title'] }}
                                                </h3>
                                            </div>
                                            <p class="text-xs leading-relaxed text-gray-500 dark:text-gray-400">
                                                {{ $feature['description'] }}
                                            </p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Footer --}}
                <div class="flex items-center justify-end rounded-b-2xl border-t border-gray-100 px-8 py-4 dark:border-gray-700">
                    @if($showOnLoad)
                        <button
                            @click="
                                open = false;
                                fetch('{{ url($dismissRoute) }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || '{{ csrf_token() }}'
                                    },
                                    body: JSON.stringify({ version: '{{ $currentVersion }}' })
                                });
                            "
                            class="inline-flex items-center gap-2 rounded-lg px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition focus:outline-none focus:ring-2 focus:ring-offset-2"
                            style="background: var(--primary-400); --tw-ring-color: var(--primary-400);"
                            onmouseover="this.style.background='var(--primary-500)'"
                            onmouseout="this.style.background='var(--primary-400)'"
                        >
                            Got it, let's go!
                            <x-filament::icon icon="heroicon-m-arrow-right" class="h-4 w-4" />
                        </button>
                    @else
                        <button
                            @click="open = false"
                            class="inline-flex items-center gap-2 rounded-lg px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition focus:outline-none focus:ring-2 focus:ring-offset-2"
                            style="background: var(--primary-400); --tw-ring-color: var(--primary-400);"
                            onmouseover="this.style.background='var(--primary-500)'"
                            onmouseout="this.style.background='var(--primary-400)'"
                        >
                            Close
                            <x-filament::icon icon="heroicon-m-x-mark" class="h-4 w-4" />
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </template>
</div>
