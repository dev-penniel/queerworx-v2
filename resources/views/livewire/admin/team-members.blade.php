<?php

use App\Models\TeamMember;
use Illuminate\Support\Facades\Storage;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;

    public $memberId = null;
    public $name = '';
    public $role = '';
    public $sortOrder = 0;
    public $isActive = true;
    public $photo;

    public function getMembersProperty()
    {
        return TeamMember::orderBy('sort_order')->latest()->get();
    }

    public function saveMember(): void
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'sortOrder' => 'required|integer|min:0',
            'isActive' => 'boolean',
            'photo' => 'nullable|image|max:4096',
        ]);

        $member = $this->memberId ? TeamMember::findOrFail($this->memberId) : new TeamMember();

        if ($this->photo) {
            if ($member->photo_path) {
                Storage::disk('public')->delete($member->photo_path);
            }

            $member->photo_path = $this->photo->store('team-members', 'public');
        }

        $member->fill([
            'name' => $validated['name'],
            'role' => $validated['role'],
            'sort_order' => $validated['sortOrder'],
            'is_active' => $validated['isActive'],
        ]);
        $member->save();

        $this->resetForm();
        $this->dispatch('team-member-saved');
    }

    public function editMember($id): void
    {
        $member = TeamMember::findOrFail($id);

        $this->memberId = $member->id;
        $this->name = $member->name;
        $this->role = $member->role;
        $this->sortOrder = $member->sort_order;
        $this->isActive = $member->is_active;
        $this->photo = null;
    }

    public function deleteMember($id): void
    {
        $member = TeamMember::findOrFail($id);

        if ($member->photo_path) {
            Storage::disk('public')->delete($member->photo_path);
        }

        $member->delete();
        $this->dispatch('team-member-deleted');
    }

    public function resetForm(): void
    {
        $this->reset(['memberId', 'name', 'role', 'sortOrder', 'isActive', 'photo']);
        $this->isActive = true;
        $this->sortOrder = 0;
    }
}; ?>

<div>
    <div class="relative mb-6 w-full">
        <div>
            <flux:heading size="xl" level="1">{{ __('Team Members') }}</flux:heading>
            <flux:breadcrumbs class="mb-4 mt-2">
                <flux:breadcrumbs.item href="{{ route('dashboard') }}">Home</flux:breadcrumbs.item>
                <flux:breadcrumbs.item>Team Members</flux:breadcrumbs.item>
            </flux:breadcrumbs>
        </div>
        <flux:separator variant="subtle" />
    </div>

    <div class="grid gap-6 xl:grid-cols-[360px_1fr]">
        <form wire:submit="saveMember" class="rounded-lg border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            <flux:heading size="lg">{{ $memberId ? 'Edit team member' : 'Add team member' }}</flux:heading>
            <flux:text class="mt-1">Add seven active members to fill the public 3-2-2 team layout.</flux:text>
            @if ($memberId)
                <div class="mt-4 rounded border border-[#14A84D]/30 bg-[#14A84D]/10 px-3 py-2 text-sm font-semibold text-[#0F7A38] dark:text-[#7BE49D]">
                    Editing: {{ $name }}
                </div>
            @endif

            <div class="mt-5 space-y-4">
                <flux:input wire:model="name" label="Full name" />
                <flux:input wire:model="role" label="Job title / role" />
                <flux:input wire:model="sortOrder" type="number" min="0" label="Display order" />
                <flux:input wire:model="photo" type="file" label="Profile picture" accept="image/*" />

                <label class="flex items-center gap-3 text-sm">
                    <input wire:model="isActive" type="checkbox" class="rounded border-zinc-300">
                    Active on public Team page
                </label>

                <div class="flex items-center gap-3">
                    <flux:button type="submit" variant="primary" class="cursor-pointer">Save Member</flux:button>
                    @if ($memberId)
                        <flux:button type="button" wire:click="resetForm" variant="ghost" class="cursor-pointer">Cancel</flux:button>
                    @endif
                    <x-action-message on="team-member-saved">{{ __('Saved.') }}</x-action-message>
                </div>
            </div>
        </form>

        <div class="overflow-x-auto rounded-lg border border-zinc-200 dark:border-zinc-700">
            <table class="w-full table-auto">
                <thead>
                    <tr class="bg-zinc-100 dark:bg-zinc-800">
                        <td class="px-5 py-3 text-sm font-bold">Photo</td>
                        <td class="px-5 py-3 text-sm font-bold">Name</td>
                        <td class="px-5 py-3 text-sm font-bold">Role</td>
                        <td class="px-5 py-3 text-sm font-bold">Order</td>
                        <td class="px-5 py-3 text-sm font-bold">Status</td>
                        <td class="px-5 py-3 text-sm font-bold">Actions</td>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($this->members as $member)
                        <tr @class([
                            'border-t border-zinc-200 hover:bg-zinc-50 dark:border-zinc-700 dark:hover:bg-zinc-800',
                            'bg-[#14A84D]/10 ring-1 ring-inset ring-[#14A84D]/40 dark:bg-[#14A84D]/15' => $memberId === $member->id,
                        ])>
                            <td class="px-5 py-3 text-sm">
                                @if ($member->photo_path)
                                    <img src="{{ Storage::url($member->photo_path) }}" alt="{{ $member->name }}" class="h-7 w-7 rounded-full object-cover">
                                @else
                                    <span class="flex h-7 w-7 items-center justify-center rounded-full bg-zinc-200 text-[9px] font-bold text-zinc-600">No img</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-sm font-semibold">{{ $member->name }}</td>
                            <td class="px-5 py-3 text-sm">{{ $member->role }}</td>
                            <td class="px-5 py-3 text-sm">{{ $member->sort_order }}</td>
                            <td class="px-5 py-3 text-sm">{{ $member->is_active ? 'Active' : 'Hidden' }}</td>
                            <td class="px-5 py-3 text-sm">
                                <div class="flex gap-3">
                                    <button
                                        type="button"
                                        wire:click="editMember({{ $member->id }})"
                                        title="Edit {{ $member->name }}"
                                        @class([
                                            'inline-flex items-center gap-1 rounded px-2 py-1 text-[#14A84D]',
                                            'bg-[#14A84D] font-semibold text-white' => $memberId === $member->id,
                                        ])
                                    >
                                        <flux:icon.pencil-square class="size-5" />
                                        @if ($memberId === $member->id)
                                            <span>Editing</span>
                                        @endif
                                    </button>
                                    <button type="button" wire:click="deleteMember({{ $member->id }})" wire:confirm="Delete this team member?" class="text-[#E61E5C]"><flux:icon.trash class="size-5" /></button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-8 text-center text-sm text-zinc-500">No team members yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
