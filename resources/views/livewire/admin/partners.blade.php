<?php

use App\Models\Partner;
use Illuminate\Support\Facades\Storage;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;

    public $partnerId = null;
    public $name = '';
    public $websiteUrl = '';
    public $sortOrder = 0;
    public $isActive = true;
    public $logo;

    public function getPartnersProperty()
    {
        return Partner::orderBy('sort_order')->latest()->get();
    }

    public function savePartner(): void
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'websiteUrl' => 'nullable|url|max:2048',
            'sortOrder' => 'required|integer|min:0',
            'isActive' => 'boolean',
            'logo' => 'nullable|image|max:4096',
        ]);

        $partner = $this->partnerId ? Partner::findOrFail($this->partnerId) : new Partner();

        if ($this->logo) {
            if ($partner->logo_path) {
                Storage::disk('public')->delete($partner->logo_path);
            }

            $partner->logo_path = $this->logo->store('partners', 'public');
        }

        $partner->fill([
            'name' => $validated['name'],
            'website_url' => $validated['websiteUrl'] ?: null,
            'sort_order' => $validated['sortOrder'],
            'is_active' => $validated['isActive'],
        ]);
        $partner->save();

        $this->resetForm();
        $this->dispatch('partner-saved');
    }

    public function editPartner($id): void
    {
        $partner = Partner::findOrFail($id);

        $this->partnerId = $partner->id;
        $this->name = $partner->name;
        $this->websiteUrl = $partner->website_url;
        $this->sortOrder = $partner->sort_order;
        $this->isActive = $partner->is_active;
        $this->logo = null;
    }

    public function deletePartner($id): void
    {
        $partner = Partner::findOrFail($id);

        if ($partner->logo_path) {
            Storage::disk('public')->delete($partner->logo_path);
        }

        $partner->delete();
        $this->dispatch('partner-deleted');
    }

    public function resetForm(): void
    {
        $this->reset(['partnerId', 'name', 'websiteUrl', 'sortOrder', 'isActive', 'logo']);
        $this->isActive = true;
        $this->sortOrder = 0;
    }
}; ?>

<div>
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">Partners</flux:heading>
        <flux:breadcrumbs class="mb-4 mt-2">
            <flux:breadcrumbs.item href="{{ route('dashboard') }}">Home</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>Partners</flux:breadcrumbs.item>
        </flux:breadcrumbs>
        <flux:separator variant="subtle" />
    </div>

    <div @class([
        'grid gap-6',
        'xl:grid-cols-[360px_1fr]' => auth()->user()->can('partners-create')
    ]) class=" ">

        @can('partners-create')
            <form wire:submit="savePartner" class="rounded-lg border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <flux:heading size="lg">{{ $partnerId ? 'Edit partner' : 'Add partner' }}</flux:heading>
                <flux:text class="mt-1">Add a clickable logo and website for the public Partners page.</flux:text>

                <div class="mt-5 space-y-4">
                    <flux:input wire:model="name" label="Partner name" />
                    <flux:input wire:model="websiteUrl" type="url" label="Website URL" placeholder="https://example.org" />
                    <flux:input wire:model="sortOrder" type="number" min="0" label="Display order" />
                    <flux:input wire:model="logo" type="file" label="Partner logo" accept="image/*" />

                    <label class="flex items-center gap-3 text-sm">
                        <input wire:model="isActive" type="checkbox" class="rounded border-zinc-300">
                        Active on public Partners page
                    </label>

                    <div class="flex items-center gap-3">
                        <flux:button type="submit" variant="primary" class="cursor-pointer">Save Partner</flux:button>
                        @if ($partnerId)
                            <flux:button type="button" wire:click="resetForm" variant="ghost" class="cursor-pointer">Cancel</flux:button>
                        @endif
                        <x-action-message on="partner-saved">Saved.</x-action-message>
                    </div>
                </div>
            </form>
        @endcan
        

        <div class="overflow-x-auto rounded-lg border border-zinc-200 dark:border-zinc-700">
            <table class="w-full table-auto">
                <thead>
                    <tr class="bg-zinc-100 dark:bg-zinc-800">
                        <td class="px-5 py-3 text-sm font-bold">Logo</td>
                        <td class="px-5 py-3 text-sm font-bold">Partner</td>
                        <td class="px-5 py-3 text-sm font-bold">Website</td>
                        <td class="px-5 py-3 text-sm font-bold">Order</td>
                        <td class="px-5 py-3 text-sm font-bold">Status</td>
                        <td class="px-5 py-3 text-sm font-bold">Actions</td>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($this->partners as $partner)
                        <tr class="border-t border-zinc-200 hover:bg-zinc-50 dark:border-zinc-700 dark:hover:bg-zinc-800">
                            <td class="px-5 py-3 text-sm">
                                @if ($partner->logo_path)
                                    <img src="{{ Storage::url($partner->logo_path) }}" alt="{{ $partner->name }}" class="h-9 w-14 rounded object-contain">
                                @else
                                    <span class="text-zinc-500">No logo</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-sm font-semibold">{{ $partner->name }}</td>
                            <td class="max-w-48 truncate px-5 py-3 text-sm">{{ $partner->website_url ?: '—' }}</td>
                            <td class="px-5 py-3 text-sm">{{ $partner->sort_order }}</td>
                            <td class="px-5 py-3 text-sm">{{ $partner->is_active ? 'Active' : 'Hidden' }}</td>
                            <td class="px-5 py-3 text-sm">
                                <div class="flex gap-3">
                                    @can('partners-edit')
                                        <button type="button" wire:click="editPartner({{ $partner->id }})" class="text-[#14A84D]" title="Edit {{ $partner->name }}"><flux:icon.pencil-square class="size-5" /></button>
                                    @endcan
                                    
                                    @can('partners-delete')
                                        <button type="button" wire:click="deletePartner({{ $partner->id }})" wire:confirm="Delete this partner?" class="text-[#E61E5C]" title="Delete {{ $partner->name }}"><flux:icon.trash class="size-5" /></button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-5 py-8 text-center text-sm text-zinc-500">No partners yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
