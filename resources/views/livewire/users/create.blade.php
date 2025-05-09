<?php

use App\Models\User;
use Livewire\Volt\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Role;
use Livewire\Attributes\On;

new class extends Component {

    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public $allRoles, $selectedRole;

    #[On('user-created')]
    public function mount(): Void
    {
        $this->allRoles = Role::latest()->get();
    }

    public function createUser(): void
    {

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        // Assign user role
        $user->syncRoles($this->selectedRole);

        $this->reset();

        $this->dispatch('user-created');

    }


    // public function ddie(){
    //     dd('Hello');
    // }

}; ?>

<div>
    <div>
        <div class="relative mb-6 w-full">
            <div class="flex justify-between items-center">
                <div>
                    <div class="flex gap-2 items-center">
                        <a wire:navigate href="{{ route('users.index') }}"><flux:icon.arrow-left-circle /></a>
                        <flux:heading size="xl" level="1">{{ __('Add User') }}</flux:heading>
                    </div>
                    <flux:breadcrumbs class="mb-4 mt-2">
                        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Home</flux:breadcrumbs.item>
                        <flux:breadcrumbs.item href="{{ route('dashboard') }}">users</flux:breadcrumbs.item>
                        <flux:breadcrumbs.item >Create</flux:breadcrumbs.item>
                    </flux:breadcrumbs>
                </div>
            </div>
            <flux:separator variant="subtle" />
        </div>
        <form wire:submit.prevent="createUser" >
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
                    wire:model="email"
                    :label="__('User Email')"
                    type="email"
                    required
                    placeholder="User email"
                    autocomplete="user-email"
                />
                <select class="form-select" wire:model="selectedRole" >
                    <option name="role" >Select Role</option>
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
                    required
                    placeholder="Password"
                    autocomplete="password"
                />
                <flux:input
                    wire:model="password_confirmation"
                    :label="__('Confirm Password')"
                    type="password"
                    required
                    placeholder="Password"
                    autocomplete="confirm-password"
                />
            </div>
            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <flux:button variant="primary" type="submit" class="w-full">{{ __('Save') }}</flux:button>
                </div>
    
                <x-action-message class="me-3" on="user-created">
                    {{ __('Saved.') }}
                </x-action-message>
            </div>
        </form>
    </div>