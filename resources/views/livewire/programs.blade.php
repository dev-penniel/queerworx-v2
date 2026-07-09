<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new
#[Layout('components.layouts.app.frontend')]
class extends Component {
}; ?>

@php
    $programs = \App\Models\Program::with(['activities' => fn ($query) => $query->with('media')->latest('activity_date')->latest()])
        ->where('is_active', true)
        ->orderBy('sort_order')
        ->orderBy('name')
        ->get();
@endphp

<main class="min-h-screen bg-[#111429] text-white">
    <section class="relative overflow-hidden py-20 sm:py-24">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_50%_0%,rgba(118,70,232,0.26),transparent_38%),linear-gradient(180deg,#211146_0%,#111429_58%,#0b0d1d_100%)]"></div>

        <div class="relative mx-auto max-w-7xl px-6">
            <div class="max-w-3xl">
                <p class="text-sm font-bold uppercase tracking-wide text-[#FFD83D]">Programs</p>
                <h1 class="mt-2 text-4xl font-bold tracking-normal text-white sm:text-5xl">
                    Activities across our work
                </h1>
                <p class="mt-4 text-base leading-7 text-white/60">
                    Browse activity updates, visual stories, videos and reports from each Queer WorX program.
                </p>
            </div>

            <div class="mt-10 grid gap-6 lg:grid-cols-3">
                @forelse ($programs as $program)
                    <article id="program-{{ \Illuminate\Support\Str::slug($program->name) }}" class="rounded-[8px] border border-white/10 bg-white/[0.04] p-5 scroll-mt-28">
                        <div class="flex items-center gap-3">
                            <span class="h-3 w-3 rounded-full" style="background-color: {{ $program->color }}"></span>
                            <h2 class="text-xl font-bold text-white">{{ $program->name }}</h2>
                        </div>

                        @if ($program->summary)
                            <p class="mt-3 text-sm leading-6 text-white/55">{{ $program->summary }}</p>
                        @endif

                        <div class="mt-5 space-y-4">
                            @forelse ($program->activities as $activity)
                                <div class="rounded border border-white/10 bg-black/20 p-4 transition hover:border-[#FFD83D]/60">
                                    @php
                                        $activityImage = $activity->featured_image_path ?: $activity->image_path ?: $activity->media->firstWhere('type', 'image')?->file_path;
                                    @endphp
                                    @if ($activityImage)
                                        <img src="{{ \Illuminate\Support\Facades\Storage::url($activityImage) }}" alt="{{ $activity->title }}" class="mb-4 aspect-video w-full rounded object-cover">
                                    @endif

                                    <h3 class="font-bold text-white">{{ $activity->title }}</h3>
                                    <p class="mt-1 text-xs text-white/40">
                                        {{ ucfirst($activity->status) }} · {{ optional($activity->activity_date)->format('M d, Y') ?? 'Date to be announced' }}
                                    </p>

                                    @if ($activity->description)
                                        <p class="mt-2 text-sm leading-6 text-white/55">{{ $activity->description }}</p>
                                    @endif

                                    <div class="mt-4 flex flex-wrap gap-2">
                                        <a href="{{ route('events.show', $activity->id) }}" class="rounded bg-[#FFD83D] px-3 py-1 text-xs font-bold text-[#111429]">View event</a>
                                        @if ($activity->video_path)
                                            <a href="{{ \Illuminate\Support\Facades\Storage::url($activity->video_path) }}" target="_blank" class="rounded bg-[#7646E8] px-3 py-1 text-xs font-bold text-white">Watch video</a>
                                        @endif
                                        @if ($activity->pdf_path)
                                            <a href="{{ \Illuminate\Support\Facades\Storage::url($activity->pdf_path) }}" target="_blank" class="rounded bg-[#149CB9] px-3 py-1 text-xs font-bold text-white">Open PDF</a>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-white/45">Activities coming soon.</p>
                            @endforelse
                        </div>
                    </article>
                @empty
                    <div class="rounded-[8px] border border-white/10 bg-white/[0.04] p-6 text-white/60 lg:col-span-3">
                        No programs have been published yet.
                    </div>
                @endforelse
            </div>
        </div>
    </section>
</main>
