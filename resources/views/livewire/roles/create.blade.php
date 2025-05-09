<?php

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Livewire\Volt\Component;

new class extends Component {

    public $permissions, $name;
    public $selectedPermissions = [];

    public function mount(): Void
    {
        $this->permissions = Permission::all();
    }

    public function createRole(){

        $validated = $this->validate([
            'name' => ['string', 'required'],
        ]);


        $role = Role::create($validated);

        $role->syncPermissions($this->selectedPermissions);

        $this->dispatch('role-created');

    }

}; ?>

<div>
    <div>
        <div class="relative mb-6 w-full">
            <div class="flex justify-between items-center">
                <div>
                    <div class="flex gap-2 items-center">
                        <a wire:navigate href="{{ route('roles.index') }}"><flux:icon.arrow-left-circle /></a>
                        <flux:heading size="xl" level="1">{{ __('Add Role') }}</flux:heading>
                    </div>
                    <flux:breadcrumbs class="mb-4 mt-2">
                        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Home</flux:breadcrumbs.item>
                        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Roles</flux:breadcrumbs.item>
                        <flux:breadcrumbs.item >Create</flux:breadcrumbs.item>
                    </flux:breadcrumbs>
                </div>
            </div>
            <flux:separator variant="subtle" />
        </div>
        <form wire:submit.prevent="createRole" >
            <div class="flex gap-5 mb-5">
                <flux:input
                    wire:model="name"
                    :label="__('Name')"
                    type="text"
                    required
                    placeholder="Name"
                    autocomplete="name"
                />


                
            </div>

            <div class="space-x-2">
                @foreach ($permissions as $permission )
                    <label for="" ><input wire:model="selectedPermissions" value="{{ $permission->name }}" type="checkbox"> {{ $permission->name }}</label>
                @endforeach
            </div>

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <flux:button variant="primary" type="submit" class="w-full">{{ __('Save') }}</flux:button>
                </div>
    
                <x-action-message class="me-3" on="role-created">
                    {{ __('Saved.') }}
                </x-action-message>
            </div>
        </form>
    </div>
