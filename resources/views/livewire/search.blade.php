<?php

use App\Models\Article;
use App\Models\Category;
use App\Models\Product;
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
        return Article::with('categories')
            ->where('status', 'published')
            ->when($this->q, fn ($query) => $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->q . '%')
                    ->orWhere('exerpt', 'like', '%' . $this->q . '%')
                    ->orWhere('body', 'like', '%' . $this->q . '%');
            }))
            ->latest()
            ->take(8)
            ->get();
    }

    public function getCategoryResultsProperty()
    {
        return Category::when($this->q, fn ($query) => $query
                ->where('name', 'like', '%' . $this->q . '%'))
            ->take(8)
            ->get();
    }

    public function getResourceResultsProperty()
    {
        return Resource::when($this->q, fn ($query) => $query
                ->where('title', 'like', '%' . $this->q . '%')
                ->orWhere('url', 'like', '%' . $this->q . '%'))
            ->latest()
            ->take(8)
            ->get();
    }

    public function getProductResultsProperty()
    {
        return Product::when($this->q, fn ($query) => $query
                ->where('title', 'like', '%' . $this->q . '%')
                ->orWhere('body', 'like', '%' . $this->q . '%'))
            ->latest()
            ->take(8)
            ->get();
    }
}; ?>

<main class="min-h-screen bg-[#111429] text-white">
    <section class="mx-auto max-w-5xl px-6 py-16">
        <h1 class="text-4xl font-bold sm:text-5xl">Search QueerWorx</h1>
        <p class="mt-3 text-white/60">Find articles, categories, resources, and programmes across the site.</p>

        <form action="{{ route('search') }}" method="GET" class="mt-8 flex rounded-full border border-white/10 bg-black/25 p-1">
            <label class="sr-only" for="global-search-page">Search site</label>
            <input
                id="global-search-page"
                name="q"
                value="{{ $q }}"
                type="search"
                placeholder="Search the whole website..."
                class="min-w-0 flex-1 rounded-full bg-transparent px-5 py-3 text-white outline-none placeholder:text-white/45"
            >
            <button class="rounded-full bg-purple-600 px-6 text-sm font-bold text-white transition hover:bg-purple-700" type="submit">
                Search
            </button>
        </form>

        <div class="mt-10 grid gap-6">
            <section class="rounded-[8px] border border-white/10 bg-black/25 p-6">
                <h2 class="text-2xl font-bold">Articles</h2>
                <div class="mt-4 grid gap-3">
                    @forelse ($this->articleResults as $article)
                        <a wire:navigate href="{{ route('article', $article->slug) }}" class="rounded-[8px] border border-white/10 p-4 transition hover:border-purple-400">
                            <h3 class="font-bold">{{ $article->title }}</h3>
                            <p class="mt-1 text-sm text-white/60">{{ $article->exerpt }}</p>
                        </a>
                    @empty
                        <p class="text-white/50">No matching articles yet.</p>
                    @endforelse
                </div>
            </section>

            <section class="rounded-[8px] border border-white/10 bg-black/25 p-6">
                <h2 class="text-2xl font-bold">Categories</h2>
                <div class="mt-4 flex flex-wrap gap-3">
                    @forelse ($this->categoryResults as $category)
                        <a wire:navigate href="{{ route('articles', ['category' => $category->slug ?: $category->name]) }}" class="rounded-full bg-white/10 px-4 py-2 text-sm font-semibold transition hover:bg-purple-500">
                            {{ $category->name }}
                        </a>
                    @empty
                        <p class="text-white/50">No matching categories yet.</p>
                    @endforelse
                </div>
            </section>

            <section class="rounded-[8px] border border-white/10 bg-black/25 p-6">
                <h2 class="text-2xl font-bold">Resources</h2>
                <div class="mt-4 grid gap-3">
                    @forelse ($this->resourceResults as $resource)
                        <a href="{{ $resource->url }}" target="_blank" class="rounded-[8px] border border-white/10 p-4 transition hover:border-cyan-400">
                            {{ $resource->title }}
                        </a>
                    @empty
                        <p class="text-white/50">No matching resources yet.</p>
                    @endforelse
                </div>
            </section>

            <section class="rounded-[8px] border border-white/10 bg-black/25 p-6">
                <h2 class="text-2xl font-bold">Programmes</h2>
                <div class="mt-4 grid gap-3">
                    @forelse ($this->productResults as $product)
                        <div class="rounded-[8px] border border-white/10 p-4">
                            <h3 class="font-bold">{{ $product->title }}</h3>
                            <p class="mt-1 text-sm text-white/60">{{ Str::limit(strip_tags($product->body), 140) }}</p>
                        </div>
                    @empty
                        <p class="text-white/50">No matching programmes yet.</p>
                    @endforelse
                </div>
            </section>
        </div>
    </section>
</main>
