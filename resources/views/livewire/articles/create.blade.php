<?php

use Livewire\Volt\Component;
use App\Models\Category;
use App\Models\Article;


new class extends Component {
    

    public $title, $slug, $exerpt, $body, $views, $claps, $thumbnail, $status, $publishedDate, $imgCredit;

    public $categories = [];

    public $selectedCategories = [];

    public function mount()
    {
        $this->categories = Category::latest()->get(['id', 'name']);
    }


    public function createArticle()
    {
        $categoryIds = array_column($this->selectedCategories, 0);

        $validated = $this->validate([
            'title' => 'required|string|max:255',
            'body' => 'required',
            'exerpt' => 'required|string|max:100',
            'views' => 'nullable',
            'claps' => 'nullable',
            'thumbnail' => 'nullable|image|max:2048',
            'imgCredit' => 'nullable',
            'status' => 'string',
        ]);

        $this->slug = Str::slug($this->title);

        $article = Article::create([
            'title' => $validated['title'],
            'slug' => $this->slug,
            'body' => $validated['body'],
            'exerpt' => $validated['exerpt'],
            'views' => $validated['views'],
            'claps' => $validated['claps'],
            'img_credit' => $validated['imgCredit'],
            'status' => $validated['status'],
            'published_date' => $this->publishedDate,
        ]);

        $article->categories()->sync($categoryIds);

        $this->reset();

        $this->dispatch('article-created');

    }

}; ?>

<div>
    <div class="relative mb-6 w-full">
        <div class="flex justify-between items-center">
            <div>
                <flux:heading size="xl" level="1">{{ __('New Article') }}</flux:heading>
                <flux:breadcrumbs class="mb-4 mt-2">
                    <flux:breadcrumbs.item href="{{ route('dashboard') }}">Home</flux:breadcrumbs.item>
                    <flux:breadcrumbs.item >Articles</flux:breadcrumbs.item>
                    <flux:breadcrumbs.item >New</flux:breadcrumbs.item>
                </flux:breadcrumbs>
            </div>
        </div>
        <flux:separator variant="subtle" />
    </div>

    <form wire:submit="createArticle" >

        <div>

            <flux:input
                class="mb-5"
                wire:model="title"
                :label="__('Title')"
                type="text"
                required
                placeholder="Slug is generated automaticaly..."
                autocomplete="title"
            />

            <div class="pb-5" x-data="quillEditor()" x-init="initQuill()">
                <!-- Quill editor container -->
                <div id="editor-container" style="height: 200px;"></div>
                <!-- Hidden input to bind with Livewire -->
                <input type="hidden" x-model="content" @input.debounce="updateContent">
            </div>

            </div>

            <flux:input
                class="mb-5"
                wire:model="thumbnail"
                :label="__('Thumbnail')"
                type="file"
            />

            <flux:input
                class="mb-5"
                wire:model="imgCredit"
                :label="__('Thumbnail Credit')"
                type="text"
                required
                placeholder="Image Credit"
                autocomplete="title"
            />

            <flux:select
                class="mb-5"
                wire:model="status"
                :label="__('Status')"
                type="text"
                required
            >

                <flux:select.option value="draft" >Draft</flux:select.option>
                <flux:select.option value="published" >Published</flux:select.option>

            </flux:select>

            {{-- <select multiple>

                @forelse ($categories as $category )

                    <flux:select.option value="{{ $category->id }}" >{{ $category->name }}</flux:select.option>

                @empty

                    <flux:select.option value="draft" >No Categories</flux:select.option>
            
                @endforelse

            </select> --}}

            <div x-data="searchableDropdown" class="relative w-80">

                <label class="block mb-2 text-gray-700 font-medium" for="option">Select Option</label>

                <div class="flex gap-4">
                    <template x-for="(selectedOption, index) in selectedOptions" :key="index">
                        <p @click="removeFromSelected(selectedOption)" x-text="selectedOption[1]"></p>
                    </template>
                </div>

                <!-- Selected options -->
                <div @click="toggleDropDown" class="border border-gray rounded-lg px-4 py-2 flex justify-between items-center cursor-pointer focus:ring focus:ring-blue-200">
                    <span x-text="'Choose an option'"></span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                    </svg>
                </div>

                <!-- Drop Down Options -->
                <div x-show="isOpen" @click.away="closeDropDown" class="absolute z-10 bg-white shadow-lg rounded-lg mt-2 w-full border border-gray-200"> 

                    <!-- Input Search -->
                    <div class="p-2">
                    <input x-model="query" type="text" placeholder="Search..." class="w-full border border-gray-300 rounded-lg px-3
                    py-2 focus:outline-none focus:ring focus:ring-blue-200"> 
                    </div>

                    <!-- Options -->
                    <ul>
                        <template x-for="option in filteredOptions" :key="option.id">
                            <li @click="selectOption(option)" class="px-4 py-2 hover:bg-gray-100 cursor-pointer" x-text="option.name"></li>
                        </template>
                        <li x-show="filteredOptions.length === 0" class="px-4 py-4 text-gray-500 text-center"> No Options Found</li>
                    </ul>

                </div>

            </div>

            <flux:input
                class="mb-5"
                wire:model="exerpt"
                :label="__('Exerpt')"
                type="text"
                required
                placeholder="Exerpt..."
            />

            <flux:input
                class="mb-5"
                wire:model="publishedDate"
                :label="__('Published Date')"
                type="date"
                required
                autocomplete="title"
            />

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <flux:button variant="primary" type="submit" class="w-full">{{ __('Save') }}</flux:button>
                </div>
    
                <x-action-message class="me-3" on="article-created">
                    {{ __('Saved.') }}
                </x-action-message>
            </div>

            {!! $body !!}
            
        </div>

    </form>

