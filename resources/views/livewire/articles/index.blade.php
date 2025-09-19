<?php

use Livewire\Volt\Component;
use App\Models\Article;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

new class extends Component {

    use WithPagination;

    public $search;

    #[On('article-deleted')]
    public function getArticlesProperty()
    {
        return Article::when($this->search, function ($query){
            $query->where('title', 'like', '%'.$this->search.'%');
        })->latest()->paginate(50);
    }

    public function deleteArticle($id)
    {
        $article = Article::findOrFail($id);

        // Remove article category relationship from pivoit table
        $article->categories()->detach();

        // delete article
        $article->delete();

        $this->dispatch('article-deleted');
;
    }

}; ?>

<div>
    
    <div class="relative mb-6 w-full">
        <div class="flex justify-between items-center">
            <div>
                <flux:heading size="xl" level="1">{{ __('Articles') }}</flux:heading>
                <flux:breadcrumbs class="mb-4 mt-2">
                    <flux:breadcrumbs.item href="{{ route('dashboard') }}">Home</flux:breadcrumbs.item>
                    <flux:breadcrumbs.item >Articles</flux:breadcrumbs.item>
                </flux:breadcrumbs>
            </div>
        </div>
        <flux:separator variant="subtle" />
    </div>

    <div>

        <div class="flex justify-between items-center mb-5">

            @can("article-create")
                <a wire:navigate href="{{ route('articles.create') }}"><flux:button class="cursor-pointer">Add</flux:button></a>
            @endcan            

            <div class="w-[200px]">
                <flux:input
                    wire:model.live="search"
                    type="text"
                    required
                    placeholder="Search"
                    autocomplete="current-password"
                />
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="table-auto w-full">
                <thead>
                    <th>
                        <tr class="bg-gray-100">
                            <td class="px-5 py-3 font-bold text-sm">Thubmnail</td>
                            <td class="px-5 py-3 font-bold text-sm">Title</td>
                            <td class="px-5 py-3 font-bold text-sm">Slug</td>
                            <td class="px-5 py-3 font-bold text-sm">Status</td>
                            <td class="px-5 py-3 font-bold text-sm">Categories</td>
                            <td class="px-5 py-3 font-bold text-sm">Views</td>
                            <td class="px-5 py-3 font-bold text-sm">Claps</td>
                            <td class="px-5 py-3 font-bold text-sm">Published</td>
                            <td class="px-5 py-3 font-bold text-sm">Created</td>

                            @canany(['article-edit', 'article-delete'])
                                <td class="px-5 py-3 font-bold text-sm">Actions</td>
                            @endcanany

                        </tr>
                    </th>
                </thead>
                <tbody>
                    @foreach ($this->articles as $article)
                        <tr class="border-b border-gray-300 hover:bg-gray-100" >
                            <td class="px-5 py-2 text-sm whitespace-nowrap">
                            @if ($article->thumbnail)
                                <div class="mt-2">
                                    <img src="{{ Storage::url($article->thumbnail) }}" class="h-15 w-30 object-cover rounded-md">
                                </div>
                            @endif
                        </td>
                            <td class="px-5 py-2 text-sm whitespace-nowrap">{{ $article->title }}</td>
                            <td class="px-5 py-2 text-sm whitespace-nowrap">{{ $article->slug }}</td>
                            <td class="px-5 py-2 text-sm whitespace-nowrap">{{ $article->status }}</td>
                            <td class="px-5 py-2 text-sm whitespace-nowrap">
                                @foreach ($article->categories as $category)
                                    <flux:badge size="sm">{{ $category->name }}</flux:badge>
                                @endforeach
                            </td>
                            <td class="px-5 py-2 text-sm whitespace-nowrap">{{ $article->views }}</td>
                            <td class="px-5 py-2 text-sm whitespace-nowrap">{{ $article->claps }}</td>
                            <td class="px-5 py-2 text-sm whitespace-nowrap">{{ $article->created_at->format('M d, Y H:i') }}</td>
                            <td class="px-5 py-2 text-sm whitespace-nowrap">{{ $article->updated_at->format('M d, Y H:i') }}</td>

                            @canany(['article-edit', 'article-delete'])

                                <td class="px-5 py-2 text-sm flex gap-2 place-content-center">
                                        
                                    @can("article-edit")
                                        <a wire:navigate href="{{ route('articles.edit', $article->id) }}"><flux:icon.pencil-square class="size-5" color="green" /></a>
                                    @endcan

                                    @can("article-delete")
                                        <flux:icon.trash class="size-5 cursor-pointer" color="red" wire:click="deleteArticle({{ $article->id }})" wire:confirm="Are you sure you want to delete?" />
                                    @endcan
                                        
                                </td>
                                
                            @endcanany

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-5">

            {{-- {{ $this->products->links() }} --}}

        </div>

    </div>
</div>
