<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new
#[Layout('components.layouts.app.frontend')]
class extends Component {
}; ?>

@php
    $programs = \App\Models\Program::where('is_active', true)
        ->orderBy('sort_order')
        ->orderBy('name')
        ->get();
    $impactStats = [
        ['value' => \App\Models\Subscriber::count(), 'label' => 'Lives Empowered'],
        ['value' => \App\Models\Program::count() + \App\Models\ProgramActivity::count(), 'label' => 'Programs & Events'],
        ['value' => \App\Models\Subscriber::where('interest', 'Support: Volunteer')->count(), 'label' => 'Volunteers'],
        ['value' => \App\Models\Subscriber::where('interest', 'Support: Partner')->count(), 'label' => 'Partner Organizations'],
    ];
    $accentColors = ['#E61E5C', '#F05A12', '#D98608', '#14A84D', '#149CB9', '#2E71FF', '#7646E8'];
@endphp

<main class="min-h-screen bg-[#111429] text-white">
    <style>
        .program-card:hover {
            border-color: var(--program-accent) !important;
        }

        .program-card:hover .program-card__cta {
            background-color: var(--program-accent) !important;
        }
    </style>

    <section class="relative overflow-hidden py-16 sm:py-20">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_50%_0%,rgba(118,70,232,0.26),transparent_38%),linear-gradient(180deg,#211146_0%,#111429_66%,#0b0d1d_100%)]"></div>

        <div class="relative mx-auto max-w-7xl px-6">
            <div class="mx-auto max-w-4xl text-center">
                <h1 class="text-4xl font-bold tracking-tight text-white sm:text-5xl">Our Programmes</h1>
                <div class="mx-auto mt-4 h-1 w-16 rounded-full bg-[#e61e5c]"></div>
                <p class="mt-5 text-base leading-7 text-white/70 sm:text-lg">
                    Queer WorX designs and implements programmes that strengthen economic justice, wellbeing, leadership, and human rights for LGBTIQ+ communities in Lesotho. Our work creates opportunities for individuals and communities to thrive through learning, advocacy, entrepreneurship, and collective action.
                </p>
            </div>

            <div class="mt-12 grid gap-6 sm:grid-cols-2 xl:grid-cols-4">
                @forelse ($programs as $index => $program)
                    @php
                        $accent = $accentColors[$index % count($accentColors)];
                    @endphp
                    <article class="program-card group flex overflow-hidden rounded-[18px] border border-white/10 bg-[#1a1a31] shadow-2xl shadow-black/20 transition hover:-translate-y-1" style="--program-accent: {{ $accent }}">
                        <a href="{{ route('programs.show', $program->id) }}" class="flex w-full flex-col" aria-label="Explore {{ $program->name }}">
                            <div class="aspect-[4/3] overflow-hidden bg-[#292142]">
                                @if ($program->cover_image_path)
                                    <img src="{{ \Illuminate\Support\Facades\Storage::url($program->cover_image_path) }}" alt="{{ $program->name }}" class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                                @else
                                    <div class="flex h-full items-center justify-center bg-[linear-gradient(135deg,#e61e5c_0%,#7646e8_100%)] text-5xl font-bold text-white/80">
                                        {{ mb_substr($program->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            <div class="flex flex-1 flex-col p-6 text-center">
                                <h2 class="text-2xl font-bold text-white">{{ $program->name }}</h2>
                                <div class="mx-auto mt-3 h-1 w-10 rounded-full" style="background-color: {{ $accent }}"></div>
                                @if ($program->summary)
                                    <p class="mt-5 text-sm leading-6 text-white/70">{{ $program->summary }}</p>
                                @endif
                                @if ($program->outcomes)
                                    <p class="mt-4 text-xs leading-5 text-white/45">{{ \Illuminate\Support\Str::limit($program->outcomes, 110) }}</p>
                                @endif
                                <span class="program-card__cta mt-6 inline-flex self-center rounded-full bg-[#e61e5c] px-5 py-2.5 text-sm font-bold text-white transition">Learn More <span class="ml-2">→</span></span>
                            </div>
                        </a>
                    </article>
                @empty
                    <div class="col-span-full rounded-[18px] border border-white/10 bg-white/[0.05] p-8 text-center text-white/60">
                        Programmes will appear here once they are added in the admin dashboard.
                    </div>
                @endforelse
            </div>

            <section class="mt-20 border-t border-white/10 pt-16 sm:pt-20">
                <div class="text-center">
                    <h2 class="text-3xl font-bold text-white">Your impact</h2>
                    <div class="mx-auto mt-3 h-1 w-16 rounded-full bg-[#e61e5c]"></div>
                    <p class="mx-auto mt-4 max-w-2xl text-white/60">The people, programmes, and partnerships that make this work possible.</p>
                </div>
                <div class="mt-10 grid gap-6 text-center md:grid-cols-4">
                    @foreach ($impactStats as $impact)
                        <div class="rounded-[18px] border border-[#e61e5c]/65 bg-[#1a1a31] p-7 shadow-lg shadow-black/10">
                            <p class="text-4xl font-bold text-pink-300">{{ number_format($impact['value']) }}</p>
                            <p class="mt-2 text-sm font-semibold text-white/80">{{ $impact['label'] }}</p>
                        </div>
                    @endforeach
                </div>
                <p class="mt-8 text-center text-sm font-semibold text-white/75"><span class="text-[#E61E5C]">&hearts;</span> Together, we are building a more inclusive tomorrow.</p>
            </section>
        </div>
    </section>
</main>
