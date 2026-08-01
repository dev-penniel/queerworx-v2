<?php

use App\Models\Advert;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Volt\Component;

new
#[Layout('components.layouts.app.frontend')]
class extends Component {
    public $articles;
    public $categories = [];

    #[Url(as: 'category')]
    public $selectedCategory = null;

    #[Url(as: 'search')]
    public $search = '';

    public function mount(): void
    {
        $this->categories = Category::orderBy('name')->get();
        $this->loadArticles();
    }

    public function loadArticles(): void
    {
        $query = Article::with('categories')->where('status', 'published')->orderByDesc('published_date')->latest();

        if ($this->selectedCategory) {
            $selectedCategory = $this->selectedCategory;
            $query->whereHas('categories', function ($categoryQuery) use ($selectedCategory) {
                $categoryQuery->where('name', $selectedCategory)->orWhere('slug', Str::slug($selectedCategory));
            });
        }

        if ($this->search) {
            $query->where(function ($articleQuery) {
                $articleQuery->where('title', 'like', '%'.$this->search.'%')
                    ->orWhere('exerpt', 'like', '%'.$this->search.'%')
                    ->orWhere('body', 'like', '%'.$this->search.'%');
            });
        }

        $this->articles = $query->get();
    }

    public function updatedSelectedCategory(): void
    {
        $this->loadArticles();
    }

    public function updatedSearch(): void
    {
        $this->loadArticles();
    }
}; ?>

@php
    $featuredStories = $articles->take(5)->values();
    $latestStories = $articles->slice($featuredStories->count() > 1 ? 1 : 0, 4)->values();
    $sidebarAdverts = Advert::orderBy('position')->latest()->take(3)->get();
@endphp

