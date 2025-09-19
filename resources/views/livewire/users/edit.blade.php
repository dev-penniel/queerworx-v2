<?php

use App\Models\User;
use Livewire\Volt\Component;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\On;
use Spatie\Permission\Models\Role;

new class extends Component {

    public $user, $id, $name, $email, $password, $password_confirmation, $allRoles, $selectedRole;
    
    #[On('user-updated')]
    public function mount($id): Void
    {

        $this->user = User::find($id);
        $this->allRoles = Role::latest()->get();

        // Get current user role
        $this->selectedRole = $this->user->roles->pluck('name')->first();

        $this->name = $this->user->name;
        $this->email = $this->user->email;
        

    }

    public function updateUser($id)
    {

        $user = User::find($id);

        $validated = $this->validate([
            'name' => ['string', 'max:255'],
            'password' => ['string', 'confirmed', Rules\Password::defaults()],
        ]);

        if($validated['password']){
            $validated['password'] = hash::make($validated['password']);
        }

        $user->fill($validated);

        $user->save();

        // Assign user role
        $user->syncRoles($this->selectedRole);

        $this->dispatch('user-updated', $user->id);

    }


}; ?>


<div>
    <div>
        <div class="relative mb-6 w-full">
            <div class="flex justify-between items-center">
                <div>
                    <div class="flex gap-2 items-center">
                        <a wire:navigate href="{{ route('users.index') }}"><flux:icon.arrow-left-circle /></a>
                        <flux:heading size="xl" level="1">{{ __('Edit User - ') }}{{$name}}</flux:heading>
                    </div>
                    <flux:breadcrumbs class="mb-4 mt-2">
                        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Home</flux:breadcrumbs.item>
                        <flux:breadcrumbs.item href="{{ route('users.index') }}">users</flux:breadcrumbs.item>
                        <flux:breadcrumbs.item >Create</flux:breadcrumbs.item>
                    </flux:breadcrumbs>
                </div>
            </div>
            <flux:separator variant="subtle" />
        </div>
        <form wire:submit.prevent="updateUser({{ $id }})" >
            <div class="flex gap-5 mb-5">
                <flux:input
                    wire:model="name"
                    :label="__('Names')"
                    type="text"
                    required
                    placeholder="Names"
                    autocomplete="names"
                />
                <flux:input
                    disabled
                    wire:model="email"
                    :label="__('User Email')"
                    type="email"
                    required
                    placeholder="User email"
                    autocomplete="user-email"
                />

                <select class="form-select" wire:model="selectedRole" >
                    @forelse ($allRoles ?? [] as $role)
                        <option wire:key="{{ $role->id }}" value="{{ $role->name }}">{{ $role->name }}</option>
                    @empty
                        <option disabled>No roles found</option>
                    @endforelse
                </select>
            </div>
            <div class="flex gap-5 mb-5">
                <flux:input
                    wire:model="password"
                    :label="__('Password')"
                    type="password"
                    placeholder="Password"
                    autocomplete="password"
                />
                <flux:input
                    wire:model="password_confirmation"
                    :label="__('Confirm Password')"
                    type="password"
                    placeholder="Password"
                    autocomplete="confirm-password"
                />
            </div>
            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <flux:button variant="primary" type="submit" class="w-full">{{ __('Save') }}</flux:button>
                </div>
    
                <x-action-message class="me-3" on="user-updated">
                    {{ __('Saved.') }}
                </x-action-message>
            </div>
        </form>
    </div>
