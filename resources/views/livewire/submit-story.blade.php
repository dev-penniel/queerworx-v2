<?php

use App\Models\Article;
use App\Models\Category;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new
#[Layout('components.layouts.app.frontend')]
class extends Component {
    use WithFileUploads;

    public $title = '';
    public $body = '';
    public $exerpt = '';
    public $authorName = '';
    public $authorEmail = '';
    public $thumbnail;
    public $selectedCategories = [];
    public $categories;
    public $submitted = false;

    public function mount()
    {
        $this->categories = Category::orderBy('name')->get();
    }

    public function submitStory()
    {
        $validated = $this->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string|min:80',
            'exerpt' => 'required|string|max:180',
            'authorName' => 'required|string|max:120',
            'authorEmail' => 'nullable|email|max:160',
            'thumbnail' => 'nullable|image|max:4096',
            'selectedCategories' => 'array',
            'selectedCategories.*' => 'exists:categories,id',
        ]);

        $slugBase = Str::slug($validated['title']);
        $slug = $slugBase;
        $counter = 2;

        while (Article::where('slug', $slug)->exists()) {
            $slug = $slugBase . '-' . $counter++;
        }

        $thumbnailPath = $this->thumbnail
            ? $this->thumbnail->store('images/thumbnails', 'public')
            : null;

        $article = Article::create([
            'title' => $validated['title'],
            'slug' => $slug,
            'body' => nl2br(e($validated['body'])),
            'exerpt' => $validated['exerpt'],
            'views' => 0,
            'claps' => 0,
            'thumbnail' => $thumbnailPath,
            'img_credit' => $validated['authorName'],
            'status' => 'draft',
            'published_date' => now()->toDateString(),
        ]);

        $article->categories()->sync($this->selectedCategories);

        $this->reset(['title', 'body', 'exerpt', 'authorName', 'authorEmail', 'thumbnail', 'selectedCategories']);
        $this->submitted = true;
    }
}; ?>

<main class="min-h-screen bg-[#111429] text-white">
    <section class="relative overflow-hidden py-16 sm:py-20">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_50%_0%,rgba(77,61,145,0.28),transparent_42%),linear-gradient(180deg,#1b1740_0%,#111429_100%)]"></div>

        <div class="relative mx-auto max-w-4xl px-6">
            <div class="text-center">
                <p class="text-sm font-semibold uppercase tracking-[0.24em] text-white/45">Xpressions</p>
                <h1 class="mt-3 text-4xl font-bold sm:text-5xl">Submit Your Story</h1>
                <p class="mx-auto mt-4 max-w-2xl text-white/60">
                    Share a story, article, reflection, or resource. Submissions are reviewed before appearing publicly.
                </p>
            </div>

            @if ($submitted)
                <div class="mt-10 rounded-[8px] border border-emerald-400/30 bg-emerald-500/10 p-6 text-center">
                    <h2 class="text-2xl font-bold text-emerald-200">Thank you for submitting.</h2>
                    <p class="mt-2 text-white/70">Your story is saved as a draft and will be reviewed by an admin.</p>
                </div>
            @endif

            <form wire:submit="submitStory" class="mt-10 grid gap-6 rounded-[8px] border border-white/10 bg-black/25 p-6 shadow-2xl shadow-black/20">
                <div class="grid gap-5 sm:grid-cols-2">
                    <label class="grid gap-2">
                        <span class="text-sm font-semibold">Your name</span>
                        <input wire:model="authorName" type="text" class="rounded-[8px] border border-white/10 bg-[#111429] px-4 py-3 text-white outline-none focus:border-purple-400">
                        @error('authorName') <span class="text-sm text-pink-300">{{ $message }}</span> @enderror
                    </label>

                    <label class="grid gap-2">
                        <span class="text-sm font-semibold">Email (optional)</span>
                        <input wire:model="authorEmail" type="email" class="rounded-[8px] border border-white/10 bg-[#111429] px-4 py-3 text-white outline-none focus:border-purple-400">
                        @error('authorEmail') <span class="text-sm text-pink-300">{{ $message }}</span> @enderror
                    </label>
                </div>

                <label class="grid gap-2">
                    <span class="text-sm font-semibold">Story title</span>
                    <input wire:model="title" type="text" class="rounded-[8px] border border-white/10 bg-[#111429] px-4 py-3 text-white outline-none focus:border-purple-400">
                    @error('title') <span class="text-sm text-pink-300">{{ $message }}</span> @enderror
                </label>

                <label class="grid gap-2">
                    <span class="text-sm font-semibold">Short summary</span>
                    <textarea wire:model="exerpt" rows="3" class="rounded-[8px] border border-white/10 bg-[#111429] px-4 py-3 text-white outline-none focus:border-purple-400"></textarea>
                    @error('exerpt') <span class="text-sm text-pink-300">{{ $message }}</span> @enderror
                </label>

                <label class="grid gap-2">
                    <span class="text-sm font-semibold">Full story</span>
                    <textarea wire:model="body" rows="10" class="rounded-[8px] border border-white/10 bg-[#111429] px-4 py-3 text-white outline-none focus:border-purple-400"></textarea>
                    @error('body') <span class="text-sm text-pink-300">{{ $message }}</span> @enderror
                </label>

                <div class="grid gap-5 sm:grid-cols-2">
                    <label class="grid gap-2">
                        <span class="text-sm font-semibold">Thumbnail image</span>
                        <input wire:model="thumbnail" type="file" accept="image/*" class="rounded-[8px] border border-white/10 bg-[#111429] px-4 py-3 text-sm text-white file:mr-4 file:rounded-full file:border-0 file:bg-purple-600 file:px-4 file:py-2 file:text-white">
                        @error('thumbnail') <span class="text-sm text-pink-300">{{ $message }}</span> @enderror
                    </label>

                    <div class="grid gap-2">
                        <span class="text-sm font-semibold">Categories</span>
                        <div class="grid max-h-40 gap-2 overflow-auto rounded-[8px] border border-white/10 bg-[#111429] p-4">
                            @forelse ($categories as $category)
                                <label class="flex items-center gap-3 text-sm text-white/75">
                                    <input wire:model="selectedCategories" value="{{ $category->id }}" type="checkbox" class="rounded border-white/20 bg-transparent text-purple-600">
                                    {{ $category->name }}
                                </label>
                            @empty
                                <p class="text-sm text-white/50">No categories have been created yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="rounded-full bg-purple-600 px-6 py-3 text-sm font-bold text-white transition hover:bg-purple-700">
                        Submit for Review
                    </button>
                </div>
            </form>
        </div>
    </section>
</main>
