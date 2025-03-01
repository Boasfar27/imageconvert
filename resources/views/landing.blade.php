<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ImageConverter Pro - Premium Image Conversion Tool</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}" />
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex flex-col">
        <!-- Navigation -->
        <nav class="nav-blur text-white shadow-md fixed w-full z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        {{-- Logo Boasfar --}}
                        <div class="flex items-center cursor-pointer group" onclick="window.location.reload()">
                            <span class="text-2xl sm:text-3xl font-bold text-indigo-600 dark:text-indigo-400">Boas</span>
                            <span class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-gray-200">far</span>
                        </div>
                    </div>
                    
                    <!-- Desktop Menu -->
                    <div class="hidden md:flex items-center space-x-6">
                        <a href="{{ route('login') }}" class="text-gray-300 hover:text-indigo-400 text-base font-medium flex items-center">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Sign In
                        </a>
                        <a href="{{ route('register') }}" class="gradient-bg text-white px-6 py-3 rounded-2xl text-base font-medium shadow-lg hover:shadow-xl transition duration-300 ease-in-out transform hover:-translate-y-0.5 flex items-center">
                            <i class="fas fa-user-plus mr-2"></i>
                            Start Free
                        </a>
                    </div>

                    <!-- Mobile Menu Button -->
                    <div class="md:hidden flex items-center">
                        <button id="mobile-menu-button" class="text-gray-200 hover:text-white focus:outline-none relative w-10 h-10 flex items-center justify-center">
                            <i class="fas fa-bars text-2xl transition-all duration-300" id="menu-open-icon"></i>
                            <i class="fas fa-times text-2xl transition-all duration-300 absolute opacity-0" id="menu-close-icon"></i>
                        </button>
                    </div>
                </div>

                <!-- Mobile Menu -->
                <div id="mobile-menu" class="hidden md:hidden">
                    <div class="px-2 pt-2 pb-3 space-y-3 bg-gray-900/90 backdrop-blur-lg rounded-lg mt-2">
                        <a href="{{ route('login') }}" class="block text-gray-300 hover:text-white hover:bg-gray-700 px-3 py-2 rounded-lg transition duration-300 flex items-center">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Sign In
                        </a>
                        <a href="{{ route('register') }}" class="block gradient-bg text-white px-3 py-2 rounded-lg transition duration-300 flex items-center">
                            <i class="fas fa-user-plus mr-2"></i>
                            Start Free
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <main class="flex-grow">
            <div class="relative overflow-hidden">
                <div class="hero-gradient hero-section">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                        <div class="text-center relative z-10">
                            <div class="floating-element mb-8">
                                <i class="fas fa-image text-6xl text-primary-400 mb-4 style="color: #38bdf8;"></i>
                            </div>
                            <h2 class="text-3xl sm:text-4xl lg:text-5xl xl:text-6xl font-bold leading-[1.2] sm:leading-[1.3] lg:leading-[1.4] tracking-tight mb-6 sm:mb-8 lg:mb-10">
                                <span class="inline-block bg-clip-text text-transparent bg-gradient-to-r from-blue-400 to-indigo-600 mb-2 sm:mb-3">
                                    Transform Your Images to
                                </span>
                                <span class="block text-white relative mt-2 sm:mt-3">
                                    Next-Gen WebP
                                    <div class="absolute -bottom-3 sm:-bottom-4 left-0 w-full h-2 bg-gradient-to-r from-yellow-400 to-orange-500 transform -skew-x-12"></div>
                                </span>
                            </h2>
                            <p class="mt-4 text-lg md:text-2xl text-gray-100 max-w-3xl mx-auto animate-pulse-slow">
                                Experience lightning-fast image optimization with our premium WebP converter. 
                                Perfect for developers and businesses who demand the best performance.
                            </p>
                            <div class="mt-12 flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-6">
                                <a href="{{ route('register') }}" class="group bg-white text-primary-600 px-8 py-4 rounded-lg font-bold shadow-lg hover:shadow-xl transition duration-300 ease-in-out transform hover:-translate-y-0.5">
                                    <i class="fas fa-rocket mr-2 group-hover:animate-bounce"></i>
                                    Start Converting Now
                                    <i class="fas fa-arrow-right ml-3 transform transition-transform group-hover:translate-x-2"></i>
                                </a>
                                <a href="#features" class="group border-2 border-white text-white px-8 py-4 rounded-lg font-bold hover:bg-gradient-to-r from-blue-400 to-indigo-600 hover:text-white transition duration-300">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    Learn More
                                    <i class="fas fa-arrow-right ml-3 transform transition-transform group-hover:translate-x-2"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Section -->
            <div class="section-blur py-16">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                        <div class="stats-card p-8">
                            <div class="flex items-center justify-center mb-4">
                                <i class="fas fa-compress-alt text-3xl text-primary-400"></i>
                            </div>
                            <div class="text-4xl font-bold text-primary-400 mb-2">30%+</div>
                            <div class="text-gray-300">Smaller File Size</div>
                        </div>
                        <div class="stats-card p-8">
                            <div class="flex items-center justify-center mb-4">
                                <i class="fas fa-bolt text-3xl text-primary-400"></i>
                            </div>
                            <div class="text-4xl font-bold text-primary-400 mb-2">2x</div>
                            <div class="text-gray-300">Faster Loading</div>
                        </div>
                        <div class="stats-card p-8">
                            <div class="flex items-center justify-center mb-4">
                                <i class="fas fa-globe text-3xl text-primary-400"></i>
                            </div>
                            <div class="text-4xl font-bold text-primary-400 mb-2">100%</div>
                            <div class="text-gray-300">Browser Support</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Features -->
            <div id="features" class="section-blur py-20 relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-primary-900/20 to-transparent"></div>
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                    <div class="text-center mb-16">
                        <h3 class="text-3xl font-bold text-white inline-block bg-gradient-to-r from-primary-400 to-blue-600 bg-clip-text text-transparent">
                            Why Choose ImageConverter Pro?
                        </h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <!-- Feature cards with centered styling -->
                        <div class="feature-card rounded-xl shadow-lg overflow-hidden transform transition duration-500 hover:scale-105 hover:shadow-2xl">
                            <div class="p-8 flex flex-col items-center text-center">
                                <div class="w-20 h-20 gradient-bg rounded-2xl flex items-center justify-center mb-6 transform transition-transform duration-500 hover:rotate-12">
                                    <i class="fas fa-compress-alt text-gray-900 text-4xl"></i>
                                </div>
                                <h4 class="text-2xl font-semibold text-gray-900 mb-4">Advanced Compression</h4>
                                <p class="text-gray-600 max-w-sm mx-auto">
                                    State-of-the-art compression algorithm that reduces file size without compromising quality.
                                </p>
                            </div>
                        </div>

                        <!-- Feature 2 -->
                        <div class="feature-card rounded-xl shadow-lg overflow-hidden transform transition duration-500 hover:scale-105 hover:shadow-2xl">
                            <div class="p-8 flex flex-col items-center text-center">
                                <div class="w-20 h-20 gradient-bg rounded-2xl flex items-center justify-center mb-6 transform transition-transform duration-500 hover:rotate-12">
                                    <i class="fas fa-bolt text-gray-900 text-4xl"></i>
                                </div>
                                <h4 class="text-2xl font-semibold text-gray-900 mb-4">Lightning Fast</h4>
                                <p class="text-gray-600 max-w-sm mx-auto">
                                    Bulk convert your images in seconds with our optimized processing engine.
                                </p>
                            </div>
                        </div>

                        <!-- Feature 3 -->
                        <div class="feature-card rounded-xl shadow-lg overflow-hidden transform transition duration-500 hover:scale-105 hover:shadow-2xl">
                            <div class="p-8 flex flex-col items-center text-center">
                                <div class="w-20 h-20 gradient-bg rounded-2xl flex items-center justify-center mb-6 transform transition-transform duration-500 hover:rotate-12">
                                    <i class="fas fa-shield-alt text-gray-900 text-4xl"></i>
                                </div>
                                <h4 class="text-2xl font-semibold text-gray-900 mb-4">Secure & Private</h4>
                                <p class="text-gray-600 max-w-sm mx-auto">
                                    Your images are processed securely and deleted after conversion.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CTA Section -->
            <div class="section-blur py-12 sm:py-20">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <h3 class="text-2xl sm:text-3xl font-bold text-white mb-6 sm:mb-8">Ready to Optimize Your Images?</h3>
                    <a href="{{ route('register') }}" class="inline-flex items-center bg-white text-primary-600 px-6 sm:px-8 py-3 sm:py-4 rounded-lg font-bold shadow-lg hover:shadow-xl transition duration-300 ease-in-out transform hover:-translate-y-0.5 group">
                        <i class="fas fa-rocket mr-2 sm:mr-3 group-hover:animate-bounce"></i>
                        <span class="text-sm sm:text-base">Get Started for Free</span>
                        <i class="fas fa-arrow-right ml-2 sm:ml-3 transform transition-transform group-hover:translate-x-2"></i>
                    </a>
                    <div class="mt-6 sm:mt-8 grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-8 max-w-4xl mx-auto">
                        <div class="flex items-center justify-center text-gray-100 bg-primary-600/10 rounded-lg p-3 sm:p-4">
                            <i class="fas fa-credit-card text-primary-400 mr-2"></i>
                            <span class="text-sm sm:text-base">No credit card required</span>
                        </div>
                        <div class="flex items-center justify-center text-gray-100 bg-primary-600/10 rounded-lg p-3 sm:p-4">
                            <i class="fas fa-gift text-primary-400 mr-2"></i>
                            <span class="text-sm sm:text-base">Free tier available</span>
                        </div>
                        <div class="flex items-center justify-center text-gray-100 bg-primary-600/10 rounded-lg p-3 sm:p-4">
                            <i class="fas fa-calendar-check text-primary-400 mr-2"></i>
                            <span class="text-sm sm:text-base">Cancel anytime</span>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="section-blur py-12 sm:py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-24 gap-8">
                    <!-- Logo & Social Links -->
                    <div class="md:col-span-4">
                        <div class="flex items-center justify-center mb-4">
                            <span class="text-2xl sm:text-3xl font-bold text-indigo-400">Contact Me</span>
                        </div>
                        <div class="flex space-x-4 mt-6 items-center justify-center">
                            <a href="https://boasfar.my.id/" class="w-10 h-10 rounded-full bg-primary-600/20 flex items-center justify-center text-primary-400 hover:bg-primary-600/30 transition-all duration-300">
                                <i class="fa-solid fa-globe"></i>
                            </a>
                            <a href="https://github.com/Boasfar27" class="w-10 h-10 rounded-full bg-primary-600/20 flex items-center justify-center text-primary-400 hover:bg-primary-600/30 transition-all duration-300">
                                <i class="fa-brands fa-github"></i>
                            </a>
                            <a href="mailto:muhammadfarhan2747@gmail.com" class="w-10 h-10 rounded-full bg-primary-600/20 flex items-center justify-center text-primary-400 hover:bg-primary-600/30 transition-all duration-300">
                                <i class="fas fa-envelope"></i>
                            </a>
                            <a href="https://www.instagram.com/farhaaan____/" class="w-10 h-10 rounded-full bg-primary-600/20 flex items-center justify-center text-primary-400 hover:bg-primary-600/30 transition-all duration-300">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="https://wa.me/6285158442747" class="w-10 h-10 rounded-full bg-primary-600/20 flex items-center justify-center text-primary-400 hover:bg-primary-600/30 transition-all duration-300">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div class="md:col-span-4">
                        <div class="flex items-center justify-center mb-4">
                            <span class="text-2xl sm:text-3xl font-bold text-indigo-400">Free For You</span>
                        </div>
                        <div class="flex flex-wrap justify-center gap-4 mt-6">
                            <a href="#" class="w-10 h-10 rounded-full bg-primary-600/20 flex items-center justify-center text-primary-400 hover:bg-primary-600/30 transition-all duration-300">
                                <i class="fas fa-gift"></i>
                            </a>
                            <a href="#" class="w-10 h-10 rounded-full bg-primary-600/20 flex items-center justify-center text-primary-400 hover:bg-primary-600/30 transition-all duration-300">
                                <i class="fas fa-star"></i>
                            </a>
                            <a href="#" class="w-10 h-10 rounded-full bg-primary-600/20 flex items-center justify-center text-primary-400 hover:bg-primary-600/30 transition-all duration-300">
                                <i class="fas fa-heart"></i>
                            </a>
                            <a href="#" class="w-10 h-10 rounded-full bg-primary-600/20 flex items-center justify-center text-primary-400 hover:bg-primary-600/30 transition-all duration-300">
                                <i class="fas fa-crown"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Copyright -->
                <div class="border-t border-gray-700 mt-12 pt-8 text-center">
                    <p class="text-gray-400">&copy; 2025 Boasfar. Hak Cipta Dilindungi.</p>
                </div>
            </div>
        </footer>

        <!-- Back to Top Button -->
        <button class="back-to-top" id="backToTop" title="Back to Top">
            <i class="fas fa-chevron-up text-white text-lg"></i>
        </button>
    </div>

    <script src="{{ asset('js/main.js') }}"></script>
</body>
</html> 