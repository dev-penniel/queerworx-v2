<?php

use App\Models\Partner;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new
#[Layout('components.layouts.app.frontend')]
class extends Component {
}; ?>

@php
    $partners = Partner::where('is_active', true)->orderBy('sort_order')->latest()->get();
@endphp

<main class="min-h-screen bg-[#111429] text-white">
    <section class="relative overflow-hidden py-20 sm:py-24">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_0%,rgba(230,30,92,0.2),transparent_34%),linear-gradient(180deg,#211146_0%,#111429_100%)]"></div>

        <div class="relative mx-auto max-w-7xl px-6">
            <div class="mx-auto max-w-3xl text-center">
                <p class="text-sm font-bold uppercase tracking-wide text-[#E61E5C]"></p>
                <h1 class="mt-2 text-4xl font-bold tracking-normal text-[#E61E5C] sm:text-5xl">Our Partners</h1>
                <p class="mt-4 text-base leading-7 text-white/60">
                    Our partners help Queer WorX build inclusive spaces, expand opportunities, and support LGBTIQ+ communities through shared action.
                </p>
            </div>

            <div class="mt-14 grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @forelse ($partners as $partner)
                    <article class="group rounded-[8px] border border-white/10 bg-white/[0.05] p-5 text-center shadow-2xl shadow-black/20 transition hover:-translate-y-1 hover:border-[#E61E5C]/50">
                        @if ($partner->website_url)
                            <a href="{{ $partner->website_url }}" target="_blank" rel="noopener noreferrer" class="block" aria-label="Visit {{ $partner->name }}">
                        @endif
                            <div class="mx-auto flex aspect-square w-full max-w-[240px] items-center justify-center overflow-hidden rounded-[8px] bg-white p-5">
                                @if ($partner->logo_path)
                                    <img src="{{ \Illuminate\Support\Facades\Storage::url($partner->logo_path) }}" alt="{{ $partner->name }}" class="h-full w-full object-contain transition duration-300 group-hover:scale-105">
                                @else
                                    <span class="text-3xl font-bold text-[#7646E8]">{{ collect(explode(' ', $partner->name))->map(fn ($part) => mb_substr($part, 0, 1))->take(2)->join('') }}</span>
                                @endif
                            </div>
                        @if ($partner->website_url)
                            </a>
                        @endif
                        <h2 class="mt-5 text-xl font-bold text-white">{{ $partner->name }}</h2>
                        @if ($partner->website_url)
                            <a href="{{ $partner->website_url }}" target="_blank" rel="noopener noreferrer" class="mt-2 inline-flex text-sm font-semibold text-[#FFD83D] transition hover:text-white">Visit website ↗</a>
                        @endif
                    </article>
                @empty
                    <div class="col-span-full rounded-[8px] border border-white/10 bg-white/[0.05] p-8 text-center text-white/60">
                        Partner profiles will appear here once they are added in the admin dashboard.
                    </div>
                @endforelse
            </div>
        </div>
    </section>
</main>
