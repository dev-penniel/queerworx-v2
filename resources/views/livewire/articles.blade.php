<?php

use Livewire\Volt\Component;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;

new
#[Layout('components.layouts.app.frontend')]
class extends Component {
    public $articles;
    public $categories = [];

    #[Url(as: 'category')]
    public $selectedCategory = null;

    #[Url(as: 'search')]
    public $search = '';

    public function mount()
    {
        $this->categories = Category::latest()->get();
        $this->loadArticles();
    }

    public function loadArticles()
    {
        $query = Article::with('categories')
            ->where('status', 'published')
            ->orderBy('published_date', 'desc');

        if ($this->selectedCategory) {
            $category = $this->selectedCategory;

            $query->whereHas('categories', function ($q) use ($category) {
                $q->where('name', $category)
                    ->orWhere('slug', Str::slug($category));
            });
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                    ->orWhere('exerpt', 'like', '%' . $this->search . '%')
                    ->orWhere('body', 'like', '%' . $this->search . '%');
            });
        }

        $this->articles = $query->get();
    }

    public function updatedSelectedCategory()
    {
        $this->loadArticles();
    }

    public function updatedSearch()
    {
        $this->loadArticles();
    }

    public function selectCategory($category)
    {
        $this->selectedCategory = $category;
        $this->loadArticles();
    }

    public function clearFilters()
    {
        $this->selectedCategory = null;
        $this->search = '';
        $this->loadArticles();
    }
}; ?>

@php
    $featuredArticle = $articles->first();
    $latestArticles = Article::with('categories')
        ->where('status', 'published')
        ->orderBy('published_date', 'desc')
        ->latest()
        ->take(3)
        ->get();
    $popularArticles = $articles->sortByDesc('views')->take(5)->values();
    $communityArticle = $articles->first(fn ($article) => $article->categories->contains('name', 'Community Stories')) ?? $featuredArticle;

    $articleUrl = fn ($article) => $article ? route('article', $article->slug) : route('submit-story');
@endphp

