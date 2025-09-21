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

<div class="min-h-screen bg-gray-50" x-data="{ mobileMenuOpen: false }">
    

    <!-- Main content -->
    <main class="container mx-auto px-4 py-12">
        <!-- Search and filter section -->
        <div class="mb-12 bg-white rounded-xl shadow-lg p-6">
            <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
                <div class="w-full md:w-1/3">
                    <div class="relative">
                        <input 
                            type="text" 
                            wire:model.live="search"
                            placeholder="Search articles..." 
                            class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition"
                        >
                        <svg class="w-5 h-5 absolute left-3 top-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
                
                <div class="w-full md:w-1/3">
                    <select 
                        wire:model.live="selectedCategory"
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition"
                    >
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->name }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <button 
                    wire:click="clearFilters"
                    class="w-full md:w-auto px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition"
                >
                    Clear Filters
                </button>
            </div>
        </div>

        <!-- Articles grid -->
        @if($articles->count())
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-8">
                @foreach($articles as $article)
                    
                        <article class="bg-white rounded-xl shadow-lg overflow-hidden transition-transform duration-300 hover:scale-[1.02]">
                            <a href="{{ route('article', ['slug' => $article->slug])  }}" wire:navigate>
                        <!-- Article thumbnail with blur effect -->
                        <div class="relative h-48 overflow-hidden">
                            @if($article->thumbnail)
                                <img 
                                    src="{{ Storage::url($article->thumbnail) }}" 
                                    alt="{{ $article->title }}"
                                    class="w-full h-full object-cover"
                                >
                            @else
                                <div class="w-full h-full bg-gradient-to-r from-blue-400 to-purple-500 flex items-center justify-center">
                                    <span class="text-white text-xl font-bold">{{ substr($article->title, 0, 2) }}</span>
                                </div>
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                            <div class="absolute bottom-4 left-4 flexwrap space-x-1">
                                @foreach ($article->categories as $category)
                                    <flux:badge class="rounded-3xl" variant="solid" size="sm" color="blue" >{{ $category->name ?? 'Uncategorized' }}</flux:badge>
                                @endforeach
                            </div>
                            <!-- Blurred background effect -->
                            <div class="absolute inset-0 backdrop-filter backdrop-blur-sm opacity-0 hover:opacity-100 transition-opacity duration-300"></div>
                        </div>
                        
                        <!-- Article content -->
                        <div class="p-6">
                            <h2 class="text-xl font-bold text-gray-800 mb-2 line-clamp-2">
                                {{-- <a 
                                    href="{{ route('articles.show', $article) }}" 
                                    class="hover:text-blue-600 transition"
                                > --}}
                                    {{ $article->title }}
                                </a>
                            </h2>
                            
                            <p class="text-gray-600 mb-4 line-clamp-3">
                                {{ $article->exerpt ?? \Illuminate\Support\Str::limit(strip_tags($article->body), 150) }}
                            </p>
                            
                            <div class="flex items-center justify-between text-sm text-gray-500">
                                {{-- <span>{{ $article->published_date->format('M d, Y') }}</span> --}}
                                <div class="flex items-center space-x-4">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        {{ $article->views }}
                                    </span>
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905a3.61 3.61 0 01-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>
                                        </svg>
                                        {{ $article->claps }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>

                    </article>
                @endforeach
            </div>
        @else
            <!-- Empty state -->
            <div class="text-center py-16">
                <div class="inline-block p-4 bg-blue-50 rounded-full mb-4">
                    <svg class="w-12 h-12 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-medium text-gray-700 mb-2">No articles found</h3>
                <p class="text-gray-500">Try adjusting your search or filter criteria</p>
            </div>
        @endif
    </main>
</div>