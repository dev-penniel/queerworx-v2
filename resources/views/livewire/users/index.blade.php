<?php

use Livewire\Attributes\On;
use Livewire\Volt\Component;
use App\Models\User;

new class extends Component {
    
    public $search = '';

    #[On('user-deleted')]
    public function getUsersProperty()
    {
        return User::when($this->search, function ($query){
            $query->where('name', 'like', '%'.$this->search.'%')
                  ->orWhere('email', 'like', '%'.$this->search.'%')
                  ->orWhere('created_at', 'like', '%'.$this->search.'%');
        })->latest()->paginate(10);
    }
    

    public function deleteUser($is): Void
    {
        $user = User::find($is);

        $user->delete();

        $this->dispatch('user-deleted');
    }


}; ?>


 
<div>
    <div class="relative mb-6 w-full">
        <div class="flex justify-between items-center">
            <div>
                <flux:heading size="xl" level="1">{{ __('Users') }}</flux:heading>
                <flux:breadcrumbs class="mb-4 mt-2">
                    <flux:breadcrumbs.item href="{{ route('dashboard') }}">Home</flux:breadcrumbs.item>
                    <flux:breadcrumbs.item >Users</flux:breadcrumbs.item>
                </flux:breadcrumbs>
            </div>
        </div>
        <flux:separator variant="subtle" />
    </div>
    <div>

        <div class="flex justify-between items-center mb-5">
            
            @can('user-create')
                <a wire:navigate href="{{ route('users.create') }}"><flux:button class="cursor-pointer">Add</flux:button></a>
                
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

        <table class="table-auto w-full">
            <thead>
                <th>
                    <tr class="bg-gray-100">
                        <td class="px-5 py-3 font-bold text-sm">Names</td>
                        <td class="px-5 py-3 font-bold text-sm">Email</td>
                        <td class="px-5 py-3 font-bold text-sm">Role</td>
                        <td class="px-5 py-3 font-bold text-sm">Created</td>

                        @canany(['user-edit', 'user-delete'])
                            <td class="px-5 py-3 font-bold text-sm">Actions</td>
                        @endcanany

                    </tr>
                </th>
            </thead>
            <tbody>

                @foreach ($this->users as $user)
                
                    <tr class="border-b border-gray-300 hover:bg-gray-100">
                        <td class="px-5 py-2 text-sm">{{ $user->name }}</td>
                        <td class="px-5 py-2 text-sm">{{ $user->email }}</td>
                        
                        @foreach ($user->getRoleNames() as $role)
                            <td class="px-5 py-2 text-sm">{{ $role }} </td>
                        @endforeach

                        <td class="px-5 py-2 text-sm">{{ $user->created_at->format('M d, Y H:i') }}</td>

                        @canany(['user-edit', 'user-delete'])

                            <td class="px-5 py-2 text-sm flex gap-2 place-content-center">
                                
                                @can('user-edit')
                                    <a wire:navigate href="{{ route('users.edit', $user->id) }}"><flux:icon.pencil-square class="size-5" color="green" /></a>
                                @endcan
                                
                                @can('user-delete')
                                    <flux:icon.trash class="size-5 cursor-pointer" color="red" wire:click="deleteUser({{ $user->id }})" wire:confirm="Are you sure you want to delete?" />
                                @endcan


                            </td>
                        @endcanany

                    </tr>

                @endforeach

            </tbody>
            {{-- {{ $contacts->links() }} --}}
        </table>

        <div class="mt-5">

            {{ $this->users->links() }}

        </div>

    </div>
</div>

