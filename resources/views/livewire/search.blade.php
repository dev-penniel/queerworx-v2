<?php

use App\Models\Article;
use App\Models\Category;
use App\Models\Program;
use App\Models\ProgramActivity;
use App\Models\Resource;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Volt\Component;

new
#[Layout('components.layouts.app.frontend')]
class extends Component {
    #[Url(as: 'q')]
    public $q = '';

    public function getArticleResultsProperty()
    {
        return $this->searchTerm() === '' ? collect() : Article::with('categories')
            ->where('status', 'published')
            ->where(function ($query) {
                $query->where('title', 'like', '%'.$this->searchTerm().'%')
                    ->orWhere('exerpt', 'like', '%'.$this->searchTerm().'%')
                    ->orWhere('body', 'like', '%'.$this->searchTerm().'%');
            })
            ->orderByDesc('published_date')
            ->take(8)
            ->get();
    }

    public function getCategoryResultsProperty()
    {
        return $this->searchTerm() === '' ? collect() : Category::where('name', 'like', '%'.$this->searchTerm().'%')->take(8)->get();
    }

    public function getResourceResultsProperty()
    {
        return $this->searchTerm() === '' ? collect() : Resource::where(function ($query) {
            $query->where('title', 'like', '%'.$this->searchTerm().'%')
                ->orWhere('url', 'like', '%'.$this->searchTerm().'%');
        })->latest()->take(8)->get();
    }

    public function getProgramResultsProperty()
    {
        return $this->searchTerm() === '' ? collect() : Program::where('is_active', true)
            ->where(function ($query) {
                $query->where('name', 'like', '%'.$this->searchTerm().'%')
                    ->orWhere('summary', 'like', '%'.$this->searchTerm().'%')
                    ->orWhere('outcomes', 'like', '%'.$this->searchTerm().'%');
            })
            ->orderBy('sort_order')
            ->take(8)
            ->get();
    }

    public function getEventResultsProperty()
    {
        return $this->searchTerm() === '' ? collect() : ProgramActivity::with('program')
            ->where(function ($query) {
                $query->where('title', 'like', '%'.$this->searchTerm().'%')
                    ->orWhere('description', 'like', '%'.$this->searchTerm().'%')
                    ->orWhere('venue', 'like', '%'.$this->searchTerm().'%');
            })
            ->latest('activity_date')
            ->take(8)
            ->get();
    }

    private function searchTerm(): string
    {
        return trim((string) $this->q);
    }
}; ?>

@php
    $hasSearch = trim((string) $q) !== '';
    $resultCount = $this->articleResults->count()
        + $this->categoryResults->count()
        + $this->resourceResults->count()
        + $this->programResults->count()
        + $this->eventResults->count();
@endphp

<main class="min-h-screen bg-[#111429] text-white">
    <section class="mx-auto max-w-6xl px-6 py-16">
        @if (! $hasSearch)
            {{-- Results appear here after a search is submitted from the site header. --}}
        @elseif ($resultCount === 0)
            <div class="rounded-[14px] border border-white/10 bg-white/[0.04] p-8 text-center">
                <h2 class="text-2xl font-bold">No results found</h2>
                <p class="mt-3 text-white/60">We could not find anything for “{{ $q }}”. Try another word or phrase.</p>
            </div>
        @else
            <div class="grid gap-6">
                @if ($this->articleResults->isNotEmpty())
                    <section class="rounded-[14px] border border-white/10 bg-white/[0.04] p-6">
                        <h2 class="text-2xl font-bold">Stories</h2>
                        <div class="mt-4 grid gap-3">
                            @foreach ($this->articleResults as $article)
                                <a wire:navigate href="{{ route('article', $article->slug) }}" class="rounded-[10px] border border-white/10 p-4 transition hover:border-[#e61e5c]">
                                    <h3 class="font-bold">{{ $article->title }}</h3>
                                    <p class="mt-1 text-sm text-white/60">{{ Str::limit($article->exerpt ?: strip_tags($article->body), 180) }}</p>
                                </a>
                            @endforeach
                        </div>
                    </section>
                @endif

                @if ($this->programResults->isNotEmpty())
                    <section class="rounded-[14px] border border-white/10 bg-white/[0.04] p-6">
                        <h2 class="text-2xl font-bold">Programmes</h2>
                        <div class="mt-4 grid gap-3 sm:grid-cols-2">
                            @foreach ($this->programResults as $program)
                                <a wire:navigate href="{{ route('programs.show', $program->id) }}" class="rounded-[10px] border border-white/10 p-4 transition hover:border-[#e61e5c]">
                                    <h3 class="font-bold">{{ $program->name }}</h3>
                                    <p class="mt-1 text-sm text-white/60">{{ Str::limit($program->summary ?: $program->outcomes, 160) }}</p>
                                </a>
                            @endforeach
                        </div>
                    </section>
                @endif

                @if ($this->eventResults->isNotEmpty())
                    <section class="rounded-[14px] border border-white/10 bg-white/[0.04] p-6">
                        <h2 class="text-2xl font-bold">Events</h2>
                        <div class="mt-4 grid gap-3 sm:grid-cols-2">
                            @foreach ($this->eventResults as $event)
                                <a wire:navigate href="{{ route('events.show', $event->id) }}" class="rounded-[10px] border border-white/10 p-4 transition hover:border-[#e61e5c]">
                                    <p class="text-xs font-semibold text-pink-200">{{ $event->program?->name ?? 'Queer WorX event' }}</p>
                                    <h3 class="mt-1 font-bold">{{ $event->title }}</h3>
                                    <p class="mt-1 text-sm text-white/60">{{ Str::limit($event->description, 160) }}</p>
                                </a>
                            @endforeach
                        </div>
                    </section>
                @endif

                @if ($this->resourceResults->isNotEmpty())
                    <section class="rounded-[14px] border border-white/10 bg-white/[0.04] p-6">
                        <h2 class="text-2xl font-bold">Resources</h2>
                        <div class="mt-4 grid gap-3">
                            @foreach ($this->resourceResults as $resource)
                                <a href="{{ $resource->url }}" target="_blank" rel="noopener noreferrer" class="rounded-[10px] border border-white/10 p-4 transition hover:border-[#149cb9]">{{ $resource->title }}</a>
                            @endforeach
                        </div>
                    </section>
                @endif

                @if ($this->categoryResults->isNotEmpty())
                    <section class="rounded-[14px] border border-white/10 bg-white/[0.04] p-6">
                        <h2 class="text-2xl font-bold">Topics</h2>
                        <div class="mt-4 flex flex-wrap gap-3">
                            @foreach ($this->categoryResults as $category)
                                <a wire:navigate href="{{ route('articles', ['category' => $category->slug ?: $category->name]) }}" class="rounded-full border border-white/15 px-4 py-2 text-sm font-semibold transition hover:border-[#e61e5c]">{{ $category->name }}</a>
                            @endforeach
                        </div>
                    </section>
                @endif
            </div>
        @endif
    </section>
</main>
