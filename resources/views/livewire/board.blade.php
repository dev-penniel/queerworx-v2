<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new
#[Layout('components.layouts.app.frontend')]
class extends Component {
}; ?>

<main class="min-h-screen bg-[#171136] text-white">
    <section class="relative overflow-hidden py-20 sm:py-24">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_75%_0%,rgba(217,134,8,0.2),transparent_34%),linear-gradient(180deg,#211146_0%,#171136_100%)]"></div>

        <div class="relative mx-auto max-w-7xl px-6">
            <div class="max-w-3xl">
                <p class="text-sm font-bold uppercase tracking-wide text-[#D98608]">Governance</p>
                <h1 class="mt-2 text-4xl font-bold tracking-normal text-white sm:text-5xl">Board</h1>
                <p class="mt-4 text-base leading-7 text-white/60">
                    Our Board provides oversight, accountability, and strategic direction for Queer WorX.
                </p>
            </div>
        </div>
    </section>
</main>
