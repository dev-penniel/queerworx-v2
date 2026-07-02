<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new
#[Layout('components.layouts.app.frontend')]
class extends Component {
}; ?>

@php
    $teamMembers = \App\Models\TeamMember::where('is_active', true)
        ->orderBy('sort_order')
        ->latest()
        ->take(7)
        ->get();

    $teamRows = [
        $teamMembers->slice(0, 3),
        $teamMembers->slice(3, 2),
        $teamMembers->slice(5, 2),
    ];
@endphp

<main class="min-h-screen bg-[#111429] text-white">
    <section class="relative overflow-hidden py-20 sm:py-24">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_0%,rgba(230,30,92,0.2),transparent_34%),linear-gradient(180deg,#211146_0%,#111429_100%)]"></div>

        <div class="relative mx-auto max-w-7xl px-6">
            <div class="mx-auto max-w-3xl text-center">
                <p class="text-sm font-bold uppercase tracking-wide text-[#E61E5C]">People</p>
                <h1 class="mt-2 text-4xl font-bold tracking-normal text-white sm:text-5xl">Our Team</h1>
                <p class="mt-4 text-base leading-7 text-white/60">
                    The Queer WorX team leads our day-to-day community, advocacy, wellness, and economic empowerment work.
                </p>
            </div>

            <div class="mt-14 space-y-8">
                @forelse ($teamRows as $rowIndex => $row)
                    @if ($row->isNotEmpty())
                        <div class="grid gap-6 sm:grid-cols-2 {{ $rowIndex === 0 ? 'lg:grid-cols-3' : 'mx-auto lg:max-w-[820px] lg:grid-cols-2' }}">
                            @foreach ($row as $member)
                                <article class="group overflow-hidden rounded-[8px] border border-white/10 bg-white/[0.05] p-5 text-center shadow-2xl shadow-black/20 transition hover:-translate-y-1 hover:border-[#E61E5C]/50">
                                    <div class="mx-auto aspect-square w-full overflow-hidden rounded-[8px] bg-black/25" style="max-width: 240px;">
                                        @if ($member->photo_path)
                                            <img src="{{ \Illuminate\Support\Facades\Storage::url($member->photo_path) }}" alt="{{ $member->name }}" class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                                        @else
                                            <div class="flex h-full w-full items-center justify-center bg-[linear-gradient(135deg,#E61E5C_0%,#7646E8_100%)] text-3xl font-bold text-white/85">
                                                {{ collect(explode(' ', $member->name))->map(fn ($part) => mb_substr($part, 0, 1))->take(2)->join('') }}
                                            </div>
                                        @endif
                                    </div>

                                    <h2 class="mt-5 text-xl font-bold text-white">{{ $member->name }}</h2>
                                    <p class="mt-2 text-sm font-semibold text-[#FFD83D]">{{ $member->role }}</p>
                                </article>
                            @endforeach
                        </div>
                    @endif
                @empty
                    <div class="rounded-[8px] border border-white/10 bg-white/[0.05] p-8 text-center text-white/60">
                        Team member profiles will appear here once they are added in the admin dashboard.
                    </div>
                @endforelse
            </div>
        </div>
    </section>
</main>
