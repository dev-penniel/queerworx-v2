{{-- <!DOCTYPE html>
<html lang="en"> --}}
{{-- <head>
    @include('partials.head')
</head>
<body class="bg-gray-50 font-sans antialiased">

    <!-- Header with blurred background -->
    <header class="relative py-12 bg-gradient-to-r from-blue-600 to-purple-700 overflow-hidden">
        <!-- Blurred background elements -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -left-20 -top-20 w-96 h-96 bg-blue-500 rounded-full filter blur-3xl opacity-30 animate-pulse"></div>
            <div class="absolute -right-20 -bottom-20 w-96 h-96 bg-purple-500 rounded-full filter blur-3xl opacity-30 animate-pulse delay-1000"></div>
        </div>
        
        <div class="relative container mx-auto px-4">
            <a wire:navigate href="{{ route('home') }}"><h1 class="text-4xl md:text-5xl font-bold text-white text-center mb-4">Penniel Blog Template</h1></a>
            <p class="text-xl text-blue-100 text-center max-w-3xl mx-auto">
                Discover the latest trends, insights, and stories from our expert writers
            </p>
        </div>
    </header>

    {{ $slot }}
    @fluxScripts

</body>
<!-- Footer with blurred background -->
    <footer class="relative bg-gray-900 text-white py-12 overflow-hidden mt-16">
        <!-- Blurred background elements -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -left-20 -top-20 w-96 h-96 bg-blue-900 rounded-full filter blur-3xl opacity-20"></div>
            <div class="absolute -right-20 -bottom-20 w-96 h-96 bg-purple-900 rounded-full filter blur-3xl opacity-20"></div>
        </div>
        
        <div class="relative container mx-auto px-4 text-center">
            <p>&copy; {{ date('Y') }} penniel.com All rights reserved.</p>
        </div>
    </footer>
</html> --}}

