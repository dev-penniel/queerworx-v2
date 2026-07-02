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
        </style>
    </head>
    <body class="min-h-screen bg-[#111429] text-white antialiased dark:bg-[#111429]">
        <div class="flex min-h-svh flex-col items-center justify-center gap-6 bg-[radial-gradient(circle_at_30%_0%,rgba(230,30,92,0.22),transparent_34%),linear-gradient(180deg,#211146_0%,#111429_58%,#0b0d1d_100%)] p-6 md:p-10">
            <div class="flex w-full max-w-sm flex-col gap-2">
                <a href="{{ route('home') }}" class="flex flex-col items-center gap-2 font-medium" wire:navigate>
                    <span class="mb-1 flex h-14 w-14 items-center justify-center rounded-full bg-[linear-gradient(135deg,#E61E5C_0%,#F05A12_18%,#FFD83D_34%,#14A84D_52%,#149CB9_72%,#7646E8_100%)] text-2xl font-bold text-white shadow-lg shadow-black/25">
                        Q
                    </span>
                    <span class="text-xl font-bold text-white">Queer<span class="text-purple-300">WorX</span></span>
                </a>
                <div class="flex flex-col gap-6">
                    {{ $slot }}
                </div>
            </div>
        </div>
        @fluxScripts
    </body>
</html>
