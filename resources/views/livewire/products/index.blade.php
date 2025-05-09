<?php
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use App\Models\Product;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

new class extends Component {

    use WithPagination;

    public $search = '';

    public function getProductsProperty()
    {
        return Product::when($this->search, function ($query) {
            $query->where('title', 'like', '%'.$this->search.'%')
                  ->orwhere('created_at', 'like', '%'.$this->search.'%')
                  ->orWhere('body', 'like', '%'.$this->search.'%');
        })->latest()->paginate(5);
    }

    public function deleteProduct($id)
    {
        $product = Product::find($id);

        $product->delete();

        $this->dispatch('product-deleted');

    }


}; ?>

<div>
    <div class="relative mb-6 w-full">
        <div class="flex justify-between items-center">
            <div>
                <flux:heading size="xl" level="1">{{ __('Products') }}</flux:heading>
                <flux:breadcrumbs class="mb-4 mt-2">
                    <flux:breadcrumbs.item href="{{ route('dashboard') }}">Home</flux:breadcrumbs.item>
                    <flux:breadcrumbs.item >Products</flux:breadcrumbs.item>
                </flux:breadcrumbs>
            </div>
        </div>
        <flux:separator variant="subtle" />
    </div>
    <div>

        <div class="flex justify-between items-center mb-5">
            
            @can('product-create')
                <a wire:navigate href="{{ route('products.create') }}"><flux:button size="sm" variant="primary" class="btn-sm"> <flux:icon.plus class="size-5" /> Add New</flux:button></a>
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

        <table class="table-auto w-full">
            <thead>
                <th>
                    <tr class="bg-gray-100">
                        <td class="px-5 py-3 font-bold text-sm">Title</td>
                        <td class="px-5 py-3 font-bold text-sm">Body</td>
                        <td class="px-5 py-3 font-bold text-sm">Created</td>
                        <td class="px-5 py-3 font-bold text-sm">Updated</td>

                        @if (auth()->user()->can('product-edit') || auth()->user()->can('product-delete') )

                            <td class="px-5 py-3 font-bold text-sm">Actions</td>

                        @endif
                        
                    </tr>
                </th>
            </thead>
            <tbody>

                @foreach ($this->products as $product)
                
                    <tr class="border-b border-gray-300 hover:bg-gray-100">
                        <td class="px-5 py-2 text-sm">{{ $product->title }}</td>
                        <td class="px-5 py-2 text-sm">{{ $product->body }}</td>
                        <td class="px-5 py-2 text-sm">{{ $product->created_at->format('M d, Y H:i') }}</td>
                        <td class="px-5 py-2 text-sm">{{ $product->updated_at->format('M d, Y H:i') }}</td>

                        @if (auth()->user()->can('product-edit') || auth()->user()->can('product-delete'))
                            
                            <td class="px-5 py-2 text-sm flex gap-2 place-content-center">
                                
                                @can('product-edit')
                                    <a wire:navigate href="{{ route('products.edit', $product->id) }}"><flux:icon.pencil-square class="size-5" color="green" /></a>
                                @endcan
                                
                                @can('product-delete')
                                    <flux:icon.trash class="size-5 cursor-pointer" color="red" wire:click="deleteProduct({{ $product->id }})" wire:confirm="Are you sure you want to delete?" />
                                    {{-- <flux:icon.trash class="size-5 cursor-pointer" color="red" wire:click="$js.showAlert({{ $product->id }})" /> --}}
                                @endcan

                            </td>

                        @endif

                        
                    </tr>

                @endforeach

            </tbody>
        </table>

        <div class="mt-5">

            {{ $this->products->links() }}

        </div>

    </div>
</div>

{{-- @script
<script>
    $js('showAlert', (id) => {

        confirm('Are you sure you want to delete?');

    })
</script>
@endscript --}}


