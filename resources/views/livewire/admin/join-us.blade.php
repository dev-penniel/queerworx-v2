<?php

use App\Models\JoinPageSetting;
use Illuminate\Support\Facades\Storage;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;

    public $heroImage;

    public function getSettingsProperty()
    {
        return JoinPageSetting::firstOrCreate([]);
    }

    public function saveImage(): void
    {
        $validated = $this->validate([
            'heroImage' => 'required|image|max:4096',
        ]);

        $settings = $this->settings;

        if ($settings->hero_image_path) {
            Storage::disk('public')->delete($settings->hero_image_path);
        }

        $settings->update([
            'hero_image_path' => $validated['heroImage']->store('join-us', 'public'),
        ]);

        $this->reset('heroImage');
        $this->dispatch('join-image-saved');
    }

    public function removeImage(): void
    {
        $settings = $this->settings;

        if ($settings->hero_image_path) {
            Storage::disk('public')->delete($settings->hero_image_path);
        }

        $settings->update(['hero_image_path' => null]);
        $this->dispatch('join-image-removed');
    }
}; ?>

<div class="text-white">
    <style>
        [data-flux-main] input,
        [data-flux-main] textarea,
        [data-flux-main] select {
            background-color: #0b0d1d !important;
            color: #ffffff !important;
        }
    </style>
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('Join Us Page') }}</flux:heading>
        <flux:breadcrumbs class="mb-4 mt-2">
            <flux:breadcrumbs.item href="{{ route('dashboard') }}">Home</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>Join Us Page</flux:breadcrumbs.item>
        </flux:breadcrumbs>
        <flux:separator variant="subtle" />
    </div>

    <div class="grid gap-6 lg:grid-cols-[360px_1fr]">
        <form wire:submit="saveImage" class="rounded-lg border border-white/10 bg-[#111429] p-5 text-white shadow-sm">
            <flux:heading size="lg">Hero image</flux:heading>
            <flux:text class="mt-1">Upload an optional image for the public Join Us page.</flux:text>

            <div class="mt-5 space-y-4">
                <flux:input wire:model="heroImage" type="file" label="Join Us image" accept="image/*" />

                <div class="flex items-center gap-3">
                    <flux:button type="submit" variant="primary" class="cursor-pointer">Save Image</flux:button>
                    <x-action-message on="join-image-saved">{{ __('Saved.') }}</x-action-message>
                </div>
            </div>
        </form>

        <div class="rounded-lg border border-white/10 bg-[#111429] p-5 text-white">
            <flux:heading size="lg">Current image</flux:heading>

            @if ($this->settings->hero_image_path)
                <img src="{{ Storage::url($this->settings->hero_image_path) }}" alt="Join Us page image" class="mt-4 max-h-80 rounded-lg object-cover">
                <flux:button type="button" wire:click="removeImage" wire:confirm="Remove this Join Us image?" variant="danger" class="mt-4 cursor-pointer">Remove Image</flux:button>
            @else
                <p class="mt-4 text-sm text-white/70">No image uploaded. The public Join Us page will show text-only content.</p>
            @endif
        </div>
    </div>
</div>
