<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new
#[Layout('components.layouts.app.frontend')]
class extends Component {
}; ?>

@php
    $membersByType = fn (string $type) => \App\Models\TeamMember::where('is_active', true)
        ->where('member_type', $type)
        ->orderBy('sort_order')
        ->latest()
        ->get();

    $pyramidRows = fn ($members) => [
        $members->slice(0, 3),
        $members->slice(3, 2),
        $members->slice(5, 1),
        $members->slice(6),
    ];

    $boardMembers = $membersByType('board');
    $advisoryMembers = $membersByType('advisory');
@endphp

<main class="min-h-screen bg-[#111429] text-white">
    <section class="relative overflow-hidden py-20 sm:py-24">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_0%,rgba(230,30,92,0.2),transparent_34%),linear-gradient(180deg,#211146_0%,#111429_100%)]"></div>

        <div class="relative mx-auto max-w-7xl px-6">
            <div class="mx-auto max-w-3xl text-center">
                <h1 class="mt-2 text-4xl font-bold tracking-normal text-[#E61E5C] sm:text-5xl">Our Board</h1>
                <p class="mt-4 text-base leading-7 text-white/60">
                    Our Board provides oversight, accountability, and strategic direction for Queer WorX.
                </p>
            </div>

            @foreach ([
                ['title' => 'Our Board', 'members' => $boardMembers, 'empty' => 'Board member profiles will appear here once they are added in the admin dashboard.'],
                ['title' => 'Community Advisory', 'members' => $advisoryMembers, 'empty' => 'Community Advisory profiles will appear here once they are added in the admin dashboard.'],
            ] as $section)
                <section class="{{ $loop->first ? 'mt-14' : 'mt-20 border-t border-white/10 pt-16' }}">
                    @unless ($loop->first)
                        <div class="mx-auto max-w-3xl text-center">
                            <h2 class="text-3xl font-bold text-[#E61E5C] sm:text-4xl">{{ $section['title'] }}</h2>
                        </div>
                    @endunless

                    @if ($section['members']->isNotEmpty())
                        <div class="mt-10 space-y-8">
                            @foreach ($pyramidRows($section['members']) as $rowIndex => $row)
                                @continue($row->isEmpty())
                                <div @class([
                                    'grid gap-6',
                                    'lg:grid-cols-3' => $rowIndex === 0,
                                    'mx-auto max-w-[560px] sm:grid-cols-2' => $rowIndex === 1,
                                    'mx-auto max-w-[240px]' => $rowIndex === 2,
                                    'mx-auto max-w-5xl sm:grid-cols-2 lg:grid-cols-3' => $rowIndex > 2,
                                ])>
                                    @foreach ($row as $member)
                                        <article class="group overflow-hidden rounded-[8px] border border-white/10 bg-white/[0.05] p-5 text-center shadow-2xl shadow-black/20 transition hover:-translate-y-1 hover:border-[#E61E5C]/50">
                                            <div class="mx-auto aspect-square w-full overflow-hidden rounded-[8px] bg-black/25" style="max-width: 240px;">
                                                @if ($member->photo_path)
                                                    <img src="{{ route('media.show', ['path' => $member->photo_path]) }}" alt="{{ $member->name }}" class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                                                @else
                                                    <div class="flex h-full w-full items-center justify-center bg-[linear-gradient(135deg,#E61E5C_0%,#7646E8_100%)] text-3xl font-bold text-white/85">
                                                        {{ collect(explode(' ', $member->name))->map(fn ($part) => mb_substr($part, 0, 1))->take(2)->join('') }}
                                                    </div>
                                                @endif
                                            </div>

                                            <h3 class="mt-5 text-xl font-bold text-white">{{ $member->name }}</h3>
                                            <p class="mt-2 text-sm font-semibold text-[#FFD83D]">{{ $member->role }}</p>
                                            @if ($member->bio)
                                                <p class="mt-3 text-sm leading-6 text-white/65">{{ $member->bio }}</p>
                                            @endif
                                        </article>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="mt-10 rounded-[8px] border border-white/10 bg-white/[0.05] p-8 text-center text-white/60">
                            {{ $section['empty'] }}
                        </div>
                    @endif
                </section>
            @endforeach
        </div>
    </section>
</main>
