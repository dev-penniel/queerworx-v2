<?php

use Livewire\Volt\Component;
use App\Models\Advert;

new class extends Component {
    public string $position;
    public $ads = [];

    public function mount(string $position)
    {
        // Load 3 ads for the given position
        $this->ads = Advert::where('position', $position)
            ->latest()
            ->take(3)
            ->get();
    }
}; ?>

<div class="py-8 mb-12">
    <div class="container mx-auto px-4 max-w-5xl">
        <div class="bg-gray-700/50 rounded-2xl p-6 text-center border border-gray-600">
            <p class="text-gray-400 text-sm mb-6">Advertisement</p>

            @if($ads->count())
                <div x-data="{ active: 0 }" 
                     x-init="setInterval(() => active = (active + 1) % {{ $ads->count() }}, 6000)" 
                     class="relative h-32 overflow-hidden rounded-xl">

                    @foreach($ads as $index => $ad)
                        <a href="{{ $ad->url }}" target="_blank"
                           class="absolute inset-0 transition-all duration-1000 ease-in-out"
                           :class="{ 'opacity-100': active === {{ $index }}, 'opacity-0': active !== {{ $index }} }">
                            <img src="{{ $ad->thumbnail }}" alt="{{ $ad->title }}"
                                 class="w-full h-full object-cover rounded-xl" />
                        </a>
                    @endforeach
                </div>
            @else
                <div class="flex justify-center items-center h-32 bg-gray-900/50 rounded-xl">
                    <p class="text-gray-500">Ad Space - 728x90</p>
                </div>
            @endif
        </div>
    </div>
</div>

