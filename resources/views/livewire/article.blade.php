<?php

use Livewire\Volt\Component;
use App\Models\Article;
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

        // Increment views
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

    public function clap()
    {
        $this->article->increment('claps');
        $this->article->refresh();
    }
};
?>

<div class="bg-gray-900 text-gray-200 min-h-screen">
    <section class="container mx-auto px-4 max-w-6xl py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            
            <!-- Main Content -->
            <article class="lg:col-span-2">
                <!-- Thumbnail -->
                <div class="">
                    <img src="{{ Storage::url($article->thumbnail) ?? 'https://placehold.co/1200x600/8B5CF6/FFFFFF/png?text=Article' }}" 
                         alt="{{ $article->title }}" 
                         class="rounded-2xl w-full object-cover mb-4">
                    @if($article->img_credit)
                        <span class="block text-xs ml-8 mb-8 text-gray-400">
                            Credit: {{ $article->img_credit }}
                        </span>
                    @endif
                </div>

                <!-- Title -->
                <h1 class="text-4xl font-bold mb-4">{{ $article->title }}</h1>
                {{-- <p class="text-gray-400 mb-6">
                    Published on {{ $article->published_date?->format('M d, Y') }}
                </p> --}}

                <div class="flex gap-5">
                    <p class="text-sm text-gray-500">
                        Published {{ $article->published_date->format('M d, Y') }}
                        • {{ $article->categories->pluck('name')->join(', ') }}
                    </p>

                    <!-- Stats -->
                    <div class="flex items-center gap-6 text-sm text-gray-400 mb-6">
                        <span><i class="fas fa-eye mr-1"></i> {{ $article->views }} views</span>
                        <livewire:components.article-clap id="{{ $article->id }}" />
                    </div>
                </div>

                <!-- Body -->
                <div class="prose prose-invert max-w-none text-gray-400">
                    {!! $article->body !!}
                </div>

                <!-- Prev / Next Navigation -->
                <div class="flex justify-between items-center mt-12">
                    @if($previousArticle)
                        <a wire:navigate href="{{ route('article', $previousArticle->slug) }}" 
                           class="flex items-center gap-3 text-purple-400 hover:text-purple-300">
                            <img src="{{ Storage::url($previousArticle->thumbnail) ?? 'https://placehold.co/100x100/8B5CF6/FFFFFF/png' }}" 
                                 class="w-16 h-16 rounded-lg object-cover">
                            <span>← {{ $previousArticle->title }}</span>
                        </a>
                    @else
                        <span></span>
                    @endif

                    @if($nextArticle)
                        <a wire:navigate href="{{ route('article', $nextArticle->slug) }}" 
                           class="flex items-center gap-3 text-purple-400 hover:text-purple-300">
                            <span>{{ $nextArticle->title }} →</span>
                            <img src="{{ Storage::url($nextArticle->thumbnail) ?? 'https://placehold.co/100x100/8B5CF6/FFFFFF/png' }}" 
                                 class="w-16 h-16 rounded-lg object-cover">
                        </a>
                    @endif
                </div>

                <!-- Related Articles -->
                <div class="mt-16">
                    <h2 class="text-2xl font-bold mb-6">Related Articles</h2>
                    <div class="grid md:grid-cols-3 gap-6">
                        @foreach($relatedArticles as $related)
                            <a wire:navigate href="{{ route('article', $related->slug) }}" class="bg-gray-800 rounded-xl overflow-hidden hover:shadow-lg transition">
                                <img src="{{ Storage::url($related->thumbnail)  ?? 'https://placehold.co/600x400/8B5CF6/FFFFFF/png?text=Article' }}" 
                                 alt="{{ $related->title }}" 
                                 class="rounded-lg mb-4 w-full h-48 object-cover">
                                <div class="p-4">
                                    <h3 class="font-semibold">{{ $related->title }}</h3>
                                    {{-- <p class="text-sm text-gray-400 mt-1">{{ $related->published_date?->format('M d, Y') }}</p> --}}
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </article>

            <!-- Sidebar -->
            <aside class="space-y-8 ">
                <div class="sticky top-40">
                    <!-- About Section -->
                    <div class="bg-gray-800 p-6 rounded-2xl">
                        <h3 class="text-xl font-bold mb-3">About QueerWorx</h3>
                        <p class="text-gray-400 text-sm">
                            QueerWorx is a community-driven initiative in Lesotho, amplifying LGBTQ+ voices through storytelling and advocacy.
                        </p>
                    </div>

                    <!-- Social Media -->
                    {{-- <div class="bg-gray-800 p-6 rounded-2xl">
                        <h3 class="text-xl font-bold mb-3">Follow Us</h3>
                        <div class="flex gap-3">
                            <a href="#" class="p-2 bg-blue-600 text-white rounded-full hover:bg-blue-700">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="p-2 bg-sky-500 text-white rounded-full hover:bg-sky-600">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="p-2 bg-pink-600 text-white rounded-full hover:bg-pink-700">
                                <i class="fab fa-instagram"></i>
                            </a>
                        </div>
                    </div> --}}

                    <livewire:components.social-share id="{{ $article->id }}" />
                </div>
            </aside>
        </div>
    </section>
</div>