</div>

<!-- Include the Quill library -->
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>

<!-- Initialize Quill editor -->
<script>

    document.addEventListener('alpine:init', () => {

        // Text Editor
        const toolbarOptions = [
        ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
        ['blockquote', 'code-block'],
        ['link', 'image', 'video'],

        [{ 'header': 1 }, { 'header': 2 }],               // custom button values
        [{ 'list': 'ordered'}, { 'list': 'bullet' }, { 'list': 'check' }],
        [{ 'indent': '-1'}, { 'indent': '+1' }],          // outdent/indent
        [{ 'header': [1, 2, 3, 4, 5, 6, false] }],

        [{ 'align': [] }],

        ['clean']                                         // remove formatting button
        ];

        //   Searchable multi select
        Alpine.data('quillEditor', () => ({
            quill: null,
            content: @entangle('body'), // Livewire property

            initQuill() {
                // Initialize Quill editor
                this.quill = new Quill('#editor-container', {
                    theme: 'snow',
                    placeholder: 'Compose an epic...',
                    modules: {
                        toolbar: toolbarOptions
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
        }));

        Alpine.data('searchableDropdown', () => ({

            isOpen: false,
            query: '',
            selectedOptions: @entangle('selectedCategories'),
            options: @js($categories),
            allOptions: @js($categories),

            get filteredOptions(){
                return this.options.filter(option =>
                    option.name.toLowerCase().includes(this.query.toLowerCase()) &&
                    !this.selectedOptions.includes(option.id)
                );
            },
            toggleDropDown(){
                this.isOpen = !this.isOpen;
            },
            closeDropDown(){
                this.isOpen = false;
            },
            removeSelectedFromOptions(){
                this.options = this.options.filter(option =>
                    !this.selectedOptions.some(([id]) => id === option.id)
                );
            },
            removeFromSelected(option){
                this.selectedOptions = this.selectedOptions.filter(id => id !== option);
                this.resetOptions(option);
                
            },
            selectOption(option){
                this.selectedOptions.push([option.id, option.name]);
                this.query = '';
                this.closeDropDown();
                this.removeSelectedFromOptions();
            },
            resetOptions(option) {
                this.options = this.allOptions.filter(option => 
                    !this.selectedOptions.some(([id]) => id === option.id)
                );
            }

        }));
    });
</script>