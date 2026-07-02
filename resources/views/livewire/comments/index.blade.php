<?php

use App\Models\ArticleComment;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public $status = 'pending';

    #[On('comment-updated')]
    public function getCommentsProperty()
    {
        return ArticleComment::with('article')
            ->when($this->status, fn ($query) => $query->where('status', $this->status))
            ->latest()
            ->paginate(30);
    }

    public function approve($id)
    {
        ArticleComment::findOrFail($id)->update([
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        $this->dispatch('comment-updated');
    }

    public function reject($id)
    {
        ArticleComment::findOrFail($id)->update([
            'status' => 'rejected',
            'approved_at' => null,
        ]);

        $this->dispatch('comment-updated');
    }

    public function deleteComment($id)
    {
        ArticleComment::findOrFail($id)->delete();
        $this->dispatch('comment-updated');
    }
}; ?>

<div>
    <div class="relative mb-6 w-full">
        <div class="flex justify-between items-center">
            <div>
                <flux:heading size="xl" level="1">{{ __('Comments') }}</flux:heading>
                <flux:breadcrumbs class="mb-4 mt-2">
                    <flux:breadcrumbs.item href="{{ route('dashboard') }}">Home</flux:breadcrumbs.item>
                    <flux:breadcrumbs.item>Comments</flux:breadcrumbs.item>
                </flux:breadcrumbs>
            </div>
        </div>
        <flux:separator variant="subtle" />
    </div>

    <div class="mb-5 w-56">
        <flux:select wire:model.live="status" :label="__('Status')">
            <flux:select.option value="pending">Pending</flux:select.option>
            <flux:select.option value="approved">Approved</flux:select.option>
            <flux:select.option value="rejected">Rejected</flux:select.option>
        </flux:select>
    </div>

    <div class="overflow-x-auto">
        <table class="table-auto w-full">
            <thead>
                <tr class="bg-gray-100">
                    <td class="px-5 py-3 font-bold text-sm">Article</td>
                    <td class="px-5 py-3 font-bold text-sm">Name</td>
                    <td class="px-5 py-3 font-bold text-sm">Comment</td>
                    <td class="px-5 py-3 font-bold text-sm">Status</td>
                    <td class="px-5 py-3 font-bold text-sm">Submitted</td>
                    <td class="px-5 py-3 font-bold text-sm">Actions</td>
                </tr>
            </thead>
            <tbody>
                @forelse ($this->comments as $comment)
                    <tr class="border-b border-gray-300 hover:bg-gray-100">
                        <td class="px-5 py-3 text-sm">
                            @if ($comment->article)
                                <a class="font-semibold hover:underline" target="_blank" href="{{ route('article', $comment->article->slug) }}">
                                    {{ $comment->article->title }}
                                </a>
                            @else
                                Deleted article
                            @endif
                        </td>
                        <td class="px-5 py-3 text-sm">
                            <span class="font-semibold">{{ $comment->name }}</span>
                            @if ($comment->email)
                                <span class="block text-xs text-gray-500">{{ $comment->email }}</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-sm max-w-lg">{{ $comment->body }}</td>
                        <td class="px-5 py-3 text-sm">{{ $comment->status }}</td>
                        <td class="px-5 py-3 text-sm whitespace-nowrap">{{ $comment->created_at->format('M d, Y H:i') }}</td>
                        <td class="px-5 py-3 text-sm">
                            <div class="flex gap-3">
                                <button wire:click="approve({{ $comment->id }})" class="text-green-600 font-semibold">Approve</button>
                                <button wire:click="reject({{ $comment->id }})" class="text-orange-600 font-semibold">Reject</button>
                                <button wire:click="deleteComment({{ $comment->id }})" wire:confirm="Delete this comment?" class="text-red-600 font-semibold">Delete</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-8 text-center text-gray-500">No comments found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-5">
        {{ $this->comments->links() }}
    </div>
</div>
