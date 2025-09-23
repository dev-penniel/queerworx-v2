<?php
use Livewire\Volt\Component;
use Illuminate\Support\Str;
use App\Models\Advert;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Livewire\WithFileUploads;

new class extends Component {
    
    use WithPagination, WithFileUploads;
    
    public $title, $thumbnail, $url, $position, $search, $id, $currentThumbnail, $thumbnailToRemove;

        public function getAdvertsProperty()
        {
            return Advert::when($this->search, function ($query){
                $query->where('title', 'like', '%'.$this->search.'%');
            })->latest()->paginate(50);
        }


        public function create()
        {
            $validated = $this->validate([
                'title' => "required|string",
                'url' => "required|string",
                'position' => "required|integer",
                'thumbnail' => 'nullable|image|max:2048',
            ]);

            $thumbnailPath = null;

            if($this->thumbnail){
                // Handle cover image upload if exists
                $thumbnailPath = $this->thumbnail->store('images/adverts', 'public');
            }

            Advert::create([
                'title' => $validated['title'],
                'url' => $validated['url'],
                'position' => $validated['position'],
                'thumbnail' => $thumbnailPath,
            ]);

            $this->reset();

            $this->dispatch('advert-created');
        }

        public function edit($id)
        {
            $advert = Advert::findOrFail($id);

            $this->id = $advert->id;
            $this->title = $advert->title;
            $this->url = $advert->url;
            $this->position = $advert->position;
            $this->currentThumbnail = $advert->thumbnail;

        }

        public function update($id)
        {
            $validated = $this->validate([
                'title' => "required|string",
                'url' => "required|string",
                'position' => "required|integer",
                'thumbnail' => 'nullable|image|max:2048',
            ]);

            $advert = Advert::findOrFail($id);

            // Handle cover image upload if exists
            $thumbnailPath = $this->currentThumbnail;

            if ($this->thumbnail) {
                
                // Delete old cover image if exists
                if ($this->currentThumbnail) {
                    Storage::disk('public')->delete($this->currentThumbnail);
                }

                $thumbnailPath = $this->thumbnail->store('images/adverts', 'public');

            } elseif ($this->currentThumbnail === null) {

                Storage::disk('public')->delete($this->thumbnailToRemove);

                // Cover image was removed
                $thumbnailPath = null;
            }

            $advert->update([
                'title' => $validated['title'],
                'url' => $validated['url'],
                'position' => $validated['position'],
                'thumbnail' => $thumbnailPath,
            ]);

            $this->dispatch('advert-updated');

        }


        public function delete($id)
        {
            $advert = Advert::findOrFail($id);
            $advert->delete();
            $this->dispatch('advert-deleted', $id);
        }

        // remove cover image
        public function removeCoverImage()
        {
            $this->thumbnailToRemove = $this->currentThumbnail;
            $this->currentThumbnail = null;
            $this->thumbnail = null;
        }

}; ?>