<!DOCTYPE html>
<html lang="en">
<head>
        @include('partials.head')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#8B5CF6',
                        secondary: '#EC4899',
                        dark: {
                            100: '#1a1a1a',
                            200: '#121212',
                            300: '#0a0a0a'
                        }
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        body {
            font-family: 'Poppins', sans-serif;
            scroll-behavior: smooth;
        }
        .gradient-bg {
            background: linear-gradient(135deg, #8B5CF6 0%, #EC4899 100%);
        }
        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(139, 92, 246, 0.4);
        }
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-200 min-h-screen" x-data="{ mobileMenu: false, searchOpen: false, aboutMenu: false, eventsMenu: false, programsMenu: false, darkMode: true }">
    @php
        $navPrograms = \App\Models\Program::where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();
    @endphp

    <!-- Header -->
    <header class="sticky top-0 z-50 bg-gray-800 shadow-lg">
        <div class="container mx-auto px-8 py-8 max-w-7xl">
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <a wire:navigate href="{{ route('home') }}" class="flex items-center">
                        <img
                            src="{{ asset('images/qw-logo-latest-trimmed.png') }}"
                            alt="Queer WorX"
                            class="h-20 w-auto object-contain md:h-24"
                        >
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <nav class="hidden items-center space-x-8 md:flex">
                    <a 
                        wire:navigate 
                        href="{{ route('home') }}" 
                        class="transition {{ request()->routeIs('home') ? 'text-purple-600 font-semibold' : 'hover:text-[#E61E5C]' }}"
                    >
                        Home
                    </a>
                    <div
                        class="relative"
                        @mouseenter="aboutMenu = true"
                        @mouseleave="aboutMenu = false"
                    >
                        <a
                            wire:navigate
                            href="{{ route('about') }}"
                            class="inline-flex items-center gap-2 transition {{ request()->routeIs('about') ? 'text-purple-600 font-semibold' : 'hover:text-[#F05A12]' }}"
                            @focus="aboutMenu = true"
                            @click="aboutMenu = !aboutMenu"
                        >
                            About Us
                            <i class="fa-solid fa-chevron-down text-xs"></i>
                        </a>

                        <div
                            x-cloak
                            x-show="aboutMenu"
                            x-transition
                            class="absolute left-0 top-full z-50 w-48 pt-4"
                        >
                            <div class="rounded-[8px] border border-white/10 bg-gray-900/95 p-2 shadow-2xl shadow-black/40 backdrop-blur">
                                <a href="{{ route('team') }}" class="block rounded px-4 py-2 text-sm text-gray-200 transition hover:bg-white/5 hover:text-[#E61E5C]">Team</a>
                                <a href="{{ route('board') }}" class="block rounded px-4 py-2 text-sm text-gray-200 transition hover:bg-white/5 hover:text-[#D98608]">Board</a>
                                <a href="{{ route('partners') }}" class="block rounded px-4 py-2 text-sm text-gray-200 transition hover:bg-white/5 hover:text-[#7646E8]">Partners</a>
                                <a href="{{ route('policies') }}" class="block rounded px-4 py-2 text-sm text-gray-200 transition hover:bg-white/5 hover:text-[#14A84D]">Policies</a>
                                <a href="{{ route('financials') }}" class="block rounded px-4 py-2 text-sm text-gray-200 transition hover:bg-white/5 hover:text-[#149CB9]">Financials</a>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('home') }}#resources" class="transition hover:text-[#FFD83D]">Resources</a>
                    <a 
                        wire:navigate 
                        href="{{ route('articles') }}" 
                        class="transition {{ request()->routeIs('articles') ? 'text-purple-600 font-semibold' : 'hover:text-[#14A84D]' }}"
                    >
                        Xpressions
                    </a>
                    <div
                        class="relative"
                        @mouseenter="eventsMenu = true"
                        @mouseleave="eventsMenu = false; programsMenu = false"
                    >
                        <a
                            href="{{ route('events') }}"
                            class="inline-flex items-center gap-2 transition {{ request()->routeIs('events') ? 'text-purple-600 font-semibold' : 'hover:text-[#2563EB]' }}"
                            @focus="eventsMenu = true"
                            @click="eventsMenu = !eventsMenu"
                        >
                            Events
                            <i class="fa-solid fa-chevron-down text-xs"></i>
                        </a>

                        <div
                            x-cloak
                            x-show="eventsMenu"
                            x-transition
                            class="absolute left-0 top-full z-50 w-52 pt-4"
                        >
                            <div class="rounded-[8px] border border-white/10 bg-gray-900/95 p-2 shadow-2xl shadow-black/40 backdrop-blur">
                                <div
                                    class="relative"
                                    @mouseenter="programsMenu = true"
                                    @mouseleave="programsMenu = false"
                                >
                                    <button type="button" class="flex w-full items-center justify-between rounded px-4 py-2 text-left text-sm text-gray-200 transition hover:bg-white/5 hover:text-[#E61E5C]">
                                        By Program
                                        <i class="fa-solid fa-chevron-right text-xs"></i>
                                    </button>

                                    <div
                                        x-cloak
                                        x-show="programsMenu"
                                        x-transition
                                        class="absolute left-full top-0 z-50 w-52 pl-3"
                                    >
                                        <div class="rounded-[8px] border border-white/10 bg-gray-900/95 p-2 shadow-2xl shadow-black/40 backdrop-blur">
                                            @forelse ($navPrograms as $program)
                                                <a href="{{ route('programs.show', $program->id) }}" class="flex items-center gap-2 rounded px-4 py-2 text-sm text-gray-200 transition hover:bg-white/5 hover:text-white">
                                                    <span class="h-2 w-2 rounded-full" style="background-color: {{ $program->color }}"></span>
                                                    <span>{{ $program->name }}</span>
                                                </a>
                                            @empty
                                                <span class="block rounded px-4 py-2 text-sm text-gray-500">No programs yet</span>
                                            @endforelse
                                            <a href="{{ route('programs') }}" class="block rounded px-4 py-2 text-sm text-gray-200 transition hover:bg-white/5 hover:text-[#149CB9]">All Programs</a>
                                        </div>
                                    </div>
                                </div>

                                <a href="{{ route('community') }}" class="block rounded px-4 py-2 text-sm text-gray-200 transition hover:bg-white/5 hover:text-[#14A84D]">Community</a>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('support') }}" class="transition hover:text-[#7646E8]">Support</a>
                </nav>

                <div class="relative flex items-center space-x-3">
                    <form
                        x-cloak
                        x-show="searchOpen"
                        x-transition
                        action="{{ route('search') }}"
                        method="GET"
                        class="absolute right-28 top-1/2 hidden w-64 -translate-y-1/2 md:block"
                    >
                        <label class="sr-only" for="site-search">Search site</label>
                        <input
                            id="site-search"
                            name="q"
                            type="search"
                            placeholder="Search the whole site..."
                            class="w-full rounded-full border border-white/10 bg-gray-900 px-4 py-2 text-sm text-white outline-none transition placeholder:text-gray-500 focus:border-purple-400"
                        >
                    </form>

                    <a href="{{ route('join-us') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-full text-sm transition">
                        Join Us
                    </a>
                    <button
                        type="button"
                        @click="searchOpen = !searchOpen"
                        class="flex h-10 w-10 items-center justify-center rounded-full text-gray-300 transition hover:bg-white/5 hover:text-[#FFD83D]"
                        aria-label="Search"
                    >
                        <i class="fas fa-search text-xl"></i>
                    </button>
                    <button
                        type="button"
                        @click="mobileMenu = !mobileMenu"
                        class="flex h-10 w-10 items-center justify-center rounded-full text-gray-300 transition hover:bg-white/5 hover:text-[#14A84D]"
                        aria-label="Open menu"
                    >
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenu" class="bg-gray-800 shadow-lg px-4 py-4 md:px-8">
            <div class="flex flex-col space-y-3">
                <form action="{{ route('search') }}" method="GET" class="md:hidden">
                    <label class="sr-only" for="mobile-site-search">Search site</label>
                    <input
                        id="mobile-site-search"
                        name="q"
                        type="search"
                        placeholder="Search the whole site..."
                        class="w-full rounded-full border border-white/10 bg-gray-900 px-4 py-2 text-sm text-white outline-none transition placeholder:text-gray-500 focus:border-purple-400"
                    >
                </form>
                <a
                    wire:navigate
                    href="{{ route('home') }}"
                    class="transition py-2 {{ request()->routeIs('home') ? 'text-purple-600 font-semibold' : 'hover:text-[#E61E5C]' }}"
                >
                    Home
                </a>
                <a
                    wire:navigate
                    href="{{ route('about') }}"
                    class="transition py-2 {{ request()->routeIs('about') ? 'text-purple-600 font-semibold' : 'hover:text-[#F05A12]' }}"
                >
                    About Us
                </a>
                <div class="-mt-2 ml-4 flex flex-col space-y-1 border-l border-white/10 pl-4">
                    <a href="{{ route('team') }}" class="py-1 text-sm text-gray-400 transition hover:text-[#E61E5C]">Team</a>
                    <a href="{{ route('board') }}" class="py-1 text-sm text-gray-400 transition hover:text-[#D98608]">Board</a>
                    <a href="{{ route('partners') }}" class="py-1 text-sm text-gray-400 transition hover:text-[#7646E8]">Partners</a>
                    <a href="{{ route('policies') }}" class="py-1 text-sm text-gray-400 transition hover:text-[#14A84D]">Policies</a>
                    <a href="{{ route('financials') }}" class="py-1 text-sm text-gray-400 transition hover:text-[#149CB9]">Financials</a>
                </div>
                <a href="{{ route('home') }}#resources" class="transition py-2 hover:text-[#FFD83D]">Resources</a>
                <a
                    wire:navigate
                    href="{{ route('articles') }}"
                    class="transition py-2 {{ request()->routeIs('articles') ? 'text-purple-600 font-semibold' : 'hover:text-[#14A84D]' }}"
                >
                    Xpressions
                </a>
                <a href="{{ route('events') }}" class="transition py-2 hover:text-[#2563EB]">Events</a>
                <div class="-mt-2 ml-4 flex flex-col space-y-1 border-l border-white/10 pl-4">
                    <span class="py-1 text-sm font-semibold text-gray-300">By Program</span>
                    <div class="ml-4 flex flex-col space-y-1 border-l border-white/10 pl-4">
                        @forelse ($navPrograms as $program)
                            <a href="{{ route('programs.show', $program->id) }}" class="py-1 text-sm text-gray-400 transition hover:text-white">{{ $program->name }}</a>
                        @empty
                            <span class="py-1 text-sm text-gray-500">No programs yet</span>
                        @endforelse
                        <a href="{{ route('programs') }}" class="py-1 text-sm text-gray-400 transition hover:text-[#149CB9]">All Programs</a>
                    </div>
                    <a href="{{ route('community') }}" class="py-1 text-sm text-gray-400 transition hover:text-[#14A84D]">Community</a>
                </div>
                <a href="{{ route('support') }}" class="transition py-2 hover:text-[#7646E8]">Support</a>
                <a href="{{ route('join-us') }}" class="bg-purple-600 hover:bg-purple-700 text-center text-white px-4 py-2 rounded-full text-sm transition mt-2">
                    Join Us
                </a>
            </div>
        </div>
    </header>

    {{ $slot }}

    <!-- Footer -->
    <footer class="bg-gray-800 py-12">
        <div class="container mx-auto px-8 max-w-7xl">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center space-x-2">
                        <div class="w-10 h-10 rounded-full gradient-bg flex items-center justify-center">
                            <span class="text-white font-bold text-xl">Q</span>
                        </div>
                        <span class="text-xl font-bold">Queer<span class="text-purple-500">Worx</span></span>
                    </div>
                    <p class="text-gray-400 mt-4">
                        Promoting and advancing economic and social inclusion for Lesotho LGBTIQ+ community.
                    </p>
                    <div class="flex space-x-4 mt-6">
                        <a href="https://web.facebook.com/queerworx" target="_blank" rel="noopener" class="text-gray-400 hover:text-white" aria-label="Queer WorX on Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://x.com/Queer_WorX" target="_blank" rel="noopener" class="text-gray-400 hover:text-white" aria-label="Queer WorX on X">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="https://www.instagram.com/queer_worx" target="_blank" rel="noopener" class="text-gray-400 hover:text-white" aria-label="Queer WorX on Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="https://www.tiktok.com/@queer_worx.ls" target="_blank" rel="noopener" class="text-gray-400 hover:text-white" aria-label="Queer WorX on TikTok">
                            <i class="fab fa-tiktok"></i>
                        </a>
                        <a href="https://www.linkedin.com/company/queer-worx/" target="_blank" rel="noopener" class="text-gray-400 hover:text-white" aria-label="Queer WorX on LinkedIn">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold">Quick Links</h3>
                    <ul class="mt-4 space-y-2">
                        <li><a href="{{ route('home') }}" class="text-gray-400 hover:text-white">Home</a></li>
                        <li><a href="{{ route('about') }}" class="text-gray-400 hover:text-white">About Us</a></li>
                        <li><a href="{{ route('home') }}#resources" class="text-gray-400 hover:text-white">Resources</a></li>
                        <li><a href="{{ route('programs') }}" class="text-gray-400 hover:text-white">Programs</a></li>
                        <li><a href="{{ route('join-us') }}" class="text-gray-400 hover:text-white">Get Involved</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold">Support</h3>
                    <ul class="mt-4 space-y-2">
                        <li><a href="{{ route('support') }}" class="text-gray-400 hover:text-white">Support Queer WorX</a></li>
                        <li><a href="{{ route('support') }}#support-form" class="text-gray-400 hover:text-white">Contact Us</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">FAQ</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Privacy Policy</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Terms of Service</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold">Contact</h3>
                    <ul class="mt-4 space-y-2">
                        <li class="flex items-start">
                            <i class="fas fa-map-marker-alt text-purple-400 mt-1 mr-2"></i>
                            <span class="text-gray-400">Leseli Community Centre, Selakhapane, Khubetsoana, Berea Hills, Lesotho </span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-phone text-purple-400 mt-1 mr-2"></i>
                            <span class="text-gray-400">+26662642114</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-envelope text-purple-400 mt-1 mr-2"></i>
                            <span class="text-gray-400">info@queerworx.org.ls</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-700 mt-12 pt-8 text-center text-gray-400">
                <p>&copy; 2023 QueerWorx. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>
