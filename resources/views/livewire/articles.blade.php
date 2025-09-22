<?php

use Livewire\Volt\Component;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Support\Facades\Route;
use Livewire\Attributes\Layout;

new 
#[Layout('components.layouts.app.frontend')]
class extends Component {
    public $articles;
    public $categories = [];
    public $selectedCategory = null;
    public $search = '';

    public function mount()
    {
        $this->loadArticles();

        $this->categories = Category::latest()->get();
    }

    public function loadArticles()
    {
        $query = Article::where('status', 'published')
                    ->orderBy('published_date', 'desc');

        if ($this->selectedCategory) {
            $query->where('categories', $this->selectedCategory);
        }

        if ($this->search) {
            $query->where(function($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
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

    public function clearFilters()
    {
        $this->selectedCategory = null;
        $this->search = '';
        $this->loadArticles();
    }
}; ?>


<div class="bg-gray-900 text-gray-200 min-h-screen">
    <!-- Hero Section -->
    <section class="relative py-16 md:py-24 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-gray-900 to-transparent z-0"></div>
        <div class="absolute right-0 top-0 bottom-0 w-full md:w-1/2 gradient-bg opacity-20 z-0"></div>
        
        <div class="container mx-auto px-4 max-w-7xl relative z-10 text-center">
            <h1 class="text-4xl md:text-5xl font-bold leading-tight">
                Explore Our <span class="text-purple-500">Articles</span>
            </h1>
            <p class="text-lg text-gray-400 mt-4 max-w-2xl mx-auto">
                Browse resources, insights, and stories from the LGBTQ+ community in Lesotho.
            </p>
        </div>
    </section>

    <!-- Filters -->
    <section class="py-8 bg-gray-800">
        <div class="container mx-auto px-4 max-w-7xl">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <!-- Category Filter -->
                <select wire:model="selectedCategory" class="bg-gray-900 border border-gray-700 text-gray-200 rounded-lg px-4 py-2 focus:outline-none">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>

                <!-- Search -->
                <input type="text" wire:model.debounce.500ms="search" placeholder="Search articles..." 
                       class="flex-grow bg-gray-900 border border-gray-700 text-gray-200 rounded-lg px-4 py-2 focus:outline-none">

                <button wire:click="clearFilters" class="bg-purple-600 hover:bg-purple-700 px-4 py-2 rounded-lg text-white transition">
                    Clear
                </button>
            </div>
        </div>
    </section>

    <!-- Articles Grid -->
    <section class="py-16 bg-gray-900">
        <div class="container mx-auto px-4 max-w-7xl">
            @if($articles->count())
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach($articles as $article)
                        <div class="bg-gray-800 rounded-2xl p-6 card-hover border border-gray-700 flex flex-col">
                            <img src="{{ $article->image ?? 'https://placehold.co/600x400/8B5CF6/FFFFFF/png?text=Article' }}" 
                                 alt="{{ $article->title }}" 
                                 class="rounded-lg mb-4 w-full h-48 object-cover">

                            <h3 class="text-xl font-semibold">{{ $article->title }}</h3>
                            <p class="text-gray-400 mt-2 line-clamp-3">
                                {{ Str::limit(strip_tags($article->body), 120) }}
                            </p>

                            <div class="mt-4 flex justify-between items-center text-sm text-gray-400">
                                {{-- <span>{{ $article->published_date?->format('M d, Y') }}</span> --}}
                                <a href="{{ route('article', $article->slug) }}" class="text-purple-400 hover:text-purple-300">
                                    Read More <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center text-gray-400">
                    <p>No articles found. Try adjusting your filters.</p>
                </div>
            @endif
        </div>
    </section>
</div>
