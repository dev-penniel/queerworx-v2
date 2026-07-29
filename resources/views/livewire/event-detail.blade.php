<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new
#[Layout('components.layouts.app.frontend')]
class extends Component {
    public $event;

    public function mount($activity): void
    {
        $this->event = \App\Models\ProgramActivity::with(['program', 'media'])->findOrFail($activity);
    }
}; ?>

@php
    $featuredImage = $event->featured_image_path ?: $event->image_path ?: $event->media->firstWhere('type', 'image')?->file_path;
    $images = $event->media->where('type', 'image')->values();
    $videos = $event->media->where('type', 'video')->values();
    $relatedEvents = \App\Models\ProgramActivity::with('media')
        ->where('program_id', $event->program_id)
        ->where('id', '!=', $event->id)
        ->latest('activity_date')
        ->take(3)
        ->get();
@endphp

<main class="min-h-screen bg-[#111429] text-white" x-data="{ lightbox: null }">
    <section class="border-b border-white/10">
        <div class="mx-auto max-w-7xl px-6 py-16">
            @if ($featuredImage)
                <img src="{{ \Illuminate\Support\Facades\Storage::url($featuredImage) }}" alt="{{ $event->title }}" class="max-h-[540px] w-full rounded-[8px] object-cover">
            @else
                <div class="h-80 rounded-[8px] bg-white/[0.06]"></div>
            @endif

            <div class="mt-8 grid gap-8 lg:grid-cols-[1fr_320px]">
                <div>
                    <p class="text-sm font-bold uppercase tracking-wide text-[#FFD83D]">{{ $event->program->name }}</p>
                    <h1 class="mt-3 text-4xl font-bold tracking-normal text-white sm:text-5xl">{{ $event->title }}</h1>
                    @if ($event->description)
                        <p class="mt-5 whitespace-pre-line text-base leading-8 text-white/70">{{ $event->description }}</p>
                    @endif
                </div>
                <aside class="rounded-[8px] border border-white/10 bg-white/[0.04] p-5">
                    <p class="text-sm text-white/55">Date</p>
                    <p class="mt-1 font-semibold">{{ optional($event->activity_date)->format('M d, Y') ?? 'To be announced' }}</p>
                    <p class="mt-5 text-sm text-white/55">Time</p>
                    <p class="mt-1 font-semibold">{{ $event->activity_time ? $event->activity_time->format('H:i') : 'To be announced' }}</p>
                    <p class="mt-5 text-sm text-white/55">Venue</p>
                    <p class="mt-1 font-semibold">{{ $event->venue ?: 'To be announced' }}</p>
                    <p class="mt-5 text-sm text-white/55">Status</p>
                    <p class="mt-1 font-semibold capitalize">{{ $event->status }}</p>
                </aside>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-6 py-12">
        <h2 class="text-3xl font-bold">Image Gallery</h2>
        <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($images as $media)
                <button type="button" x-on:click="lightbox = '{{ \Illuminate\Support\Facades\Storage::url($media->file_path) }}'" class="overflow-hidden rounded-[8px] border border-white/10">
                    <img src="{{ \Illuminate\Support\Facades\Storage::url($media->file_path) }}" alt="{{ $event->title }}" class="aspect-[4/3] w-full object-cover">
                </button>
            @empty
                <p class="text-sm text-white/55">No image gallery has been uploaded yet.</p>
            @endforelse
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-6 py-8">
        <h2 class="text-3xl font-bold">Video Gallery</h2>
        <div class="mt-6 grid gap-4 lg:grid-cols-2">
            @forelse ($videos as $media)
                <video controls class="w-full rounded-[8px] border border-white/10">
                    <source src="{{ \Illuminate\Support\Facades\Storage::url($media->file_path) }}">
                </video>
            @empty
                <p class="text-sm text-white/55">No videos have been uploaded yet.</p>
            @endforelse
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-6 py-12">
        <h2 class="text-3xl font-bold">Related Events</h2>
        <div class="mt-6 grid gap-6 lg:grid-cols-3">
            @forelse ($relatedEvents as $related)
                @php $relatedImage = $related->featured_image_path ?: $related->image_path ?: $related->media->firstWhere('type', 'image')?->file_path; @endphp
                <a href="{{ route('events.show', $related->id) }}" class="overflow-hidden rounded-[8px] border border-white/10 bg-white/[0.04] transition hover:border-[#FFD83D]/60">
                    @if ($relatedImage)
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($relatedImage) }}" alt="{{ $related->title }}" class="aspect-[16/9] w-full object-cover">
                    @endif
                    <div class="p-5">
                        <h3 class="font-bold">{{ $related->title }}</h3>
                        <p class="mt-2 text-sm text-white/55">{{ optional($related->activity_date)->format('M d, Y') }}</p>
                    </div>
                </a>
            @empty
                <p class="text-sm text-white/55">Related events will appear here.</p>
            @endforelse
        </div>
    </section>

    <div x-cloak x-show="lightbox" x-on:click="lightbox = null" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/90 p-6">
        <img x-bind:src="lightbox" alt="" class="max-h-full max-w-full rounded-[8px] object-contain">
    </div>
</main>
