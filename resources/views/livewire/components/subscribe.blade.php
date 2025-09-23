<?php

use Livewire\Volt\Component;
use App\Models\Subscriber;

new class extends Component {

    public $email;
    
    public function subscribe()
    {
        $validated = $this->validate([
            'email' => 'required|email|unique:subscribers,email',
        ]);

        Subscriber::create($validated);

        $this->reset();

        session()->flash('success', 'Thank you for subscribing to our newsletter.');

    }

}; ?>

<section class="py-16 bg-gray-900">
    <div class="container mx-auto px-4 max-w-5xl">
        <div class="gradient-bg rounded-3xl p-10 text-center">
            <h2 class="text-3xl font-bold text-white">Stay Connected</h2>
            <p class="text-purple-100 mt-2 max-w-2xl mx-auto">
                Subscribe to our newsletter for updates on events, resources, and community news.
            </p>
            
            <div class="mt-8 max-w-md mx-auto">
                <form wire:submit.prevent="subscribe" class=" gap-4">
                    
                    <div class="flex flex-col sm:flex-row gap-4">
                        <input 
                            wire:model="email"
                            type="email" 
                            placeholder="Your email address" 
                            class="flex-grow bg-white/10 border border-white/20 rounded-full px-6 py-3 text-white placeholder-purple-200 focus:outline-none focus:ring-2 focus:ring-white"
                        >

                        <button 
                            wire:loading.remove
                            class="bg-white text-purple-600 hover:bg-gray-100 font-medium rounded-full px-6 py-3 transition cursor-pointer"
                        >
                            Subscribe
                        </button>
                        <button 
                            wire:loading 
                            class="bg-white text-purple-600 hover:bg-gray-100 font-medium rounded-full px-6 py-3 transition"
                        >
                            Loading...
                        </button>
                    </div>

                    @if($errors->has('email'))
                        <div 
                            x-data="{ show: true }" 
                            x-init="setTimeout(() => show = false, 4000)" 
                            x-show="show" 
                            x-transition:enter="transition ease-out duration-500" 
                            x-transition:enter-start="opacity-0 -translate-y-2" 
                            x-transition:enter-end="opacity-100 translate-y-0" 
                            x-transition:leave="transition ease-in duration-500" 
                            x-transition:leave-start="opacity-100 translate-y-0" 
                            x-transition:leave-end="opacity-0 -translate-y-2"
                            wire:loading.remove 
                            class="mt-4"
                        >
                            <div class="bg-red-500/20 border border-red-500/40 text-red-200 px-4 py-3 rounded-xl text-sm text-center shadow-md">
                                <i class="fas fa-exclamation-circle mr-2"></i> 
                                {{ $errors->first('email') }}
                            </div>
                        </div>
                    @endif

                    @if (session()->has('success'))
                        <div 
                            x-data="{ show: true }" 
                            x-init="setTimeout(() => show = false, 4000)" 
                            x-show="show" 
                            x-transition:enter="transition ease-out duration-500" 
                            x-transition:enter-start="opacity-0 -translate-y-2" 
                            x-transition:enter-end="opacity-100 translate-y-0" 
                            x-transition:leave="transition ease-in duration-500" 
                            x-transition:leave-start="opacity-100 translate-y-0" 
                            x-transition:leave-end="opacity-0 -translate-y-2"
                            wire:loading.remove 
                            class="mt-4"
                        >
                            <div class="bg-green-500/20 border border-green-500/40 text-green-200 px-4 py-3 rounded-xl text-sm text-center shadow-md">
                                <i class="fas fa-check-circle mr-2"></i> 
                                {{ session('success') }}
                            </div>
                        </div>
                    @endif

                </form>
            </div>
            
            <p class="text-purple-200 text-sm mt-6">
                We respect your privacy. You can unsubscribe at any time.
            </p>
        </div>
    </div>
</section>
