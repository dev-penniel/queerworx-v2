<?php

use Livewire\Volt\Component;
use App\Models\Article;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;

new 
#[Layout('components.layouts.app.frontend')]
class extends Component {
    public Article $article;
    public $previousArticle;
    public $nextArticle;
    public $relatedArticles;

    public function mount($slug)
    {
        
        $this->article = Article::with('categories')->where('slug', $slug)->firstOrFail();

        $this->article->increment('views');

        $this->previousArticle = Article::where('id', '<', $this->article->id)
            ->where('status', '=', 'published')
            ->orderBy('id', 'desc')
            ->first();

        $this->nextArticle = Article::where('id', '>', $this->article->id)
            ->where('status', '=', 'published')
            ->orderBy('id', 'asc')
            ->first();

        $categoryIds = $this->article->categories->pluck('id')->toArray();

        $this->relatedArticles = Article::whereHas('categories', function ($q) use ($categoryIds) {
                $q->whereIn('categories.id', $categoryIds);
            })
            ->where('status', '=', 'published')
            ->where('id', '!=', $this->article->id)
            ->latest()
            ->take(3)
            ->get();
    }
}; ?>

<div>
    <div class="container mx-auto px-6 py-12 grid grid-cols-1 lg:grid-cols-3 gap-12">
    
    {{-- Article Content --}}
    <div class="lg:col-span-2">
        {{-- Thumbnail --}}
        @if($article->thumbnail)
            <img alt="{{ $article->img_credit }}" src="{{ Storage::url($article->thumbnail) }}" 
                 class="w-full h-96 object-cover rounded-2xl shadow mb-2">

                 <p class="text-gray-500 mb-4 text-sm ml-5">Image by {{ $article->img_credit }}</p>
        @endif

        {{-- Title --}}
        <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ $article->title }}</h1>

        {{-- Meta --}}
        <div class="flex gap-5 mb-8">
            <p class="text-sm text-gray-500">
                Published {{ $article->created_at->format('M d, Y') }}
                • {{ $article->categories->pluck('name')->join(', ') }}
            </p>

            <div class="flex items-center justify-between text-sm text-gray-500">
                {{-- <span>{{ $article->published_date->format('M d, Y') }}</span> --}}
                <div class="flex items-center space-x-4">
                    <span class="flex items-center">
                        <svg class="w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        {{ $article->views }}
                    </span>
                    <livewire:like id="{{ $article->id }}" />
                </div>
            </div>
        </div>

        {{-- Body --}}
        <div class="prose prose-lg max-w-none mb-10">
            {!! $article->body !!}
        </div>

        {{-- Previous & Next Navigation --}}
        <div class="mt-10 border-t pt-6 grid grid-cols-2 gap-6">

            {{-- Previous --}}
            @if($previousArticle)
                <a wire:navigate href="{{ route('article', ['slug' => $previousArticle->slug]) }}" 
                   class="group flex items-center gap-4 p-4 rounded-xl bg-white shadow hover:shadow-lg transition">
                    @if($previousArticle->thumbnail)
                        <img src="{{ Storage::url($previousArticle->thumbnail) }}" 
                             class="w-20 h-20 object-cover rounded-lg shadow">
                    @endif
                    <div>
                        <p class="text-xs text-gray-500">Previous</p>
                        <h3 class="text-sm font-semibold group-hover:text-blue-600">
                            {{ Str::limit($previousArticle->title, 50) }}
                        </h3>
                    </div>
                </a>
            @endif

            {{-- Next --}}
            @if($nextArticle)
                <a wire:navigate href="{{ route('article', ['slug' => $nextArticle->slug]) }}" 
                   class="group flex items-center gap-4 p-4 rounded-xl bg-white shadow hover:shadow-lg transition text-right justify-end">
                    <div>
                        <p class="text-xs text-gray-500">Next</p>
                        <h3 class="text-sm font-semibold group-hover:text-blue-600">
                            {{ Str::limit($nextArticle->title, 50) }}
                        </h3>
                    </div>
                    @if($nextArticle->thumbnail)
                        <img src="{{ Storage::url($nextArticle->thumbnail) }}" 
                             class="w-20 h-20 object-cover rounded-lg shadow">
                    @endif
                </a>
            @endif

        </div>

        {{-- Related Articles --}}
        @if($relatedArticles->count())
            <div class="mt-16">
                <h2 class="text-2xl font-semibold mb-6">Related Articles</h2>
                <div class="grid md:grid-cols-3 gap-6">
                    @foreach($relatedArticles as $related)
                        <a wire:navigate href="{{ route('article', ['slug' => $related->slug]) }}" 
                           class="block bg-white rounded-2xl shadow hover:shadow-lg transition overflow-hidden">
                            @if($related->thumbnail)
                                <img src="{{ Storage::url($related->thumbnail) }}" 
                                     class="w-full h-40 object-cover">
                            @endif
                            <div class="p-4">
                                <h3 class="font-semibold text-gray-900 mb-2">
                                    {{ Str::limit($related->title, 60) }}
                                </h3>
                                <p class="text-sm text-gray-500">
                                    {{ $related->created_at->format('M d, Y') }}
                                </p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    {{-- Sidebar --}}
    <div class="space-y-8">
        {{-- About --}}
        <div class="sticky top-5">
            <div class="bg-white rounded-2xl shadow p-6 ">
                <h2 class="text-xl font-semibold mb-4">About</h2>
                <p class="text-gray-600">
                    Welcome to our blog where we share stories, tutorials, and insights.  
                    Stay tuned for more fresh content.
                </p>
            </div>

            <livewire:share-article id="{{ $article->id }}" />
        </div>

        {{-- Social Links --}}
        {{-- <div class="bg-white rounded-2xl shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Follow Us</h2>
            <div class="flex gap-4">
                <x-fab-facebook class="w-5 h-5" />
                <x-fab-twitter class="w-5 h-5" />
                <x-fab-instagram class="w-5 h-5" />
                <x-fab-github class="w-5 h-5" />
            </div>
        </div> --}}
    </div>
</div>
</div>

