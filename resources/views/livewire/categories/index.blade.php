<?php

use Livewire\Volt\Component;
use Illuminate\Support\Str;
use App\Models\Category;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

new class extends Component {

    use WithPagination;
    
    // $original name = name from database
    public $name, $originalName, $editedName, $slug, $id;
    public $search = '';

    public function getCategoriesProperty()
    {
        return Category::when($this->search, function ($query){
            $query->where('name', 'like', '%'.$this->search.'%');
        })->latest()->paginate(50);
    }
    

    public function createCategory()
    {
        $validated = $this->validate([
            'name' => "required|string",
        ]);

        // Generate slug from model name
        $this->slug = Str::slug($this->name);

        Category::create([
            'name' => $this->name,
            'slug' => $this->slug,
        ]);

        $this->reset();

        $this->dispatch('category-created');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);

        $this->id = $category->id;
        $this->editedName = $category->name;
        $this->originalName = $category->name;

    }

    public function updateCategory($id)
    {
        $validated = $this->validate([
            'editedName' => 'required|string',
        ]);

        $category = Category::findOrFail($id);

        $slug = Str::slug($this->editedName);

        $category->update([
            'name' => $validated['editedName'],
            'slug' => $slug,
        ]);

        $this->originalName = $validated['editedName'];

        $this->dispatch('category-updated');

    }


    public function deleteCategory($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        $this->dispatch('category-deleted', $id);
    }

}; ?>

<div>

    {{-- Create category modal --}}
    <flux:modal name="create-category" class="md:w-96">
        <form wire:submit.ignore="createCategory">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">Add New Category</flux:heading>
                    <flux:text class="mt-2">Create a new category entry</flux:text>
                </div>
    
                <flux:input wire:model="name" wire:model.live="slug" label="Name" placeholder="Slug is generated automaticaly" />
    
                <div class="flex">
                    <flux:spacer />
     
                    <div class="flex items-center gap-4">
                        
            
                        <x-action-message class="me-3" on="category-created">
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

    {{-- Update category modal --}}
    <flux:modal name="update-category" class="md:w-96">
        <form wire:submit.ignore="updateCategory({{ $id }})">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">Update Category</flux:heading>
                    <flux:text class="mt-2">{{ $this->originalName }}</flux:text>
                </div>
    
                <flux:input wire:model="editedName" wire:model.live="slug" label="Name" placeholder="Slug is generated automaticaly" />
    
                <div class="flex">
                    <flux:spacer />
     
                    <div class="flex items-center gap-4">
                        
            
                        <x-action-message class="me-3" on="category-updated">
                            {{ __('Saved.') }}
                        </x-action-message>

                        <div class="flex items-center justify-end">
                            <flux:button variant="primary" type="submit" class="w-full">{{ __('Save') }}</flux:button>
                        </div>

                    </div>
                </div>
            </div>
        </form>
    </flux:modal>

    <div class="relative mb-6 w-full">
        <div class="flex justify-between items-center">
            <div>
                <flux:heading size="xl" level="1">{{ __('Categories') }}</flux:heading>
                <flux:breadcrumbs class="mb-4 mt-2">
                    <flux:breadcrumbs.item href="{{ route('dashboard') }}">Home</flux:breadcrumbs.item>
                    <flux:breadcrumbs.item >Categories</flux:breadcrumbs.item>
                </flux:breadcrumbs>
            </div>
        </div>
        <flux:separator variant="subtle" />
    </div>
    <div>

        <div class="flex justify-between items-center mb-5">
            
            @can("category-create")
                <flux:modal.trigger name="create-category">
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
                        <td class="px-5 py-3 font-bold text-sm">Name</td>
                        <td class="px-5 py-3 font-bold text-sm">Slug</td>
                        <td class="px-5 py-3 font-bold text-sm">Created</td>
                        <td class="px-5 py-3 font-bold text-sm">Updated</td>

                            @canany(['category-edit', 'category-delete'])

                                <td class="px-5 py-3 font-bold text-sm">Actions</td>
                            @endcanany
                        
                    </tr>
                </th>
                </thead>
                <tbody>

                @foreach ($this->categories as $category)
                
                    <tr class="border-b border-gray-300 hover:bg-gray-100">
                        <td class="px-5 py-2 text-sm">{{ $category->name }}</td>
                        <td class="px-5 py-2 text-sm">{{ $category->slug }}</td>
                        <td class="px-5 py-2 text-sm">{{ $category->created_at->format('M d, Y H:i') }}</td>
                        <td class="px-5 py-2 text-sm">{{ $category->updated_at->format('M d, Y H:i') }}</td>
                        
                            
                            @canany(['category-edit', 'category-delete'])
                                <td class="px-5 py-2 text-sm flex gap-2 place-content-center">
                                    
                                        @can("category-edit")
                                            <flux:modal.trigger wire:click="edit({{ $category->id }})" name="update-category">
                                                <flux:icon.pencil-square class="size-5 cursor-pointer" color="green" />
                                            </flux:modal.trigger>
                                        @endcan

                                        @can("category-delete")
                                            <flux:icon.trash class="size-5 cursor-pointer" color="red" wire:click="deleteCategory({{ $category->id }})" wire:confirm="Are you sure you want to delete?" />
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

            {{ $this->categories->links() }}

        </div>

    </div>
</div>

{{-- @script
<script>
    $js('showAlert', (id) => {

        confirm('Are you sure you want to delete?');

    })
</script>
@endscript --}}



