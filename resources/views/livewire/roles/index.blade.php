<?php

use Livewire\Attributes\On;
use Spatie\Permission\Models\Role;
use Livewire\Volt\Component;

new class extends Component {

    public $roles;
    
    #[On('role-deleted')]
    public function mount(): Void
    {
        $this->roles = Role::latest()->get();
    }

    public function deleteRole($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        $this->dispatch('role-deleted');
    }

}; ?>

<div>
    <div class="relative mb-6 w-full">
        <div class="flex justify-between items-center">
            <div>
                <flux:heading size="xl" level="1">{{ __('Roles') }}</flux:heading>
                <flux:breadcrumbs class="mb-4 mt-2">
                    <flux:breadcrumbs.item href="{{ route('dashboard') }}">Home</flux:breadcrumbs.item>
                    <flux:breadcrumbs.item >Roles</flux:breadcrumbs.item>
                </flux:breadcrumbs>
            </div>
        </div>
        <flux:separator variant="subtle" />
    </div>
    <div>

        <div class="flex justify-between items-center mb-5">
            
            <a wire:navigate href="{{ route('roles.create') }}"><flux:button size="sm" variant="primary" class="btn-sm"> <flux:icon.plus class="size-5" /> Add New</flux:button></a>

            <disv class="w-[200px]">
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
                        <td class="px-5 py-3 font-bold text-sm">Role</td>
                        {{-- <td class="px-5 py-3 font-bold text-sm">Email</td>
                        <td class="px-5 py-3 font-bold text-sm">Created</td>
                        <td class="px-5 py-3 font-bold text-sm">Updated</td> --}}
                        <td class="px-5 py-3 font-bold text-sm">Actions</td>
                    </tr>
                </th>
            </thead>
            <tbody>

                @foreach ($roles as $role)
                
                    <tr class="border-b border-gray-300 hover:bg-gray-100">
                        <td class="px-5 py-2 text-sm">{{ $role->name }}

                            <div class="flex space-x-3 flex-wrap">
                                @foreach ($role->permissions as $permission)
                                    <flux:badge class="mt-2" size="sm">{{ $permission->name }}</flux:badge>
                                    {{-- <p class="text-sm text-slate-600">{{ $permission->name }}</p> --}}
                                @endforeach
                            </div>

                        </td>
                        {{-- <td class="px-5 py-2 text-sm">{{ $role->email }}</td>
                        <td class="px-5 py-2 text-sm">{{ $role->created_at }}</td>
                        <td class="px-5 py-2 text-sm">{{ $role->updated_at }}</td> --}}
                        <td class="px-5 py-2 text-sm flex gap-2 place-content-center">
                            
                            <a wire:navigate href="{{ route('roles.edit', $role->id) }}"><flux:icon.pencil-square class="size-5" color="green" /></a>
                            
                            <flux:icon.trash class="size-5 cursor-pointer" color="red" wire:click="deleteRole({{ $role->id }})" wire:confirm.prompt="Deleting Roles is not advised, are you sure you want to continue?\n\nType YES to confirm|YES"  />


                            </td>
                    </tr>

                @endforeach

            </tbody>
            {{-- {{ $contacts->links() }} --}}
        </table>

        <div class="mt-5">

            {{-- {{ $this->contacts->links() }} --}}

        </div>

    </div>
</div>


