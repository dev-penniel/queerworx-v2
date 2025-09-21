<!DOCTYPE html>
<html lang="en">
<head>
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
</html>