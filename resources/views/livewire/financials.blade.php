<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new
#[Layout('components.layouts.app.frontend')]
class extends Component {
}; ?>

@php
    $financialDocuments = \App\Models\AdminDocument::where('type', 'financial')
        ->latest('published_at')
        ->latest()
        ->get();
@endphp

<main class="min-h-screen bg-[#111429] text-white">
    <section class="relative overflow-hidden py-20 sm:py-24">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_0%,rgba(20,156,185,0.2),transparent_34%),linear-gradient(180deg,#211146_0%,#111429_100%)]"></div>

        <div class="relative mx-auto max-w-7xl px-6">
            <div class="max-w-3xl">
                <p class="text-sm font-bold uppercase tracking-wide text-[#149CB9]">Accountability</p>
                <h1 class="mt-2 text-4xl font-bold tracking-normal text-white sm:text-5xl">
                    Financials
                </h1>
            </div>

            <div class="mt-10 rounded-[8px] border border-white/10 bg-white/[0.05] p-6 shadow-2xl shadow-black/20">
                <div class="space-y-4">
                    @forelse ($financialDocuments as $document)
                        <a href="{{ \Illuminate\Support\Facades\Storage::url($document->file_path) }}" target="_blank" class="block rounded border border-white/10 bg-black/20 p-4 transition hover:border-[#149CB9] hover:bg-[#149CB9]/10">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <h2 class="font-bold text-white">{{ $document->title }}</h2>
                                    @if ($document->description)
                                        <p class="mt-1 text-sm leading-6 text-white/60">{{ $document->description }}</p>
                                    @endif
                                </div>
                                <span class="shrink-0 rounded bg-[#149CB9] px-3 py-1 text-xs font-bold text-white">PDF</span>
                            </div>
                        </a>
                    @empty
                        <p class="text-sm text-white/55">No financial documents uploaded yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </section>
</main>
