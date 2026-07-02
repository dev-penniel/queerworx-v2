<?php

use App\Models\AdminDocument;
use Illuminate\Support\Facades\Storage;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

new class extends Component {
    use WithFileUploads;
    use WithPagination;

    public $title = '';
    public $type = 'policy';
    public $description = '';
    public $published_at = '';
    public $file;
    public $search = '';
    public $editingId = null;

    public function getDocumentsProperty()
    {
        return AdminDocument::when($this->search, function ($query) {
            $query->where('title', 'like', '%'.$this->search.'%')
                ->orWhere('type', 'like', '%'.$this->search.'%');
        })->latest()->paginate(10);
    }

    public function edit($id): void
    {
        $document = AdminDocument::findOrFail($id);

        $this->editingId = $document->id;
        $this->title = $document->title;
        $this->type = $document->type;
        $this->description = $document->description;
        $this->published_at = optional($document->published_at)->format('Y-m-d');
        $this->file = null;
    }

    public function save(): void
    {
        $rules = [
            'title' => 'required|string|max:255',
            'type' => 'required|in:policy,financial',
            'description' => 'nullable|string',
            'published_at' => 'nullable|date',
        ];

        $rules['file'] = $this->editingId ? 'nullable|file|mimes:pdf|max:10240' : 'required|file|mimes:pdf|max:10240';

        $validated = $this->validate($rules);
        $document = $this->editingId ? AdminDocument::findOrFail($this->editingId) : new AdminDocument();

        if ($this->file) {
            if ($document->file_path) {
                Storage::disk('public')->delete($document->file_path);
            }

            $validated['file_path'] = $this->file->store('about-documents', 'public');
        }

        $document->fill($validated);
        $document->save();

        $this->resetForm();
        $this->dispatch('document-saved');
    }

    public function delete($id): void
    {
        $document = AdminDocument::findOrFail($id);

        Storage::disk('public')->delete($document->file_path);
        $document->delete();

        $this->dispatch('document-deleted');
    }

    public function resetForm(): void
    {
        $this->reset(['title', 'description', 'published_at', 'file', 'editingId']);
        $this->type = 'policy';
    }
}; ?>

<div>
    <div class="relative mb-6 w-full">
        <div class="flex justify-between items-center">
            <div>
                <flux:heading size="xl" level="1">{{ __('Policies & Financials') }}</flux:heading>
                <flux:breadcrumbs class="mb-4 mt-2">
                    <flux:breadcrumbs.item href="{{ route('dashboard') }}">Home</flux:breadcrumbs.item>
                    <flux:breadcrumbs.item>Policies & Financials</flux:breadcrumbs.item>
                </flux:breadcrumbs>
            </div>
        </div>
        <flux:separator variant="subtle" />
    </div>

    <div class="grid gap-6 lg:grid-cols-[360px_1fr]">
        <form wire:submit="save" class="rounded-lg border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            <div class="mb-5">
                <flux:heading size="lg">{{ $editingId ? 'Edit document' : 'Upload document' }}</flux:heading>
                <flux:text class="mt-1">PDFs appear on the About Us page.</flux:text>
            </div>

            <div class="space-y-4">
                <flux:input wire:model="title" label="Title" placeholder="Board policy, annual statement..." />

                <flux:select wire:model="type" label="Type">
                    <flux:select.option value="policy">Policy</flux:select.option>
                    <flux:select.option value="financial">Financial</flux:select.option>
                </flux:select>

                <flux:textarea wire:model="description" label="Description" rows="4" />
                <flux:input wire:model="published_at" type="date" label="Published date" />
                <flux:input wire:model="file" type="file" label="PDF file" accept="application/pdf" />

                <div class="flex items-center gap-3">
                    <flux:button type="submit" variant="primary" class="cursor-pointer">{{ $editingId ? 'Update' : 'Save' }}</flux:button>
                    @if ($editingId)
                        <flux:button type="button" wire:click="resetForm" variant="ghost" class="cursor-pointer">Cancel</flux:button>
                    @endif
                    <x-action-message on="document-saved">{{ __('Saved.') }}</x-action-message>
                </div>
            </div>
        </form>

        <div>
            <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex gap-2">
                    <span class="rounded-full bg-[#E61E5C] px-3 py-1 text-xs font-semibold text-white">Policies</span>
                    <span class="rounded-full bg-[#149CB9] px-3 py-1 text-xs font-semibold text-white">Financials</span>
                </div>

                <div class="w-full sm:w-[240px]">
                    <flux:input wire:model.live="search" placeholder="Search documents" />
                </div>
            </div>

            <div class="overflow-x-auto rounded-lg border border-zinc-200 dark:border-zinc-700">
                <table class="w-full table-auto">
                    <thead>
                        <tr class="bg-zinc-100 dark:bg-zinc-800">
                            <td class="px-5 py-3 text-sm font-bold">Title</td>
                            <td class="px-5 py-3 text-sm font-bold">Type</td>
                            <td class="px-5 py-3 text-sm font-bold">Published</td>
                            <td class="px-5 py-3 text-sm font-bold">File</td>
                            <td class="px-5 py-3 text-sm font-bold">Actions</td>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($this->documents as $document)
                            <tr class="border-t border-zinc-200 hover:bg-zinc-50 dark:border-zinc-700 dark:hover:bg-zinc-800">
                                <td class="px-5 py-3 text-sm">{{ $document->title }}</td>
                                <td class="px-5 py-3 text-sm capitalize">{{ $document->type }}</td>
                                <td class="px-5 py-3 text-sm">{{ optional($document->published_at)->format('M d, Y') ?? 'Draft' }}</td>
                                <td class="px-5 py-3 text-sm">
                                    <a class="text-[#149CB9] hover:underline" href="{{ Storage::url($document->file_path) }}" target="_blank">Open PDF</a>
                                </td>
                                <td class="px-5 py-3 text-sm">
                                    <div class="flex gap-3">
                                        <button type="button" wire:click="edit({{ $document->id }})" class="text-[#14A84D]"><flux:icon.pencil-square class="size-5" /></button>
                                        <button type="button" wire:click="delete({{ $document->id }})" wire:confirm="Delete this document?" class="text-[#E61E5C]"><flux:icon.trash class="size-5" /></button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-8 text-center text-sm text-zinc-500">No documents uploaded yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-5">{{ $this->documents->links() }}</div>
        </div>
    </div>
</div>
