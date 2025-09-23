<?php

use Livewire\Volt\Component;
use App\Models\Article;
use Livewire\Attributes\On;


new class extends Component {
    
    public $article, $id;

    #On['article-liked']
    public function mount()
    {
        $this->article = Article::findOrFail($this->id);

    }

    public function liked($id){

        $this->article->increment('claps');
        $this->dispatch('article-liked');
    }

}; ?>

{{-- <a class="cursor-pointer"  wire:click="liked({{ $id }})">
    <p class="flex items-center gap-1 hover:text-purple-400 transition">
        <i class="fas fa-hands-clapping"></i> {{ $article->claps }} claps
    </p>
</a> --}}

<div 
    x-data="{ active: false }" 
    class="relative inline-block select-none"
>
    <!-- Clap Button -->
    <button 
        class=" hover:text-purple-400 cursor-pointer"
        wire:click="liked({{ $id }})"
        @click="active = true; setTimeout(() => active = false, 800)"
        class="flex items-center gap-2 focus:outline-none"
    >
        <i 
            class="fas fa-hands-clapping transition-transform duration-300"
            :class="{ 'text-purple-400 scale-125': active }"
        ></i>

        <span 
            class="transition duration-300"
            :class="{ 'text-purple-400': active }"
        >
            {{ $article->claps }} claps
        </span>
    </button>

    <!-- Confetti Burst -->
    <template x-if="active">
        <div 
            class="absolute -top-6 left-1/2 transform -translate-x-1/2 flex space-x-1 opacity-0 animate-fade-up"
        >
            <span class="text-yellow-400">🎉</span>
            <span class="text-pink-400">👏</span>
            <span class="text-green-400">🌟</span>
        </div>
    </template>

    {{-- <!-- Loading feedback -->
    <div 
        wire:loading 
        wire:target="liked" 
        class="text-purple-400 text-xs mt-1 animate-pulse"
    >
        <i class="fas fa-circle-notch fa-spin mr-1"></i>clapping...
    </div> --}}

    <!-- Add this small animation helper -->
    <style>
        @keyframes fadeUp {
            0% { transform: translateY(20px); opacity: 0; }
            50% { opacity: 1; }
            100% { transform: translateY(-10px); opacity: 0; }
        }
        .animate-fade-up {
            animation: fadeUp 0.8s ease-out forwards;
        }
    </style>
</div>




