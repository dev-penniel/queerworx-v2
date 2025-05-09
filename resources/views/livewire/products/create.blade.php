<?php

use App\Models\Product;
use Livewire\Volt\Component;

new class extends Component {
    
    public $title, $body;

    public function createProduct()
    {

        $validated = $this->validate([
            'title' => ['required', 'string'],
            'body' => ['required', 'string'],
        ]);

        // dd($validated);

        Product::create($validated);

        $this->reset();

        $this->dispatch('product-created');

    }

}; ?>

<div>
    <div>
        <div class="relative mb-6 w-full">
            <div class="flex justify-between items-center">
                <div>
                    <div class="flex gap-2 items-center">
                        <a wire:navigate href="{{ route('products.index') }}"><flux:icon.arrow-left-circle /></a>
                        <flux:heading size="xl" level="1">{{ __('Add Product') }}</flux:heading>
                    </div>
                    <flux:breadcrumbs class="mb-4 mt-2">
                        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Home</flux:breadcrumbs.item>
                        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Products</flux:breadcrumbs.item>
                        <flux:breadcrumbs.item >Create</flux:breadcrumbs.item>
                    </flux:breadcrumbs>
                </div>
            </div>
            <flux:separator variant="subtle" />
        </div>
        <form wire:submit.prevent="createProduct" >
            <div class="flex gap-5 mb-5">
                <flux:input
                    wire:model="title"
                    :label="__('Title')"
                    type="text"
                    required
                    placeholder="Title"
                    autocomplete="title"
                />
                <flux:input
                    wire:model="body"
                    :label="__('body')"
                    type="body"
                    required
                    placeholder="Body"
                    autocomplete="body"
                />
            </div>
            
            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <flux:button variant="primary" type="submit" class="w-full">{{ __('Save') }}</flux:button>
                </div>
    
                <x-action-message class="me-3" on="product-created">
                    {{ __('Saved.') }}
                </x-action-message>
            </div>
        </form>
    </div>