<main class="bg-[#111429] text-white">
    <section class="mx-auto grid max-w-7xl gap-10 px-6 py-14 lg:grid-cols-[0.9fr_1.25fr] lg:items-center lg:py-20">
        <div>
            <h1 class="text-6xl font-bold tracking-normal text-purple-400 sm:text-7xl">
                Xpressions
            </h1>
            <p class="mt-4 text-xl font-semibold text-white">
                Stories. Perspectives. Knowledge. Community.
            </p>
            <div class="mt-6 flex h-4 w-32 items-center gap-1">
                @foreach (['bg-purple-500', 'bg-pink-400', 'bg-orange-300', 'bg-yellow-300', 'bg-emerald-400', 'bg-cyan-400'] as $color)
                    <span class="h-1.5 w-5 rounded-full {{ $color }}"></span>
                @endforeach
            </div>
            <p class="mt-8 max-w-md text-lg leading-8 text-white/60">
                A space for our voices to be heard, our stories to be shared, and our community to stay informed and inspired.
            </p>

            <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                <form action="{{ route('articles') }}" method="GET" class="flex w-full max-w-md rounded-full border border-white/10 bg-black/25 p-1 shadow-sm">
                    <label class="sr-only" for="xpressions-search">Search articles</label>
                    <input
                        id="xpressions-search"
                        name="search"
                        value="{{ $search }}"
                        type="search"
                        placeholder="Search stories..."
                        class="min-w-0 flex-1 rounded-full bg-transparent px-4 py-2 text-sm text-white outline-none placeholder:text-white/45"
                    >
                    <button class="rounded-full bg-purple-700 px-4 text-sm font-semibold text-white transition hover:bg-purple-800" type="submit">
                        Search
                    </button>
                </form>

                <a wire:navigate href="{{ route('submit-story') }}" class="inline-flex items-center justify-center rounded-full border border-purple-400/40 px-5 py-3 text-sm font-semibold text-purple-200 transition hover:border-purple-300 hover:text-white">
                    Upload Story
                </a>
            </div>
        </div>

        <article class="relative min-h-[390px] overflow-hidden rounded-[8px] bg-gray-900 shadow-xl">
            @if ($featuredArticle?->thumbnail)
                <img src="{{ Storage::url($featuredArticle->thumbnail) }}" alt="{{ $featuredArticle->title }}" class="absolute inset-0 h-full w-full object-cover">
            @else
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_35%_30%,rgba(255,255,255,0.35),transparent_18%),linear-gradient(135deg,#4F46E5_0%,#DB2777_35%,#F97316_58%,#16A34A_78%,#0891B2_100%)]"></div>
                <div class="absolute inset-0 flex items-center justify-center text-white/25">
                    <i class="fa-solid fa-image text-8xl"></i>
                </div>
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/25 to-transparent"></div>
            <div class="relative flex h-full min-h-[390px] flex-col justify-end p-8 text-white">
                <span class="w-fit rounded bg-purple-700 px-3 py-2 text-xs font-bold uppercase tracking-wide">
                    Featured Story
                </span>
                <h2 class="mt-28 max-w-lg text-4xl font-bold leading-tight">
                    {{ $featuredArticle->title ?? 'What Pride Means to Us in Lesotho' }}
                </h2>
                <p class="mt-3 max-w-lg text-lg text-white/85">
                    {{ $featuredArticle?->exerpt ?? 'Upload a featured Xpressions story with a thumbnail to replace this placeholder panel.' }}
                </p>
                <a href="{{ $articleUrl($featuredArticle) }}" class="mt-6 inline-flex w-fit items-center gap-2 text-lg font-semibold transition hover:text-purple-200">
                    {{ $featuredArticle ? 'Read More' : 'Upload Story' }} <i class="fa-solid fa-arrow-right text-sm"></i>
                </a>
            </div>
        </article>
    </section>

    <section class="border-t border-white/10">
        <div class="mx-auto max-w-7xl px-6 py-9">
            <div class="flex items-center justify-between gap-6">
                <h2 class="text-3xl font-bold">Latest Articles</h2>
                <a href="{{ route('articles') }}" class="text-sm font-bold text-purple-300 transition hover:text-pink-300">
                    View all articles <i class="fa-solid fa-arrow-right ml-1"></i>
                </a>
            </div>

            <div class="mt-5 grid gap-5 lg:grid-cols-3">
                @forelse ($latestArticles as $article)
                    <a wire:navigate href="{{ route('article', $article->slug) }}" class="block overflow-hidden rounded-[8px] border border-white/10 bg-black/25 shadow-sm transition hover:-translate-y-1 hover:border-purple-400/60 hover:shadow-lg">
                        @if ($article->thumbnail)
                            <img src="{{ Storage::url($article->thumbnail) }}" alt="{{ $article->title }}" class="h-44 w-full object-cover">
                        @else
                            <div class="flex h-44 items-center justify-center bg-gradient-to-br from-purple-100 via-pink-100 to-cyan-100 text-purple-300">
                                <i class="fa-solid fa-image text-5xl"></i>
                            </div>
                        @endif
                        <div class="p-5">
                            <span class="rounded bg-purple-100 px-3 py-1 text-[11px] font-bold uppercase text-purple-700">
                                {{ $article->categories->first()->name ?? 'Community Stories' }}
                            </span>
                            <h3 class="mt-4 text-xl font-bold leading-snug">{{ $article->title }}</h3>
                            <p class="mt-2 text-sm leading-6 text-white/60">{{ Str::limit($article->exerpt ?: strip_tags($article->body), 105) }}</p>
                            <div class="mt-5 flex items-center gap-3 text-xs font-medium text-white/45">
                                <span>{{ $article->img_credit ?: 'Queer Worx Team' }}</span>
                                <span>&bull;</span>
                                <span>{{ $article->published_date?->format('M d, Y') }}</span>
                                <span>&bull;</span>
                                <span>5 min read</span>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="rounded-[8px] border border-white/10 bg-black/25 p-6 text-sm text-white/60 lg:col-span-3">
                        No published articles yet. Latest articles will appear here after they are approved and published in the admin dashboard.
                    </div>@endforelse
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-6 py-6">
        <article class="grid gap-7 rounded-[8px] border border-white/10 bg-black/25 p-6 lg:grid-cols-[0.8fr_1.2fr] lg:items-center">
            <div class="min-h-56 overflow-hidden rounded-[8px]">
                @if ($communityArticle?->thumbnail)
                    <img src="{{ Storage::url($communityArticle->thumbnail) }}" alt="{{ $communityArticle->title }}" class="h-full min-h-56 w-full object-cover">
                @else
                    <div class="flex min-h-56 items-center justify-center bg-gradient-to-br from-cyan-100 via-white to-pink-100 text-purple-300">
                        <i class="fa-solid fa-flag text-7xl"></i>
                    </div>
                @endif
            </div>
            <div class="relative">
                <span class="text-xs font-bold uppercase text-purple-700">Featured Community Story</span>
                <h2 class="mt-5 text-3xl font-bold">{{ $communityArticle->title ?? 'Finding Home in My Community' }}</h2>
                <p class="mt-3 max-w-xl text-white/60">
                    {{ $communityArticle?->exerpt ?? 'From feeling invisible to finding my tribe.' }}
                </p>
                <a href="{{ $articleUrl($communityArticle) }}" class="mt-6 inline-flex items-center gap-2 font-bold text-purple-300 transition hover:text-pink-300">
                    {{ $communityArticle ? 'Read Full Story' : 'Upload Community Story' }} <i class="fa-solid fa-arrow-right text-sm"></i>
                </a>
                <i class="fa-solid fa-quote-right absolute right-4 top-8 hidden text-8xl text-purple-400/60 md:block"></i>
            </div>
        </article>
    </section>
    
    <section class="mx-auto max-w-7xl px-6 pb-16 pt-6">
        <div class="flex items-center justify-between">
            <h2 class="text-3xl font-bold">Popular Reads</h2>
            <a href="{{ route('articles') }}" class="text-sm font-bold text-purple-300 transition hover:text-pink-300">
                View all <i class="fa-solid fa-arrow-right ml-1"></i>
            </a>
        </div>

        <div class="mt-6 grid gap-5 lg:grid-cols-5">
            @forelse ($popularArticles as $index => $article)
                <a wire:navigate href="{{ route('article', $article->slug) }}" class="group grid grid-cols-[auto_1fr] gap-3">
                    <span class="flex h-8 w-8 items-center justify-center rounded-full bg-purple-700 text-sm font-bold text-white">{{ $index + 1 }}</span>
                    <div class="flex gap-3">
                        @if ($article->thumbnail)
                            <img src="{{ Storage::url($article->thumbnail) }}" alt="{{ $article->title }}" class="h-20 w-20 rounded-[8px] object-cover">
                        @else
                            <div class="flex h-20 w-20 shrink-0 items-center justify-center rounded-[8px] bg-purple-100 text-purple-400">
                                <i class="fa-solid fa-image"></i>
                            </div>
                        @endif
                        <div>
                            <h3 class="text-sm font-bold leading-snug group-hover:text-purple-300">{{ Str::limit($article->title, 58) }}</h3>
                            <p class="mt-2 text-xs text-white/45">{{ $article->published_date?->format('M d, Y') }}</p>
                        </div>
                    </div>
                </a>
            @empty
                @foreach (['LGBTIQ+ Rights in Lesotho: What You Should Know', 'Inclusive Language Guide', 'How to Be an Ally Every Day', 'Pride in Business: Building Inclusive Enterprices', 'Financial Literacy and Growth'] as $index => $title)
                    <div class="grid grid-cols-[auto_1fr] gap-3">
                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-purple-700 text-sm font-bold text-white">{{ $index + 1 }}</span>
                        <div class="flex gap-3">
                            <div class="flex h-20 w-20 shrink-0 items-center justify-center rounded-[8px] bg-gradient-to-br from-purple-100 to-pink-100 text-purple-400">
                                <i class="fa-solid fa-image"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold leading-snug text-white">{{ $title }}</h3>
                                <p class="mt-2 text-xs text-white/45">Upload article</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endforelse
        </div>
    </section>*/

    <livewire:components.adverts position="3" />
</main>