<main class="min-h-screen bg-[#111429] text-white">
    <section class="mx-auto max-w-7xl px-6 py-12 sm:py-16">
        <div class="mb-8 flex flex-col gap-5 border-b border-white/10 pb-6 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm font-extrabold uppercase tracking-[0.28em] text-pink-300">Stories · Voices · Our Truth</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <form action="{{ route('articles') }}" method="GET" class="flex rounded-full border border-white/15 bg-black/20 p-1">
                    <label class="sr-only" for="xpressions-search">Search stories</label>
                    <input id="xpressions-search" name="search" value="{{ $search }}" type="search" placeholder="Search stories" class="min-w-0 bg-transparent px-4 py-2 text-sm text-white outline-none placeholder:text-white/40">
                    <button class="rounded-full bg-[#e61e5c] px-4 text-sm font-bold text-white transition hover:bg-[#c9184f]" type="submit">Search</button>
                </form>
                <a wire:navigate href="{{ route('submit-story') }}" class="inline-flex items-center rounded-full bg-[#e61e5c] px-5 py-3 text-sm font-bold text-white transition hover:bg-[#c9184f]">Submit a story</a>
            </div>
        </div>

        <div class="grid gap-8 lg:grid-cols-[minmax(0,1fr)_280px]">
            <div>
                @if ($featuredStories->isNotEmpty())
                    <div
                        x-data="{
                            activeSlide: 0,
                            total: {{ $featuredStories->count() }},
                            autoplay: null,
                            next() { this.activeSlide = this.activeSlide === this.total - 1 ? 0 : this.activeSlide + 1 },
                            previous() { this.activeSlide = this.activeSlide === 0 ? this.total - 1 : this.activeSlide - 1 },
                            startAutoplay() { if (this.total > 1 && !this.autoplay) this.autoplay = setInterval(() => this.next(), 6000) },
                            stopAutoplay() { clearInterval(this.autoplay); this.autoplay = null },
                        }"
                        x-init="startAutoplay(); return () => stopAutoplay()"
                        x-on:mouseenter="stopAutoplay()"
                        x-on:mouseleave="startAutoplay()"
                        class="relative overflow-hidden rounded-[18px] border border-white/10 bg-[#1a1a31] shadow-2xl shadow-black/20"
                    >
                        <div class="relative min-h-[420px] sm:min-h-[500px]">
                            @foreach ($featuredStories as $index => $article)
                                <article x-show="activeSlide === {{ $index }}" x-transition.opacity.duration.400ms class="absolute inset-0" @if ($index !== 0) style="display: none;" @endif>
                                    @if ($article->thumbnail)
                                        <div class="absolute inset-0 flex items-center justify-center bg-black/35">
                                            <img src="{{ \Illuminate\Support\Facades\Storage::url($article->thumbnail) }}" alt="{{ $article->title }}" class="h-full w-full object-contain">
                                        </div>
                                    @else
                                        <div class="absolute inset-0 bg-[linear-gradient(135deg,#e61e5c_0%,#7646e8_100%)]"></div>
                                    @endif
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/35 to-transparent"></div>
                                    <div class="relative flex h-full min-h-[420px] flex-col justify-end p-6 sm:min-h-[500px] sm:p-9">
                                        <span class="w-fit rounded bg-[#e61e5c] px-3 py-1.5 text-[11px] font-extrabold uppercase tracking-wide">Featured story</span>
                                        <h2 class="mt-4 max-w-2xl text-3xl font-bold leading-tight sm:text-4xl">{{ $article->title }}</h2>
                                        <p class="mt-3 max-w-xl text-sm leading-6 text-white/85 sm:text-base">{{ Str::limit($article->exerpt ?: strip_tags($article->body), 190) }}</p>
                                        <a wire:navigate href="{{ route('article', $article->slug) }}" class="mt-6 inline-flex w-fit rounded-full bg-[#e61e5c] px-5 py-2.5 text-sm font-bold transition hover:bg-[#c9184f]">Read full story <span class="ml-2">→</span></a>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                        @if ($featuredStories->count() > 1)
                            <button type="button" x-on:click="previous()" class="absolute left-4 top-1/2 flex h-10 w-10 -translate-y-1/2 items-center justify-center rounded-full bg-black/50 text-white transition hover:bg-[#e61e5c]" aria-label="Previous slide">‹</button>
                            <button type="button" x-on:click="next()" class="absolute right-4 top-1/2 flex h-10 w-10 -translate-y-1/2 items-center justify-center rounded-full bg-black/50 text-white transition hover:bg-[#e61e5c]" aria-label="Next slide">›</button>
                            <div class="absolute bottom-5 left-1/2 flex -translate-x-1/2 gap-2">
                                @foreach ($featuredStories as $index => $article)
                                    <button type="button" x-on:click="activeSlide = {{ $index }}" class="h-2.5 w-2.5 rounded-full transition" x-bind:class="activeSlide === {{ $index }} ? 'bg-[#e61e5c]' : 'bg-white/60'" aria-label="Show {{ $article->title }}"></button>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @else
                    <div class="flex min-h-[420px] items-center justify-center rounded-[18px] border border-white/10 bg-[#1a1a31] p-8 text-center text-white/60">
                        Publish stories from the admin dashboard to populate the Xpressions slider.
                    </div>
                @endif

                <section class="mt-10">
                    <div class="flex items-center justify-between gap-4">
                        <h2 class="text-2xl font-bold">Latest Stories</h2>
                        <a wire:navigate href="{{ route('articles') }}" class="text-sm font-bold text-pink-300 hover:text-pink-200">View all stories →</a>
                    </div>
                    <div class="mt-5 grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
                        @forelse ($latestStories as $article)
                            <a wire:navigate href="{{ route('article', $article->slug) }}" class="group overflow-hidden rounded-[14px] border border-white/10 bg-[#1a1a31] transition hover:-translate-y-1 hover:border-[#e61e5c]/60">
                                <div class="flex min-h-[170px] items-center justify-center bg-black/25 p-2">
                                    @if ($article->thumbnail)
                                        <img src="{{ \Illuminate\Support\Facades\Storage::url($article->thumbnail) }}" alt="{{ $article->title }}" class="max-h-[230px] w-full object-contain">
                                    @else
                                        <i class="fa-solid fa-image text-4xl text-white/25"></i>
                                    @endif
                                </div>
                                <div class="p-4">
                                    <span class="rounded bg-[#e61e5c]/20 px-2 py-1 text-[10px] font-extrabold uppercase tracking-wide text-pink-200">{{ $article->categories->first()->name ?? 'Xpressions' }}</span>
                                    <h3 class="mt-3 text-base font-bold leading-snug text-white group-hover:text-pink-200">{{ Str::limit($article->title, 64) }}</h3>
                                    <p class="mt-3 text-xs text-white/45">{{ $article->published_date?->format('M d, Y') ?? 'Recently published' }}</p>
                                </div>
                            </a>
                        @empty
                            <p class="text-sm text-white/55 sm:col-span-2 xl:col-span-4">No further stories match this search yet.</p>
                        @endforelse
                    </div>
                </section>
            </div>

            <aside class="space-y-6">
                <div class="text-xs font-extrabold uppercase tracking-wide text-pink-300">Advertisement</div>
                @if ($sidebarAdverts->isNotEmpty())
                    <div
                        x-data="{
                            activeAdvert: 0,
                            total: {{ $sidebarAdverts->count() }},
                            autoplay: null,
                            next() { this.activeAdvert = this.activeAdvert === this.total - 1 ? 0 : this.activeAdvert + 1 },
                            startAutoplay() { if (this.total > 1 && !this.autoplay) this.autoplay = setInterval(() => this.next(), 6000) },
                            stopAutoplay() { clearInterval(this.autoplay); this.autoplay = null },
                        }"
                        x-init="startAutoplay(); return () => stopAutoplay()"
                        x-on:mouseenter="stopAutoplay()"
                        x-on:mouseleave="startAutoplay()"
                        class="relative overflow-hidden rounded-[14px]"
                    >
                        <div class="min-h-[260px]">
                            @foreach ($sidebarAdverts as $index => $advert)
                                <a x-show="activeAdvert === {{ $index }}" x-transition.opacity.duration.400ms href="{{ $advert->url }}" target="_blank" rel="noopener noreferrer" class="group block overflow-hidden rounded-[14px] border border-white/10 bg-[#1a1a31] transition hover:border-[#e61e5c]/60" aria-label="Open {{ $advert->title }}" @if ($index !== 0) style="display: none;" @endif>
                                    <div class="flex min-h-[180px] items-center justify-center bg-white p-3">
                                        @if ($advert->video_path)
                                            <video src="{{ route('media.show', ['path' => $advert->video_path]) }}" class="max-h-[210px] w-full object-contain" autoplay muted loop playsinline></video>
                                        @elseif ($advert->thumbnail)
                                            <img src="{{ Storage::url($advert->thumbnail) }}" class="h-15 w-30 object-cover rounded-md">
                                        @else
                                            <span class="text-center font-bold text-[#7646e8]">{{ $advert->title }}</span>
                                        @endif
                                    </div>
                                    <p class="p-4 text-sm font-bold text-white group-hover:text-pink-200">{{ $advert->title }} <span class="ml-1">→</span></p>
                                </a>
                            @endforeach
                        </div>

                        @if ($sidebarAdverts->count() > 1)
                            <div class="absolute bottom-3 left-1/2 z-10 flex -translate-x-1/2 gap-2">
                                @foreach ($sidebarAdverts as $index => $advert)
                                    <button type="button" x-on:click="activeAdvert = {{ $index }}" class="h-2 w-2 rounded-full transition" x-bind:class="activeAdvert === {{ $index }} ? 'bg-[#e61e5c]' : 'bg-white/60'" aria-label="Show {{ $advert->title }}"></button>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @else
                    <div class="rounded-[14px] border border-white/10 bg-[#1a1a31] p-5 text-sm leading-6 text-white/55">Adverts added in the admin dashboard will appear here.</div>
                @endif

                <div class="rounded-[14px] border border-[#e61e5c]/35 bg-[#e61e5c]/10 p-6">
                    <h2 class="text-xl font-bold text-white">Share Your Story</h2>
                    <p class="mt-3 text-sm leading-6 text-white/70">Xpressions is a platform for our voices, articles and perspectives.</p>
                    <a wire:navigate href="{{ route('submit-story') }}" class="mt-5 inline-flex rounded-full bg-[#e61e5c] px-4 py-2 text-sm font-bold text-white transition hover:bg-[#c9184f]">Submit now →</a>
                </div>
            </aside>
        </div>
    </section>
</main>
