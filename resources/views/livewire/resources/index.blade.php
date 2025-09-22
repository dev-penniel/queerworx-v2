<?php

use Livewire\Volt\Component;
use Illuminate\Support\Str;
use App\Models\Resource;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

new class extends Component {
    
    use WithPagination;
    
    public $title, $url, $search, $id, $originalTitle = '';

    public function getResourcesProperty()
    {
        return Resource::when($this->search, function ($query){
            $query->where('title', 'like', '%'.$this->search.'%');
        })->latest()->paginate(50);
    }

    public function edit($id)
    {
        $resource = Resource::findOrFail($id);

        $this->id = $resource->id;
        $this->title = $resource->title;
        $this->url = $resource->url;

    }


    public function createResource()
    {
        $validated = $this->validate([
            'title' => "required|string",
            'url' => "required|string",
        ]);

        Resource::create([
            'title' => $this->title,
            'url' => $this->url,
        ]);

        $this->reset();

        $this->dispatch('resource-created');
    }

    public function updateResource($id)
    {
        $validated = $this->validate([
            'title' => "required|string",
            'url' => "required|string",
        ]);

        $resource = Resource::findOrFail($id);

        $resource->update([
            'title' => $validated['title'],
            'url' => $validated['url'],
        ]);

        $this->dispatch('resource-updated');

    }



    public function deleteResource($id)
    {
        $resource = Resource::findOrFail($id);
        $resource->delete();
        $this->dispatch('resource-deleted', $id);
    }

}; ?>

<div>
    {{-- Create category modal --}}
    <flux:modal name="create-resource" class="md:w-96">
        <form wire:submit.ignore="createResource">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">Add New Resource</flux:heading>
                    <flux:text class="mt-2">Create a new resource entry</flux:text>
                </div>
    
                <flux:input wire:model="title" wire:model.live="title" label="Title" placeholder="Enter title..." />

                <flux:input wire:model="url" wire:model.live="url" label="Url" placeholder="Enter url..." />
    
                <div class="flex">
                    <flux:spacer />
     
                    <div class="flex items-center gap-4">
                        
            
                        <x-action-message class="me-3" on="resource-created">
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

    <flux:modal name="update-resource" class="md:w-96">
        <form wire:submit.ignore="updateResource({{ $id }})">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">Update Category</flux:heading>
                    <flux:text class="mt-2">{{ $this->originalTitle }}</flux:text>
                </div>
    
                <flux:input wire:model="title" wire:model.live="title" label="Title" placeholder="Enter title..." />

                <flux:input wire:model="url" wire:model.live="url" label="Url" placeholder="Enter url..." />
    
                <div class="flex">
                    <flux:spacer />
     
                    <div class="flex items-center gap-4">
                        
            
                        <x-action-message class="me-3" on="resource-updated">
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
                <flux:heading size="xl" level="1">{{ __('Resources') }}</flux:heading>
                <flux:breadcrumbs class="mb-4 mt-2">
                    <flux:breadcrumbs.item href="{{ route('dashboard') }}">Home</flux:breadcrumbs.item>
                    <flux:breadcrumbs.item >Resources</flux:breadcrumbs.item>
                </flux:breadcrumbs>
            </div>
        </div>
        <flux:separator variant="subtle" />
    </div>

    <div>

        <div class="flex justify-between items-center mb-5">
            
            
                <flux:modal.trigger name="create-resource">
                    <flux:button class="cursor-pointer">Add</flux:button>
                </flux:modal.trigger>
            

                

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
                        <td class="px-5 py-3 font-bold text-sm">Name</td>
                        <td class="px-5 py-3 font-bold text-sm">Url</td>
                        <td class="px-5 py-3 font-bold text-sm">Created</td>
                        <td class="px-5 py-3 font-bold text-sm">Updated</td>

                            @canany(['category-edit', 'category-delete'])

                                <td class="px-5 py-3 font-bold text-sm">Actions</td>
                            @endcanany
                        
                    </tr>
                </th>
                </thead>
                <tbody>

                @foreach ($this->resources as $resource)
                
                    <tr class="border-b border-gray-300 hover:bg-gray-100">
                        <td class="px-5 py-2 text-sm">{{ $resource->title }}</td>
                        <td class="px-5 py-2 text-sm">{{ $resource->url }}</td>
                        <td class="px-5 py-2 text-sm">{{ $resource->created_at->format('M d, Y H:i') }}</td>
                        <td class="px-5 py-2 text-sm">{{ $resource->updated_at->format('M d, Y H:i') }}</td>
                        
                            
                            @canany(['category-edit', 'category-delete'])
                                <td class="px-5 py-2 text-sm flex gap-2 place-content-center">
                                    
                                        @can("category-edit")
                                            <flux:modal.trigger wire:click="edit({{ $resource->id }})" name="update-resource">
                                                <flux:icon.pencil-square class="size-5 cursor-pointer" color="green" />
                                            </flux:modal.trigger>
                                        @endcan

                                        @can("category-delete")
                                            <flux:icon.trash class="size-5 cursor-pointer" color="red" wire:click="deleteResource({{ $resource->id }})" wire:confirm="Are you sure you want to delete?" />
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

            {{ $this->resources->links() }}

        </div>

    </div>
</div>