<div>
    {{-- Create category modal --}}
    <flux:modal name="create-advert" class="md:w-96">
        <form wire:submit.ignore="create">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">Add New Advert</flux:heading>
                    <flux:text class="mt-2">Create a new advert entry</flux:text>
                </div>
    
                <flux:input wire:model="title" label="Title" placeholder="Enter title..." />
                <div class="flex gap-4">
                    <flux:input wire:model="url" label="Url" placeholder="Enter url..." />
                    <flux:input type="number" wire:model="position" label="Position" placeholder="Enter position..." />
                </div>

                {{-- Cover Image --}}
                <div class="space-y-4 mb-10">
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

    
                <div class="flex">
                    <flux:spacer />
     
                    <div class="flex items-center gap-4">
                        
            
                        <x-action-message class="me-3" on="advert-created">
                            {{ __('Saved.') }}
                        </x-action-message>

                        <div class="flex items-center justify-end">
                            <flux:button variant="primary" type="submit" class="w-full cursor-pointer">{{ __('Save') }}</flux:button>
                        </div>

                    </div>
                </div>
            </div>
        </form>
    </flux:modal>

    {{-- Create category modal --}}
    <flux:modal name="update-advert" class="md:w-96">
        <form wire:submit.ignore="update({{ $id }})">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">Update Advert</flux:heading>
                    <flux:text class="mt-2">{{ $this->title }}</flux:text>
                </div>
    
                <flux:input wire:model="title" label="Title" placeholder="Enter title..." />
                <div class="flex gap-4">
                    <flux:input wire:model="url" label="Url" placeholder="Enter url..." />
                    <flux:input type="number" wire:model="position" label="Position" placeholder="Enter position..." />
                </div>

                {{-- Cover Image --}}
                <div class="space-y-4 mb-5 ">
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
                                <button type="button" wire:click="removeCoverImage" 
                                        class="mt-2 text-sm text-red-600 hover:text-red-800 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Remove Current Image
                                </button>
                            @endif
                        </div>

                        @if ($thumbnail == null && $currentThumbnail !== null)
                            <div class="mt-2">
                                <span class="block text-sm font-medium text-gray-700 mb-1">Current Cover Image:</span>
                                <img src="{{ Storage::url($currentThumbnail)}}" class="h-40 w-full object-cover rounded-md">
                                <button type="button" wire:click="removeCoverImage" 
                                        class="mt-2 text-sm text-red-600 hover:text-red-800 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Remove Current Image
                                </button>
                            </div>
                        @endif
                    </div>
                </div>

    
                <div class="flex">
                    <flux:spacer />
     
                    <div class="flex items-center gap-4">
                        
            
                        <x-action-message class="me-3" on="advert-updated">
                            {{ __('Saved.') }}
                        </x-action-message>

                        <div class="flex items-center justify-end">
                            <flux:button variant="primary" type="submit" class="w-full cursor-pointer">{{ __('Save') }}</flux:button>
                        </div>

                    </div>
                </div>
            </div>
        </form>
    </flux:modal>


    <div class="relative mb-6 w-full">
        <div class="flex justify-between items-center">
            <div>
                <flux:heading size="xl" level="1">{{ __('Adverts') }}</flux:heading>
                <flux:breadcrumbs class="mb-4 mt-2">
                    <flux:breadcrumbs.item href="{{ route('dashboard') }}">Home</flux:breadcrumbs.item>
                    <flux:breadcrumbs.item >Adverts</flux:breadcrumbs.item>
                </flux:breadcrumbs>
            </div>
        </div>
        <flux:separator variant="subtle" />
    </div>
    <div>

        <div class="flex justify-between items-center mb-5">
            
            @can("category-create")
                <flux:modal.trigger name="create-advert">
                    <flux:button class="cursor-pointer">Add</flux:button>
                </flux:modal.trigger>
            @endcan

                

            <div class="w-[200px]">
                <flux:input
                    wire:model.live="search"
                    type="text"
                    required
                    placeholder="Search"
                    autocomplete="current-password"
                />
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="table-auto w-full">
                <thead>
                <th>
                    <tr class="bg-gray-100">
                        <td class="px-5 py-3 font-bold text-sm">Thumbnail</td>
                        <td class="px-5 py-3 font-bold text-sm">Title</td>
                        <td class="px-5 py-3 font-bold text-sm">Url</td>
                        <td class="px-5 py-3 font-bold text-sm">Position</td>
                        <td class="px-5 py-3 font-bold text-sm">Created</td>
                        <td class="px-5 py-3 font-bold text-sm">Updated</td>

                            @canany(['category-edit', 'category-delete'])

                                <td class="px-5 py-3 font-bold text-sm">Actions</td>
                            @endcanany
                        
                    </tr>
                </th>
                </thead>
                <tbody>

                @foreach ($this->adverts as $advert)
                
                    <tr class="border-b border-gray-300 hover:bg-gray-100">
                        <td class="px-5 py-2 text-sm whitespace-nowrap">
                            @if ($advert->thumbnail)
                                <div class="mt-2">
                                    <img src="{{ Storage::url($advert->thumbnail) }}" class="h-15 w-30 object-cover rounded-md">
                                </div>
                            @endif
                        </td>
                        <td class="px-5 py-2 text-sm">{{ $advert->title }}</td>
                        <td class="px-5 py-2 text-sm">{{ $advert->url }}</td>
                        <td class="px-5 py-2 text-sm">{{ $advert->position }}</td>
                        <td class="px-5 py-2 text-sm">{{ $advert->created_at->format('M d, Y H:i') }}</td>
                        <td class="px-5 py-2 text-sm">{{ $advert->updated_at->format('M d, Y H:i') }}</td>
                        
                            
                            @canany(['category-edit', 'category-delete'])
                                <td class="px-5 py-2 text-sm flex gap-2 place-content-center">
                                    
                                        @can("category-edit")
                                            <flux:modal.trigger wire:click="edit({{ $advert->id }})" name="update-advert">
                                                <flux:icon.pencil-square class="size-5 cursor-pointer" color="green" />
                                            </flux:modal.trigger>
                                        @endcan

                                        @can("category-delete")
                                            <flux:icon.trash class="size-5 cursor-pointer" color="red" wire:click="delete({{ $advert->id }})" wire:confirm="Are you sure you want to delete?" />
                                            {{-- <flux:icon.trash class="size-5 cursor-pointer" color="red" wire:click="$js.showAlert({{ $product->id }})" /> --}}
                                        @endcan
                                        

                                </td>
                            @endcanany
                        
                    </tr>

                @endforeach

                </tbody>
            </table>
        </div>
        

        <div class="mt-5">

            {{ $this->adverts->links() }}

        </div>

    </div>
</div>
