<?php

use Livewire\Volt\Component;
use App\Models\Category;
use App\Models\Article;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Storage;


new class extends Component {

    use WithFileUploads;
    

    public $title, $slug, $exerpt, $body, $thumbnail, $status, $publishedDate, $imgCredit;

    public $categories = [];

    public $selectedCategories = [];

    public function mount()
    {
        $this->categories = Category::latest()->get(['id', 'name']);
    }


    public function createArticle()
    {
        $categoryIds = $this->selectedCategories;

        $validated = $this->validate([
            'title' => 'required|string|max:255',
            'body' => 'required',
            'exerpt' => 'required|string|max:100',
            'thumbnail' => 'nullable|image|max:2048',
            'imgCredit' => 'nullable',
            'status' => 'string',
            'selectedCategories' => 'array',
            'selectedCategories.*' => 'exists:categories,id',
        ]);

        $this->slug = Str::slug($validated['title']);

        $thumbnailPath = null;

        if($this->thumbnail){
            // Handle cover image upload if exists
            $thumbnailPath = $this->thumbnail->store('images/thumbnails', 'public');
        }

        $article = Article::create([
            'title' => $validated['title'],
            'slug' => $this->slug,
            'body' => $validated['body'],
            'exerpt' => $validated['exerpt'],
            'views' => 0,
            'claps' => 0,
            'img_credit' => $validated['imgCredit'],
            'status' => $validated['status'],
            'published_date' => $this->publishedDate,
            'thumbnail' => $thumbnailPath,
        ]);

        $article->categories()->sync($categoryIds);


        $this->dispatch('article-created');

    }

}; ?>

