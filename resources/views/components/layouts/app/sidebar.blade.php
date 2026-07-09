<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
        <style>
            [data-flux-sidebar] [data-flux-navlist-item],
            [data-flux-sidebar] .admin-sidebar-brand {
                color: rgba(255, 255, 255, 0.82) !important;
                transition: background 180ms ease, border-color 180ms ease, color 180ms ease, box-shadow 180ms ease, transform 180ms ease;
            }

            [data-flux-sidebar] [data-flux-navlist-item]:hover,
            [data-flux-sidebar] [data-flux-navlist-item]:focus-visible,
            [data-flux-sidebar] [data-flux-navlist-item][data-current],
            [data-flux-sidebar] .admin-sidebar-brand:hover,
            [data-flux-sidebar] .admin-sidebar-brand:focus-visible {
                border-color: transparent !important;
                color: #ffffff !important;
                background:
                    linear-gradient(rgba(33, 17, 70, 0.94), rgba(33, 17, 70, 0.94)) padding-box,
                    linear-gradient(135deg, #E61E5C 0%, #F05A12 18%, #FFD83D 34%, #14A84D 52%, #149CB9 72%, #7646E8 100%) border-box !important;
                box-shadow: 0 10px 26px rgba(0, 0, 0, 0.22), inset 0 0 0 1px rgba(255, 255, 255, 0.04);
            }

            [data-flux-sidebar] [data-flux-navlist-item]:hover,
            [data-flux-sidebar] .admin-sidebar-brand:hover {
                transform: translateX(2px);
            }

            [data-flux-main],
            [data-flux-main] [data-flux-heading],
            [data-flux-main] [data-flux-subheading],
            [data-flux-main] [data-flux-breadcrumbs],
            [data-flux-main] [data-flux-breadcrumbs] a,
            [data-flux-main] label,
            [data-flux-main] table,
            [data-flux-main] td,
            [data-flux-main] th {
                color: #ffffff !important;
            }

            [data-flux-main] [data-flux-text],
            [data-flux-main] .text-zinc-500,
            [data-flux-main] .text-zinc-600,
            [data-flux-main] .text-gray-500,
            [data-flux-main] .text-gray-600,
            [data-flux-main] .dark\:text-zinc-400,
            [data-flux-main] .dark\:text-zinc-500 {
                color: rgba(255, 255, 255, 0.74) !important;
            }

            [data-flux-main] input,
            [data-flux-main] textarea,
            [data-flux-main] select {
                color: #ffffff !important;
            }

            [data-flux-main] input::placeholder,
            [data-flux-main] textarea::placeholder {
                color: rgba(255, 255, 255, 0.52) !important;
            }

            [data-flux-main] thead tr {
                background: rgba(255, 255, 255, 0.09) !important;
            }

            [data-flux-main] tbody tr:hover {
                background: rgba(255, 255, 255, 0.07) !important;
            }

            [data-flux-main] .bg-white,
            [data-flux-main] .bg-gray-50,
            [data-flux-main] .bg-gray-100,
            [data-flux-main] .bg-zinc-50,
            [data-flux-main] .bg-zinc-100 {
                background-color: rgba(255, 255, 255, 0.06) !important;
                border-color: rgba(255, 255, 255, 0.12) !important;
                color: #ffffff !important;
            }

            [data-flux-main] .bg-white *,
            [data-flux-main] .bg-gray-50 *,
            [data-flux-main] .bg-gray-100 *,
            [data-flux-main] .bg-zinc-50 *,
            [data-flux-main] .bg-zinc-100 * {
                color: inherit !important;
            }

            [data-flux-main] .bg-white input,
            [data-flux-main] .bg-white textarea,
            [data-flux-main] .bg-white select {
                background-color: #0b0d1d !important;
                border-color: rgba(255, 255, 255, 0.16) !important;
                color: #ffffff !important;
            }

            [data-flux-main] [data-flux-button],
            [data-flux-main] button,
            [data-flux-main] a[role="button"],
            [data-flux-main] .cursor-pointer {
                transition: background-color 160ms ease, border-color 160ms ease, color 160ms ease, box-shadow 160ms ease, transform 160ms ease !important;
            }

            [data-flux-main] [data-flux-button],
            [data-flux-main] [data-flux-button][data-variant="primary"],
            [data-flux-main] button[type="submit"],
            [data-flux-main] a [data-flux-button] {
                background-color: #14A84D !important;
                border-color: #14A84D !important;
                color: #ffffff !important;
            }

            [data-flux-main] [data-flux-button]:hover,
            [data-flux-main] [data-flux-button]:focus-visible,
            [data-flux-main] [data-flux-button]:active,
            [data-flux-main] button:hover,
            [data-flux-main] button:focus-visible,
            [data-flux-main] button:active,
            [data-flux-main] a[role="button"]:hover,
            [data-flux-main] a[role="button"]:focus-visible,
            [data-flux-main] a[role="button"]:active {
                background-color: #0f7a38 !important;
                border-color: #14A84D !important;
                color: #ffffff !important;
                box-shadow: 0 0 0 3px rgba(20, 168, 77, 0.28) !important;
            }

            [data-flux-main] [data-flux-button]:active,
            [data-flux-main] button:active,
            [data-flux-main] a[role="button"]:active {
                transform: translateY(1px);
            }

            [data-flux-main] [data-flux-button][data-variant="danger"],
            [data-flux-main] button.text-\[\#E61E5C\],
            [data-flux-main] .text-\[\#E61E5C\] {
                color: #ff8bad !important;
            }

            [data-flux-main] [data-flux-button][data-variant="danger"]:hover,
            [data-flux-main] [data-flux-button][data-variant="danger"]:focus-visible,
            [data-flux-main] [data-flux-button][data-variant="danger"]:active {
                background-color: #14A84D !important;
                border-color: #14A84D !important;
                color: #ffffff !important;
            }

            [data-flux-main] [data-flux-menu],
            [data-flux-main] [role="menu"],
            [data-flux-main] [data-flux-select-options],
            [data-flux-main] [data-flux-dropdown] {
                background-color: #111429 !important;
                border-color: rgba(255, 255, 255, 0.14) !important;
                color: #ffffff !important;
            }

            [data-flux-main] [data-flux-menu] *,
            [data-flux-main] [role="menu"] *,
            [data-flux-main] [data-flux-select-options] * {
                color: #ffffff !important;
            }

            [data-flux-main] [data-flux-menu] [data-flux-menu-item]:hover,
            [data-flux-main] [role="menuitem"]:hover,
            [data-flux-main] [data-flux-select-option]:hover,
            [data-flux-main] [data-flux-select-option][data-active] {
                background-color: #14A84D !important;
                color: #ffffff !important;
            }

            [data-flux-main] .ql-toolbar,
            [data-flux-main] .ql-container,
            [data-flux-main] .ql-editor {
                background-color: #0b0d1d !important;
                border-color: rgba(255, 255, 255, 0.16) !important;
                color: #ffffff !important;
            }

            [data-flux-main] .ql-toolbar button,
            [data-flux-main] .ql-toolbar .ql-picker,
            [data-flux-main] .ql-toolbar .ql-picker-label {
                color: #ffffff !important;
            }

            [data-flux-main] .ql-toolbar button:hover,
            [data-flux-main] .ql-toolbar button:focus,
            [data-flux-main] .ql-toolbar button.ql-active,
            [data-flux-main] .ql-toolbar .ql-picker-label:hover {
                color: #14A84D !important;
            }
        </style>
    </head>
    <body class="min-h-screen bg-[#111429] text-white dark:bg-[#111429]">
        <flux:sidebar sticky stashable class="w-[200px] border-r border-white/10 bg-[#211146] text-white dark:border-white/10 dark:bg-[#211146]">

            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <a href="{{ route('dashboard') }}" class="admin-sidebar-brand mr-5 flex items-center space-x-2 rounded-lg border border-transparent px-3 py-2" wire:navigate>
                {{-- <x-app-logo /> --}}
                <h2 class="font-bold">Queer WorX</h2>
            </a>

            <flux:navlist variant="outline">

                <flux:navlist.group :heading="__('PAGES')" class="grid">

                    <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>{{ __('Dashboard') }}</flux:navlist.item>

                    <flux:navlist.item icon="newspaper" :href="route('articles.index')" :current="request()->routeIs('articles.index')" wire:navigate>{{ __('Articles') }}</flux:navlist.item>
                    <flux:navlist.item icon="chat-bubble-left-right" :href="route('comments.index')" :current="request()->routeIs('comments.index')" wire:navigate>{{ __('Comments') }}</flux:navlist.item>

                    <flux:navlist.item icon="clipboard-document-list" :href="route('categories.index')" :current="request()->routeIs('categories.index')" wire:navigate>{{ __('Categories') }}</flux:navlist.item>

                    <flux:navlist.item icon="archive-box" :href="route('resources.index')" :current="request()->routeIs('resources.index')" wire:navigate>{{ __('Resources') }}</flux:navlist.item>

                    <flux:navlist.item icon="document-text" :href="route('admin.documents')" :current="request()->routeIs('admin.documents')" wire:navigate>{{ __('Policies & Financials') }}</flux:navlist.item>

                    <flux:navlist.item icon="identification" :href="route('admin.team-members')" :current="request()->routeIs('admin.team-members')" wire:navigate>{{ __('Team Members') }}</flux:navlist.item>

                    <flux:navlist.item icon="user-group" :href="route('admin.join-us')" :current="request()->routeIs('admin.join-us')" wire:navigate>{{ __('Join Us Page') }}</flux:navlist.item>

                    <flux:navlist.item icon="radio" :href="route('admin.support')" :current="request()->routeIs('admin.support')" wire:navigate>{{ __('Support Page') }}</flux:navlist.item>

                    <flux:navlist.item icon="squares-2x2" :href="route('admin.programs')" :current="request()->routeIs('admin.programs')" wire:navigate>{{ __('Programs') }}</flux:navlist.item>

                    <flux:navlist.item icon="user-group" :href="route('subscribers.index')" :current="request()->routeIs('subscribers.index')" wire:navigate>{{ __('Subscribers') }}</flux:navlist.item>

                    <flux:navlist.item icon="radio" :href="route('adverts.index')" :current="request()->routeIs('adverts.index')" wire:navigate>{{ __('Adverts') }}</flux:navlist.item>


                    
                </flux:navlist.group>
                
            </flux:navlist>

            <flux:spacer />

            @can('user-list')

                <flux:navlist.group :heading="__('ADMINISTRATION')" class="grid">

                    <flux:navlist variant="outline">
                        
                        @can('user-list')
                            <flux:navlist.item icon="user-group" :href="route('users.index')" :current="request()->routeIs('users.index')" wire:navigate>{{ __('Users') }}</flux:navlist.item>
                        @endcan

                        @can('role-list')
                            <flux:navlist.item icon="user-group" :href="route('roles.index')" :current="request()->routeIs('roles.index')" wire:navigate>{{ __('Roles') }}</flux:navlist.item>
                        @endcan

                        {{-- <flux:navlist.item icon="folder-git-2" href="https://github.com/laravel/livewire-starter-kit" target="_blank">
                        {{ __('Repository') }}
                        </flux:navlist.item>

                        <flux:navlist.item icon="book-open-text" href="https://laravel.com/docs/starter-kits" target="_blank">
                        {{ __('Documentation') }}
                        </flux:navlist.item> --}}
                    </flux:navlist>

                </flux:navlist.group>
                
            @endcan

            <!-- Desktop User Menu -->
            <flux:dropdown position="bottom" align="start">
                <flux:profile
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevrons-up-down"
                />

                <flux:menu class="w-[220px]">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-left text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="border-b border-white/10 bg-[#211146] text-white lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-left text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @fluxScripts
    </body>
    <!-- Include the Quill library -->
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
</html>
