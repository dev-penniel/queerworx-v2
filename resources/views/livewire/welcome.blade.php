
<?php

use Livewire\Volt\Component;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Support\Facades\Route;
use Livewire\Attributes\Layout;

new 
#[Layout('components.layouts.app.frontend')]
class extends Component {
    public $articles;
    public $categories = [];
    public $selectedCategory = null;
    public $search = '';

    public function mount()
    {
        $this->loadArticles();

        $this->categories = Category::latest()->get();
    }

    public function loadArticles()
    {
        $query = Article::where('status', 'published')
                    ->orderBy('published_date', 'desc');

        if ($this->selectedCategory) {
            $query->where('categories', $this->selectedCategory);
        }

        if ($this->search) {
            $query->where(function($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('body', 'like', '%' . $this->search . '%');
            });
        }

        $this->articles = $query->get();
    }

    public function updatedSelectedCategory()
    {
        $this->loadArticles();
    }

    public function updatedSearch()
    {
        $this->loadArticles();
    }

    public function clearFilters()
    {
        $this->selectedCategory = null;
        $this->search = '';
        $this->loadArticles();
    }
}; ?>


<div>
    <!-- Hero Section -->
<section class="relative py-16 md:py-24 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-gray-900 to-transparent z-0"></div>
        <div class="absolute right-0 top-0 bottom-0 w-full md:w-1/2 gradient-bg opacity-20 z-0"></div>
        
        <div class="container mx-auto px-4 max-w-7xl relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div>
                    <h1 class="text-4xl md:text-5xl font-bold leading-tight">
                        Building a <span class="text-purple-500">Stronger</span> LGBTQ+ Community in Lesotho
                    </h1>
                    <p class="text-lg text-gray-400 mt-4">
                        QueerWorx provides resources, support, and community for LGBTQ+ individuals in Lesotho. Join us in creating a more inclusive society.
                    </p>
                    <div class="flex flex-wrap gap-4 mt-8">
                        <button class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-full transition">
                            Get Involved
                        </button>
                        <button class="border border-purple-500 text-purple-300 hover:bg-purple-950/30 px-6 py-3 rounded-full transition">
                            Learn More
                        </button>
                    </div>
                </div>
                <div class="flex justify-center">
                    <div class="relative">
                        <div class="w-80 h-80 rounded-full gradient-bg opacity-70 blur-xl absolute -z-10 top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2"></div>
                        <div class="bg-gray-800/80 backdrop-blur-lg rounded-2xl p-6 border border-gray-700 shadow-xl">
                            <img src="https://placehold.co/400x300/8B5CF6/FFFFFF/png?text=Community+Image" alt="Community" class="rounded-xl w-full">
                            <div class="mt-4 text-center">
                                <p class="text-sm">Join our next community event on June 28th</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Ad Space -->
    <section class="py-8 bg-gray-800">
        <div class="container mx-auto px-4 max-w-7xl">
            <div class="bg-gray-700/50 rounded-2xl p-6 text-center border border-gray-600">
                <p class="text-gray-400 text-sm mb-2">Advertisement</p>
                <div class="flex justify-center items-center h-32 bg-gray-900/50 rounded-xl">
                    <p class="text-gray-500">Ad Space - 728x90</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Resources Section -->
    <section class="py-16 bg-gray-900">
        <div class="container mx-auto px-4 max-w-7xl">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold">Community Resources</h2>
                <p class="text-gray-400 mt-2 max-w-2xl mx-auto">
                    Access helpful resources, support services, and information tailored for the LGBTQ+ community in Lesotho.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Resource Card 1 -->
                <div class="bg-gray-800 rounded-2xl p-6 card-hover border border-gray-700">
                    <div class="w-14 h-14 gradient-bg rounded-xl flex items-center justify-center mb-4">
                        <i class="fas fa-heart text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold">Health & Wellness</h3>
                    <p class="text-gray-400 mt-2">
                        Find LGBTQ+ friendly healthcare providers, mental health resources, and wellness programs.
                    </p>
                    <a href="#" class="inline-block text-purple-400 mt-4 hover:text-purple-300">
                        Explore <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>

                <!-- Resource Card 2 -->
                <div class="bg-gray-800 rounded-2xl p-6 card-hover border border-gray-700">
                    <div class="w-14 h-14 gradient-bg rounded-xl flex items-center justify-center mb-4">
                        <i class="fas fa-gavel text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold">Legal Support</h3>
                    <p class="text-gray-400 mt-2">
                        Know your rights and access legal support for discrimination, documentation, and more.
                    </p>
                    <a href="#" class="inline-block text-purple-400 mt-4 hover:text-purple-300">
                        Explore <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>

                <!-- Resource Card 3 -->
                <div class="bg-gray-800 rounded-2xl p-6 card-hover border border-gray-700">
                    <div class="w-14 h-14 gradient-bg rounded-xl flex items-center justify-center mb-4">
                        <i class="fas fa-users text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold">Community Groups</h3>
                    <p class="text-gray-400 mt-2">
                        Connect with local LGBTQ+ groups, events, and community initiatives across Lesotho.
                    </p>
                    <a href="#" class="inline-block text-purple-400 mt-4 hover:text-purple-300">
                        Explore <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Events Section -->
    <section class="py-16 bg-gray-800">
        <div class="container mx-auto px-4 max-w-7xl">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold">Upcoming Events</h2>
                <p class="text-gray-400 mt-2 max-w-2xl mx-auto">
                    Join our community events, workshops, and celebrations throughout Lesotho.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Event 1 -->
                <div class="bg-gray-700/30 rounded-2xl overflow-hidden border border-gray-600 card-hover">
                    <div class="gradient-bg p-4 text-white">
                        <div class="flex justify-between items-center">
                            <span class="text-sm">June 28, 2023</span>
                            <span class="text-sm bg-black/20 px-2 py-1 rounded-full">In Person</span>
                        </div>
                        <h3 class="text-xl font-semibold mt-2">Pride March Maseru</h3>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-300">
                            Join us for the annual Pride March through the streets of Maseru. Show your support for the LGBTQ+ community in Lesotho.
                        </p>
                        <div class="flex items-center mt-6">
                            <div class="flex-shrink-0">
                                <i class="fas fa-map-marker-alt text-purple-400"></i>
                            </div>
                            <div class="ml-2">
                                <p class="text-sm text-gray-400">Independence Park, Maseru</p>
                            </div>
                        </div>
                        <button class="w-full mt-6 bg-gray-600 hover:bg-gray-500 text-white py-2 rounded-lg transition">
                            RSVP Now
                        </button>
                    </div>
                </div>

                <!-- Event 2 -->
                <div class="bg-gray-700/30 rounded-2xl overflow-hidden border border-gray-600 card-hover">
                    <div class="gradient-bg p-4 text-white">
                        <div class="flex justify-between items-center">
                            <span class="text-sm">July 15, 2023</span>
                            <span class="text-sm bg-black/20 px-2 py-1 rounded-full">Online</span>
                        </div>
                        <h3 class="text-xl font-semibold mt-2">LGBTQ+ Rights Workshop</h3>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-300">
                            Learn about your rights, how to advocate for yourself, and what legal protections exist in Lesotho.
                        </p>
                        <div class="flex items-center mt-6">
                            <div class="flex-shrink-0">
                                <i class="fas fa-globe text-purple-400"></i>
                            </div>
                            <div class="ml-2">
                                <p class="text-sm text-gray-400">Online via Zoom</p>
                            </div>
                        </div>
                        <button class="w-full mt-6 bg-gray-600 hover:bg-gray-500 text-white py-2 rounded-lg transition">
                            Register Now
                        </button>
                    </div>
                </div>
            </div>
        </div>
    <!-- Newsletter Section -->
    <section class="py-16 bg-gray-900">
        <div class="container mx-auto px-4 max-w-5xl">
            <div class="gradient-bg rounded-3xl p-10 text-center">
                <h2 class="text-3xl font-bold text-white">Stay Connected</h2>
                <p class="text-purple-100 mt-2 max-w-2xl mx-auto">
                    Subscribe to our newsletter for updates on events, resources, and community news.
                </p>
                
                <div class="mt-8 max-w-md mx-auto">
                    <form class="flex flex-col sm:flex-row gap-4">
                        <input 
                            type="email" 
                            placeholder="Your email address" 
                            class="flex-grow bg-white/10 border border-white/20 rounded-full px-6 py-3 text-white placeholder-purple-200 focus:outline-none focus:ring-2 focus:ring-white"
                        >
                        <button 
                            type="submit" 
                            class="bg-white text-purple-600 hover:bg-gray-100 font-medium rounded-full px-6 py-3 transition"
                        >
                            Subscribe
                        </button>
                    </form>
                </div>
                
                <p class="text-purple-200 text-sm mt-6">
                    We respect your privacy. You can unsubscribe at any time.
                </p>
            </div>
        </div>
    </section>
</div>
