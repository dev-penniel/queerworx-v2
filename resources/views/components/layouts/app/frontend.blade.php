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
    </style>
</head>
<body class="bg-gray-900 text-gray-200 min-h-screen" x-data="{ mobileMenu: false, darkMode: true }">
    <!-- Header -->
    <header class="sticky top-0 z-50 bg-gray-800 shadow-lg">
        <div class="container mx-auto px-8 py-8 max-w-7xl">
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <a href="#" class="flex items-center space-x-2">
                        <div class="w-10 h-10 rounded-full gradient-bg flex items-center justify-center">
                            <span class="text-white font-bold text-xl">Q</span>
                        </div>
                        <span class="text-xl font-bold">Queer<span class="text-purple-500">Worx</span></span>
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <nav class="hidden md:flex space-x-8">
                    <a 
                        wire:navigate 
                        href="{{ route('home') }}" 
                        class="transition {{ request()->routeIs('home') ? 'text-purple-600 font-semibold' : 'hover:text-purple-400' }}"
                    >
                        Home
                    </a>
                    <a href="#" class="hover:text-purple-400 transition">Resources</a>
                    <a 
                        wire:navigate 
                        href="{{ route('articles') }}" 
                        class="transition {{ request()->routeIs('articles') ? 'text-purple-600 font-semibold' : 'hover:text-purple-400' }}"
                    >
                        Xpressions
                    </a>
                    <a href="#" class="hover:text-purple-400 transition">Community</a>
                    <a href="#" class="hover:text-purple-400 transition">Events</a>
                    <a href="#" class="hover:text-purple-400 transition">Support</a>
                </nav>

                <div class="flex items-center space-x-4">
                    <button class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-full text-sm transition">
                        Join Us
                    </button>
                    <button @click="mobileMenu = !mobileMenu" class="md:hidden text-gray-300 hover:text-white">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenu" class="md:hidden bg-gray-800 shadow-lg px-4 py-4">
            <div class="flex flex-col space-y-3">
                <a href="#" class="hover:text-purple-400 transition py-2">Home</a>
                <a href="#" class="hover:text-purple-400 transition py-2">Resources</a>
                <a href="#" class="hover:text-purple-400 transition py-2">Community</a>
                <a href="#" class="hover:text-purple-400 transition py-2">Events</a>
                <a href="#" class="hover:text-purple-400 transition py-2">Support</a>
                <button class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-full text-sm transition mt-2">
                    Sign In
                </button>
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
                        Supporting and empowering the LGBTQ+ community in Lesotho.
                    </p>
                    <div class="flex space-x-4 mt-6">
                        <a href="#" class="text-gray-400 hover:text-white">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold">Quick Links</h3>
                    <ul class="mt-4 space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white">Home</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">About Us</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Resources</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Events</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Get Involved</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold">Support</h3>
                    <ul class="mt-4 space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white">Help Center</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Contact Us</a></li>
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
                            <span class="text-gray-400">123 Rainbow Road, Maseru, Lesotho</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-phone text-purple-400 mt-1 mr-2"></i>
                            <span class="text-gray-400">+266 123 4567</span>
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