<?php
use App\Models\Product;
use Livewire\Volt\Component;

new class extends Component {
    

    public $title, $body, $id;

    #[On('product-updated')]
    public function mount($id): void
    {

        $product = Product::find($id);

        $this->title = $product->title;
        $this->body = $product->body;
    }


    public function updateProduct($id)
    {
        $product = Product::find($id);
        
        $validated = $this->validate([
            'title' => ['required', 'string'],
            'body' => ['required', 'string'],
        ]);

        $product->update($validated);

        $this->dispatch('product-updated');


    }


}; ?>

<div>
    <div>
        <div class="relative mb-6 w-full">
            <div class="flex justify-between items-center">
                <div>
                    <div class="flex gap-2 items-center">
                        <a wire:navigate href="{{ route('products.index') }}"><flux:icon.arrow-left-circle /></a>
                        <flux:heading size="xl" level="1">{{ __('Edit Product - ') }} {{$title}}</flux:heading>
                    </div>
                    <flux:breadcrumbs class="mb-4 mt-2">
                        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Home</flux:breadcrumbs.item>
                        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Products</flux:breadcrumbs.item>
                        <flux:breadcrumbs.item >Edit</flux:breadcrumbs.item>
                        <flux:breadcrumbs.item >{{ $title }}</flux:breadcrumbs.item>
                    </flux:breadcrumbs>
                </div>
            </div>
            <flux:separator variant="subtle" />
        </div>
        <form wire:submit.prevent="updateProduct({{ $id }})" >
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
    
                <x-action-message class="me-3" on="product-updated">
                    {{ __('Saved.') }}
                </x-action-message>
            </div>
        </form>
    </div>