<div>
    <div class="relative mb-6 w-full max-w-6xl lg:mx-auto">
        <div class="flex justify-between items-center">
            <div>
                <flux:heading size="xl" level="1">{{ __('New Article') }}</flux:heading>
                <flux:breadcrumbs class="mb-4 mt-2">
                    <flux:breadcrumbs.item href="{{ route('dashboard') }}">Home</flux:breadcrumbs.item>
                    <flux:breadcrumbs.item href="{{ route('articles.index') }}" >Articles</flux:breadcrumbs.item>
                    <flux:breadcrumbs.item >New</flux:breadcrumbs.item>
                </flux:breadcrumbs>
            </div>
        </div>
        <flux:separator variant="subtle" />
    </div>

    <form wire:submit="createArticle" >

        {{-- Demo Grid

        <div class='grid grid-cols-3 gap-10'>

            <div class='col-span-2 h-5 bg-black'>

            </div>

            <div class='col-span-1 h-5 bg-black'>

            </div>

        </div> --}}


        <div class='grid grid-cols-3 gap-10 max-w-6xl lg:mx-auto'>

            <div class='col-span-2'>
                <flux:input
                    class="mb-5"
                    wire:model="title"
                    :label="__('Title')"
                    type="text"
                    required
                    placeholder="Slug is generated automaticaly..."
                    autocomplete="title"
                />

                <div wire:ignore class="pb-5" x-data="{
                    quill: null,
                    content: @entangle('body'), // Livewire property

                    initQuill() {
                        // Initialize Quill editor
                        this.quill = new Quill('#editor-container', {
                            theme: 'snow',
                            placeholder: 'Compose an epic...',
                            modules: {
                                toolbar: [
                                    ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
                                    ['blockquote', 'code-block'],
                                    ['link', 'image', 'video'],

                                    [{ 'header': 1 }, { 'header': 2 }],               // custom button values
                                    [{ 'list': 'ordered'}, { 'list': 'bullet' }, { 'list': 'check' }],
                                    [{ 'indent': '-1'}, { 'indent': '+1' }],          // outdent/indent
                                    [{ 'header': [1, 2, 3, 4, 5, 6, false] }],

                                    [{ 'align': [] }],

                                    ['clean']                                         // remove formatting button
                                ]
                            }
                        });

                        // Set initial content if it exists
                        if (this.content) {
                            this.quill.root.innerHTML = this.content;
                        }

                        // Update Alpine model when Quill content changes
                        this.quill.on('text-change', () => {
                            this.content = this.quill.root.innerHTML;
                        });

                        // Watch for external content changes (like Livewire updates)
                        this.$watch('content', (value) => {
                            if (this.quill.root.innerHTML !== value) {
                                this.quill.root.innerHTML = value;
                            }
                        });
                    },

                    updateContent() {
                        // This method is triggered by the hidden input
                        // The debounce modifier helps prevent excessive updates
                        this.content = this.quill.root.innerHTML;
                    }
                }" x-init="initQuill()">
                    <!-- Quill editor container -->
                    <div id="editor-container" style="height: 200px;"></div>
                    <!-- Hidden input to bind with Livewire -->
                    <input type="hidden" x-model="content" @input.debounce="updateContent">
                </div>

                <flux:textarea
                    class="mb-5"
                    wire:model="exerpt"
                    :label="__('Exerpt')"
                    type="text"
                    required
                    placeholder="Exerpt..."
                />

                <div class="flex gap-5 align-center">

                    <flux:input
                    wire:model="publishedDate"
                    :label="__('Published Date')"
                    type="date"
                    required
                    autocomplete="title"
                    />

                    <flux:select
                        class="w-1/2"
                        wire:model="status"
                        :label="__('Status')"
                        type="text"
                        required
                    >

                        <flux:select.option value="draft" >Draft</flux:select.option>
                        <flux:select.option value="published" >Published</flux:select.option>

                    </flux:select>

                </div>

            </div>



            <div class='col-span-1 '>
                
                {{-- Cover Image --}}
                <div class="space-y-4 mb-5">
                    <div class="space-y-2">
                        <div x-data="{ isUploading: false, progress: 0 }" 
                            x-on:livewire-upload-start="isUploading = true"
                            x-on:livewire-upload-finish="isUploading = false"
                            x-on:livewire-upload-error="isUploading = false"
                            x-on:livewire-upload-progress="progress = $event.detail.progress">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Thumbnail (Max 2MB)</label>
                            <div class="flex items-center justify-center w-full">
                                <label class="flex flex-col w-full h-32 border-2 border-dashed rounded-lg hover:bg-gray-50 hover:border-gray-300 transition-all">
                                    <div class="flex flex-col items-center justify-center pt-7">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                        </svg>
                                        <p class="pt-1 text-sm text-gray-600">Click to upload cover image</p>
                                    </div>
                                    <input type="file" class="opacity-0" wire:model="thumbnail" accept="image/*" />
                                </label>
                            </div>
                            <div x-show="isUploading" class="mt-2">
                                <progress max="100" x-bind:value="progress" class="w-full"></progress>
                            </div>
                            @error('thumbnail') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                        </div>

                        <!-- Cover Image Preview -->
                        <div wire:loading.remove wire:target="thumbnail">
                            @if ($thumbnail)
                                <div class="mt-2">
                                    <span class="block text-sm font-medium text-gray-700 mb-1">Preview:</span>
                                    <img src="{{ $thumbnail->temporaryUrl() }}" class="h-40 w-full object-cover rounded-md">
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <flux:input
                    class="mb-5"
                    wire:model="imgCredit"
                    :label="__('Thumbnail Credit')"
                    type="text"
                    required
                    placeholder="Image Credit"
                    autocomplete="title"
                />

                <div class="mb-5">
                    <label class="mb-2 block text-sm font-medium text-white">Categories</label>
                    <div class="grid max-h-48 gap-2 overflow-auto rounded-lg border border-white/10 bg-[#111429] p-4">
                        @forelse ($categories as $category)
                            <label class="flex items-center gap-3 text-sm text-white/80">
                                <input wire:model="selectedCategories" value="{{ $category->id }}" type="checkbox" class="rounded border-white/20 bg-transparent text-purple-600">
                                <span>{{ $category->name }}</span>
                            </label>
                        @empty
                            <p class="text-sm text-white/55">No categories have been created yet.</p>
                        @endforelse
                    </div>
                    @error('selectedCategories.*') <span class="mt-1 block text-sm text-pink-300">{{ $message }}</span> @enderror
                </div>

                

                <div class="flex items-center gap-4">
                    <div class="flex items-center justify-end">
                        <flux:button variant="primary" type="submit" class="w-full">{{ __('Save') }}</flux:button>
                    </div>
        
                    <x-action-message class="me-3" on="article-created">
                        {{ __('Saved.') }}
                    </x-action-message>
                </div>
            </div>
            
        </div>

    </form>

</div>
