<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new
#[Layout('components.layouts.app.frontend')]
class extends Component {
}; ?>

<main class="min-h-screen bg-[#111429] text-white">
    <section class="relative overflow-hidden py-20 sm:py-24">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_40%_0%,rgba(255,216,61,0.18),transparent_34%),linear-gradient(180deg,#211146_0%,#111429_58%,#0b0d1d_100%)]"></div>

        <div class="relative mx-auto max-w-7xl px-6">
            <div class="max-w-3xl">
                <p class="text-sm font-bold uppercase tracking-wide text-[#FFD83D]">Events</p>
                <h1 class="mt-2 text-4xl font-bold tracking-normal text-white sm:text-5xl">Programs and community gatherings</h1>
                <p class="mt-4 text-base leading-7 text-white/65">
                    Explore Queer WorX programs, activities, community moments, and opportunities to connect.
                </p>
            </div>

            <div class="mt-10 grid gap-6 md:grid-cols-2">
                <a href="{{ route('programs') }}" class="rounded-[8px] border border-white/10 bg-white/[0.05] p-6 transition hover:border-[#E61E5C]/60 hover:bg-white/[0.08]">
                    <p class="text-sm font-bold uppercase tracking-wide text-[#E61E5C]">Programs</p>
                    <h2 class="mt-2 text-2xl font-bold text-white">View programs</h2>
                    <p class="mt-3 text-sm leading-6 text-white/60">Browse activities, photos, videos and PDFs uploaded for each program.</p>
                </a>

                <a href="{{ route('community') }}" class="rounded-[8px] border border-white/10 bg-white/[0.05] p-6 transition hover:border-[#14A84D]/60 hover:bg-white/[0.08]">
                    <p class="text-sm font-bold uppercase tracking-wide text-[#14A84D]">Community</p>
                    <h2 class="mt-2 text-2xl font-bold text-white">Community updates</h2>
                    <p class="mt-3 text-sm leading-6 text-white/60">Find community-centered updates, gatherings and connection points.</p>
                </a>
            </div>
        </div>
    </section>
</main>
