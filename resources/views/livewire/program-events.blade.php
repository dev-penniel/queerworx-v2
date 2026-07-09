<?php

use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Volt\Component;

new
#[Layout('components.layouts.app.frontend')]
class extends Component {
    public $program;

    #[Url(as: 'status')]
    public $status = 'all';

    public function mount($program): void
    {
        $this->program = \App\Models\Program::with(['activities' => fn ($query) => $query->with('media')->latest('activity_date')->latest()])
            ->findOrFail($program);
    }
}; ?>

@php
    $events = $program->activities;

    if ($status !== 'all') {
        $events = $events->where('status', $status);
    }
@endphp

<main class="min-h-screen bg-[#111429] text-white">
    <section class="border-b border-white/10">
        <div class="mx-auto max-w-7xl px-6 py-16">
            <div class="overflow-hidden rounded-[8px] border border-white/10 bg-white/[0.04]">
                @if ($program->cover_image_path)
                    <img src="{{ \Illuminate\Support\Facades\Storage::url($program->cover_image_path) }}" alt="{{ $program->name }}" class="max-h-[420px] w-full object-cover">
                @else
                    <div class="h-72 bg-[linear-gradient(135deg,#211146_0%,#7646E8_45%,#111429_100%)]"></div>
                @endif
            </div>

            <div class="mt-8 max-w-3xl">
                <p class="text-sm font-bold uppercase tracking-wide text-[#FFD83D]">Program Events</p>
                <h1 class="mt-3 text-4xl font-bold tracking-normal text-white sm:text-5xl">{{ $program->name }}</h1>
                @if ($program->summary)
                    <p class="mt-4 text-base leading-7 text-white/65">{{ $program->summary }}</p>
                @endif
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-6 py-12">
        <div class="flex flex-wrap gap-3">
            @foreach (['all' => 'All', 'upcoming' => 'Upcoming', 'ongoing' => 'Ongoing', 'completed' => 'Past'] as $value => $label)
                <a href="{{ route('programs.show', ['program' => $program->id, 'status' => $value]) }}" class="rounded-full border px-4 py-2 text-sm font-semibold transition {{ $status === $value ? 'border-[#FFD83D] bg-[#FFD83D] text-[#111429]' : 'border-white/15 text-white/70 hover:border-[#FFD83D] hover:text-white' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <div class="mt-8 grid gap-6 lg:grid-cols-3">
            @forelse ($events as $event)
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
                        <p class="text-xs font-semibold uppercase tracking-wide text-white/45">{{ ucfirst($event->status) }} · {{ optional($event->activity_date)->format('M d, Y') ?? 'Date to be announced' }}</p>
                        <h2 class="mt-3 text-xl font-bold text-white group-hover:text-[#FFD83D]">{{ $event->title }}</h2>
                        <p class="mt-3 text-sm leading-6 text-white/60">{{ \Illuminate\Support\Str::limit($event->description, 120) }}</p>
                    </div>
                </a>
            @empty
                <div class="rounded-[8px] border border-white/10 bg-white/[0.04] p-6 text-sm text-white/60 lg:col-span-3">
                    No events match this filter yet.
                </div>
            @endforelse
        </div>
    </section>
</main>
