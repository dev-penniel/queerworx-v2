<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new
#[Layout('components.layouts.app.frontend')]
class extends Component {
}; ?>

<main class="min-h-screen bg-[#111429] text-white">
    <section class="relative overflow-hidden py-20 sm:py-24">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_45%_0%,rgba(20,168,77,0.2),transparent_34%),linear-gradient(180deg,#211146_0%,#111429_58%,#0b0d1d_100%)]"></div>

        <div class="relative mx-auto max-w-5xl px-6">
            <p class="text-sm font-bold uppercase tracking-wide text-[#14A84D]"></p>
            <h1 class="mt-2 text-4xl font-bold tracking-normal text-white sm:text-5xl">Community</h1>
            <p class="mt-4 max-w-3xl text-base leading-7 text-white/65">
                A home for Queer WorX community updates, gatherings, stories and shared moments.
            </p>

            <div class="mt-10 rounded-[8px] border border-white/10 bg-white/[0.05] p-6 text-white/65">
                
            </div>
        </div>
    </section>
</main>
