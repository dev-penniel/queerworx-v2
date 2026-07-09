<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
        <style>
            body,
            [data-flux-heading],
            [data-flux-subheading],
            label {
                color: #ffffff !important;
            }

            [data-flux-subheading],
            .text-zinc-600,
            .dark\:text-zinc-400 {
                color: rgba(255, 255, 255, 0.72) !important;
            }

            input,
            select,
            textarea,
            [data-flux-input],
            [data-flux-control] {
                background-color: #0b0d1d !important;
                border-color: rgba(255, 255, 255, 0.16) !important;
                color: #ffffff !important;
            }

            input::placeholder,
            textarea::placeholder {
                color: rgba(255, 255, 255, 0.48) !important;
            }

            a,
            [data-flux-link] {
                color: #d8c7ff !important;
            }

            button,
            [data-flux-button] {
                color: #ffffff !important;
            }

            [data-flux-button],
            button[type="submit"] {
                background-color: #14A84D !important;
                border-color: #14A84D !important;
                color: #ffffff !important;
            }

            [data-flux-button]:hover,
            [data-flux-button]:focus-visible,
            [data-flux-button]:active,
            button[type="submit"]:hover,
            button[type="submit"]:focus-visible,
            button[type="submit"]:active {
                background-color: #0f7a38 !important;
                border-color: #14A84D !important;
                color: #ffffff !important;
                box-shadow: 0 0 0 3px rgba(20, 168, 77, 0.28) !important;
            }
        </style>
    </head>
    <body class="min-h-screen bg-[#111429] text-white antialiased dark:bg-[#111429]">
        <div class="flex min-h-svh flex-col items-center justify-center gap-6 bg-[radial-gradient(circle_at_30%_0%,rgba(230,30,92,0.22),transparent_34%),linear-gradient(180deg,#211146_0%,#111429_58%,#0b0d1d_100%)] p-6 md:p-10">
            <div class="flex w-full max-w-sm flex-col gap-2">
                <a href="{{ route('home') }}" class="flex flex-col items-center gap-2 font-medium" wire:navigate>
                    <img
                        src="{{ asset('images/qw-logo-latest-trimmed.png') }}"
                        alt="Queer WorX"
                        class="h-16 w-auto object-contain"
                    >
                </a>
                <div class="flex flex-col gap-6">
                    {{ $slot }}
                </div>
            </div>
        </div>
        @fluxScripts
    </body>
</html>
