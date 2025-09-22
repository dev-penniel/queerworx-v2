<?php

use Livewire\Volt\Component;
use Illuminate\Support\Str;
use App\Models\Subscriber;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

new class extends Component {

    use WithPagination;
    
    public $email, $search, $id;

    public function getSubscribersProperty()
    {
        return Subscriber::when($this->search, function ($query){
            $query->where('email', 'like', '%'.$this->search.'%');
        })->latest()->paginate(50);
    }


    public function createSubscriber()
    {
        $validated = $this->validate([
            'email' => "required|email",
        ]);

        Subscriber::create([
            'email' => $this->email,
        ]);

        $this->reset();

        $this->dispatch('subscriber-created');
    }

    public function update($id)
    {
        $validated = $this->validate([
            'email' => "required|email",
        ]);

        $subscriber = Subscriber::findOrFail($id);

        $subscriber->update([
            'email' => $this->email,
        ]);

        $this->dispatch('subscriber-updated');

    }

    public function edit($id)
    {
        $subscriber = Subscriber::findOrFail($id);

        $this->id = $subscriber->id;
        $this->email = $subscriber->email;

    }


    public function deleteSubscriber($id)
    {
        $category = Subscriber::findOrFail($id);
        $category->delete();
        $this->dispatch('subscriber-deleted', $id);
    }

}; ?>

<div>
    {{-- Create category modal --}}
    <flux:modal name="create-subscriber" class="md:w-96">
        <form wire:submit.ignore="createSubscriber">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">Add New Subsciber</flux:heading>
                    <flux:text class="mt-2">Create a new subscriber entry</flux:text>
                </div>
    
                <flux:input wire:model="email" label="Email" placeholder="Subscriber email" />
    
                <div class="flex">
                    <flux:spacer />
     
                    <div class="flex items-center gap-4">
                        
            
                        <x-action-message class="me-3" on="subscriber-created">
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
    <flux:modal name="update-subscriber" class="md:w-96">
        <form wire:submit.ignore="update({{ $id }})">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">Update Category</flux:heading>
                    <flux:text class="mt-2">{{ $this->email }}</flux:text>
                </div>
    
                <flux:input wire:model="email" label="Email" placeholder="Subscriber email..." />
    
                <div class="flex">
                    <flux:spacer />
     
                    <div class="flex items-center gap-4">
                        
            
                        <x-action-message class="me-3" on="subscriber-updated">
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
                <flux:heading size="xl" level="1">{{ __('Subscribers') }}</flux:heading>
                <flux:breadcrumbs class="mb-4 mt-2">
                    <flux:breadcrumbs.item href="{{ route('dashboard') }}">Home</flux:breadcrumbs.item>
                    <flux:breadcrumbs.item >Subscribers</flux:breadcrumbs.item>
                </flux:breadcrumbs>
            </div>
        </div>
        <flux:separator variant="subtle" />
    </div>
    <div>

        <div class="flex justify-between items-center mb-5">
            
            @can("category-create")
                <flux:modal.trigger name="create-subscriber">
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
                        <td class="px-5 py-3 font-bold text-sm">Email</td>
                        <td class="px-5 py-3 font-bold text-sm">Created</td>
                        <td class="px-5 py-3 font-bold text-sm">Updated</td>

                            @canany(['category-edit', 'category-delete'])

                                <td class="px-5 py-3 font-bold text-sm">Actions</td>
                            @endcanany
                        
                    </tr>
                </th>
                </thead>
                <tbody>

                @foreach ($this->subscribers as $subscriber)
                
                    <tr class="border-b border-gray-300 hover:bg-gray-100">
                        <td class="px-5 py-2 text-sm">{{ $subscriber->email }}</td>
                        <td class="px-5 py-2 text-sm">{{ $subscriber->created_at->format('M d, Y H:i') }}</td>
                        <td class="px-5 py-2 text-sm">{{ $subscriber->updated_at->format('M d, Y H:i') }}</td>
                        
                            
                            @canany(['category-edit', 'category-delete'])
                                <td class="px-5 py-2 text-sm flex gap-2 place-content-center">
                                    
                                        @can("category-edit")
                                            <flux:modal.trigger wire:click="edit({{ $subscriber->id }})" name="update-subscriber">
                                                <flux:icon.pencil-square class="size-5 cursor-pointer" color="green" />
                                            </flux:modal.trigger>
                                        @endcan

                                        @can("category-delete")
                                            <flux:icon.trash class="size-5 cursor-pointer" color="red" wire:click="deleteSubscriber({{ $subscriber->id }})" wire:confirm="Are you sure you want to delete?" />
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

            {{ $this->subscribers->links() }}

        </div>

    </div>
</div>
