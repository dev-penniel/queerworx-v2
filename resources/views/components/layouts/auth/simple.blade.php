<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
        <style>
            body,
            [data-flux-heading],
            label,
            [data-flux-label] {
                color: #ffffff !important;
            }

            [data-flux-subheading],
            .text-zinc-600,
            .dark\:text-zinc-400 {
                color: #d8d9ec !important;
            }

            input,
            select,
            textarea,
            [data-flux-input],
            [data-flux-control] {
                background-color: #0b0d1d !important;
                border-color: rgba(220, 220, 255, 0.32) !important;
                color: #ffffff !important;
            }

            input:focus,
            select:focus,
            textarea:focus,
            [data-flux-input]:focus-within,
            [data-flux-control]:focus-within {
                border-color: #b18aff !important;
                box-shadow: 0 0 0 3px rgba(177, 138, 255, 0.28) !important;
            }

            input::placeholder,
            textarea::placeholder {
                color: #a8aac3 !important;
            }

            a,
            [data-flux-link] {
                color: #e2d5ff !important;
                font-weight: 600;
                text-decoration: underline;
                text-underline-offset: 3px;
            }

            [data-flux-error],
            .text-red-500,
            .text-red-600 {
                color: #ffb4c7 !important;
            }

            .auth-card {
                background-color: #1a1a31 !important;
                border-color: rgba(220, 220, 255, 0.22) !important;
                box-shadow: 0 24px 60px rgba(0, 0, 0, 0.42) !important;
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
                        src="{{ asset('images/1df067aa-d966-491f-a61e-bdfc572c0075.png') }}"
                        alt="Queer WorX"
                        class="h-20 w-auto max-w-[240px] object-contain sm:h-24"
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
