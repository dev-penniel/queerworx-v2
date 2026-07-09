<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new
#[Layout('components.layouts.app.frontend')]
class extends Component {
}; ?>

@php
    $programs = \App\Models\Program::with([
            'activities' => fn ($query) => $query->with('media')->latest('activity_date')->latest(),
        ])
        ->withCount('activities')
        ->where('is_active', true)
        ->orderBy('sort_order')
        ->orderBy('name')
        ->get();

    $upcomingEvents = \App\Models\ProgramActivity::with(['program', 'media'])
        ->where('status', 'upcoming')
        ->where(function ($query) {
            $query->whereNull('activity_date')->orWhereDate('activity_date', '>=', now()->toDateString());
        })
        ->orderByRaw('activity_date is null')
        ->orderBy('activity_date')
        ->take(6)
        ->get();

    $heroEvent = $upcomingEvents->first()
        ?? \App\Models\ProgramActivity::with(['program', 'media'])->latest('activity_date')->latest()->first();

    $heroImage = $heroEvent?->featured_image_path
        ?: $heroEvent?->image_path
        ?: $heroEvent?->media->firstWhere('type', 'image')?->file_path
        ?: $programs->firstWhere('cover_image_path')?->cover_image_path;
@endphp

<main class="min-h-screen bg-[#111429] text-white">
    <section class="border-b border-white/10 bg-[#111429]">
        <div class="mx-auto grid max-w-7xl gap-10 px-6 py-12 md:grid-cols-[1fr_auto] md:items-center lg:py-16">
            <div>
                <p class="text-sm font-bold uppercase tracking-wide text-[#FFD83D]"> </p>
                <h1 class="mt-4 text-5xl font-bold tracking-normal text-white sm:text-6xl">Events</h1>
                <p class="mt-6 max-w-md text-base leading-7 text-white/70">
                    Explore Queer WorX gatherings, workshops, program activities, and community spaces created for learning, connection, and collective care.
                </p>
            </div>

            <div class="flex justify-center overflow-hidden rounded-[8px] border border-white/10 bg-white/[0.04] p-3 md:centre-left">
                @if ($heroImage)
                    <img src="{{ \Illuminate\Support\Facades\Storage::url($heroImage) }}" alt="Queer WorX events" class="aspect-square w-full max-w-[240px] rounded-[8px] object-cover">
                @else
                    <div class="aspect-square w-full max-w-[240px] rounded-[8px] bg-[linear-gradient(135deg,#211146_0%,#7646E8_45%,#111429_100%)]"></div>
                @endif
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-6 py-14">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="text-3xl font-bold text-white">Upcoming Events</h2>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-white/60"></p>
            </div>
            <a href="#events-by-program" class="text-sm font-semibold text-[#FFD83D] transition hover:text-white">Browse by program</a>
        </div>

        <div class="mt-8 grid gap-6 lg:grid-cols-3">
            @forelse ($upcomingEvents as $event)
                @php
                    $eventImage = $event->featured_image_path ?: $event->image_path ?: $event->media->firstWhere('type', 'image')?->file_path;
                @endphp
                <a href="{{ route('events.show', $event->id) }}" class="group overflow-hidden rounded-[8px] border border-white/10 bg-white/[0.04] p-5 transition hover:-translate-y-1 hover:border-[#FFD83D]/60">
                    <div class="grid place-items-center">
                        @if ($eventImage)
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($eventImage) }}" alt="{{ $event->title }}" class="mx-auto aspect-square w-full max-w-[240px] rounded-[8px] object-cover">
                        @else
                            <div class="mx-auto aspect-square w-full max-w-[240px] rounded-[8px] bg-white/[0.06]"></div>
                        @endif
                    </div>
                    <div class="pt-5">
                        <p class="text-xs font-semibold uppercase tracking-wide text-white/45">
                            {{ optional($event->activity_date)->format('M d, Y') ?? 'Date to be announced' }}
                            @if ($event->activity_time) · {{ $event->activity_time->format('H:i') }} @endif
                        </p>
                        <h3 class="mt-3 text-xl font-bold text-white group-hover:text-[#FFD83D]">{{ $event->title }}</h3>
                        <p class="mt-3 text-sm leading-6 text-white/60">{{ \Illuminate\Support\Str::limit($event->description, 120) }}</p>
                        <p class="mt-4 text-sm font-semibold text-white/70">{{ $event->venue ?: 'Venue to be announced' }}</p>
                    </div>
                </a>
            @empty
                <div class="rounded-[8px] border border-white/10 bg-white/[0.04] p-6 text-sm text-white/60 lg:col-span-3">
                    
                </div>
            @endforelse
        </div>
    </section>

    <section id="events-by-program" class="border-t border-white/10">
        <div class="mx-auto max-w-7xl px-6 py-14">
            <div>
                <h2 class="text-3xl font-bold text-white">Events by Program</h2>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-white/60">Browse events organized under active programs.</p>
            </div>

            <div class="mt-8 space-y-6">
                @forelse ($programs as $program)
                    @php
                        $previewMedia = collect([$program->cover_image_path])
                            ->merge($program->activities->flatMap(fn ($activity) => collect([$activity->featured_image_path, $activity->image_path])->merge($activity->media->where('type', 'image')->pluck('file_path'))))
                            ->filter()
                            ->unique()
                            ->take(3)
                            ->values();
                    @endphp
                    <article class="grid gap-6 rounded-[8px] border border-white/10 bg-white/[0.04] p-5 lg:grid-cols-[0.7fr_1.3fr] lg:items-center">
                        <div>
                            <h3 class="text-2xl font-bold text-white">{{ $program->name }}</h3>
                            @if ($program->summary)
                                <p class="mt-3 text-sm leading-6 text-white/60">{{ $program->summary }}</p>
                            @endif
                            <a href="{{ route('programs.show', $program->id) }}" class="mt-6 inline-flex rounded-full border border-[#FFD83D]/60 px-5 py-3 text-sm font-bold text-[#FFD83D] transition hover:bg-[#FFD83D] hover:text-[#111429]">
                                View Program Events
                            </a>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-3">
                            @forelse ($previewMedia as $path)
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($path) }}" alt="{{ $program->name }}" class="mx-auto aspect-square w-full max-w-[240px] rounded-[8px] object-cover">
                            @empty
                                <div class="mx-auto aspect-square w-full max-w-[240px] rounded-[8px] bg-white/[0.06] sm:col-span-3"></div>
                            @endforelse
                        </div>
                    </article>
                @empty
                    <div class="rounded-[8px] border border-white/10 bg-white/[0.04] p-6 text-sm text-white/60">
                        Programs will appear here once they are created in the admin dashboard.
                    </div>
                @endforelse
            </div>
        </div>
    </section>
</main>
