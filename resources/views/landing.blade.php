<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ImageConverter Pro - Premium Image Conversion Tool</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #0ea5e9 0%, #3b82f6 100%);
        }
        .custom-shape-divider-bottom {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            overflow: hidden;
            line-height: 0;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex flex-col">
        <!-- Navigation -->
        <nav class="bg-white shadow-md fixed w-full z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-20">
                    <div class="flex items-center">
                        <div class="flex items-center">
                            <i class="fas fa-image text-primary-600 text-3xl mr-2"></i>
                            <h1 class="text-2xl font-bold bg-gradient-to-r from-primary-600 to-blue-600 bg-clip-text text-transparent">
                                ImageConverter Pro
                            </h1>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-primary-600 font-medium transition duration-150 ease-in-out">
                            Sign In
                        </a>
                        <a href="{{ route('register') }}" class="gradient-bg text-white px-6 py-3 rounded-lg font-medium shadow-lg hover:shadow-xl transition duration-300 ease-in-out transform hover:-translate-y-0.5">
                            Start Free
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <main class="flex-grow pt-20">
            <div class="relative overflow-hidden">
                <div class="gradient-bg text-white py-24">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="text-center">
                            <h2 class="text-5xl md:text-6xl font-bold leading-tight mb-8">
                                Transform Your Images to
                                <span class="relative">
                                    <span class="block">Next-Gen WebP</span>
                                    <span class="absolute bottom-0 left-0 w-full h-2 bg-yellow-400 transform -skew-x-12"></span>
                                </span>
                            </h2>
                            <p class="mt-4 text-xl md:text-2xl text-gray-100 max-w-3xl mx-auto">
                                Experience lightning-fast image optimization with our premium WebP converter. 
                                Perfect for developers and businesses who demand the best performance.
                            </p>
                            <div class="mt-12 flex justify-center space-x-6">
                                <a href="{{ route('register') }}" class="bg-white text-primary-600 px-8 py-4 rounded-lg font-bold shadow-lg hover:shadow-xl transition duration-300 ease-in-out transform hover:-translate-y-0.5">
                                    Start Converting Now
                                </a>
                                <a href="#features" class="border-2 border-white text-white px-8 py-4 rounded-lg font-bold hover:bg-white hover:text-primary-600 transition duration-300">
                                    Learn More
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Section -->
            <div class="bg-white py-16">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                        <div class="p-6">
                            <div class="text-4xl font-bold text-primary-600 mb-2">30%+</div>
                            <div class="text-gray-600">Smaller File Size</div>
                        </div>
                        <div class="p-6">
                            <div class="text-4xl font-bold text-primary-600 mb-2">2x</div>
                            <div class="text-gray-600">Faster Loading</div>
                        </div>
                        <div class="p-6">
                            <div class="text-4xl font-bold text-primary-600 mb-2">100%</div>
                            <div class="text-gray-600">Browser Support</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Features -->
            <div id="features" class="py-20 bg-gray-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-16">
                        <h3 class="text-3xl font-bold text-gray-900">Why Choose ImageConverter Pro?</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <!-- Feature 1 -->
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden transform transition duration-500 hover:scale-105">
                            <div class="p-8">
                                <div class="w-12 h-12 gradient-bg rounded-lg flex items-center justify-center mb-4">
                                    <i class="fas fa-compress-alt text-white text-2xl"></i>
                                </div>
                                <h4 class="text-xl font-semibold text-gray-900 mb-2">Advanced Compression</h4>
                                <p class="text-gray-600">
                                    State-of-the-art compression algorithm that reduces file size without compromising quality.
                                </p>
                            </div>
                        </div>

                        <!-- Feature 2 -->
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden transform transition duration-500 hover:scale-105">
                            <div class="p-8">
                                <div class="w-12 h-12 gradient-bg rounded-lg flex items-center justify-center mb-4">
                                    <i class="fas fa-bolt text-white text-2xl"></i>
                                </div>
                                <h4 class="text-xl font-semibold text-gray-900 mb-2">Lightning Fast</h4>
                                <p class="text-gray-600">
                                    Bulk convert your images in seconds with our optimized processing engine.
                                </p>
                            </div>
                        </div>

                        <!-- Feature 3 -->
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden transform transition duration-500 hover:scale-105">
                            <div class="p-8">
                                <div class="w-12 h-12 gradient-bg rounded-lg flex items-center justify-center mb-4">
                                    <i class="fas fa-shield-alt text-white text-2xl"></i>
                                </div>
                                <h4 class="text-xl font-semibold text-gray-900 mb-2">Secure & Private</h4>
                                <p class="text-gray-600">
                                    Your images are processed securely and deleted after conversion.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CTA Section -->
            <div class="gradient-bg py-20">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <h3 class="text-3xl font-bold text-white mb-8">Ready to Optimize Your Images?</h3>
                    <a href="{{ route('register') }}" class="inline-block bg-white text-primary-600 px-8 py-4 rounded-lg font-bold shadow-lg hover:shadow-xl transition duration-300 ease-in-out transform hover:-translate-y-0.5">
                        Get Started for Free
                    </a>
                    <p class="mt-4 text-gray-100">
                        No credit card required • Free tier available • Cancel anytime
                    </p>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-gray-900 text-gray-300 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <div class="flex items-center mb-4">
                            <i class="fas fa-image text-primary-400 text-2xl mr-2"></i>
                            <span class="text-xl font-bold text-white">ImageConverter Pro</span>
                        </div>
                        <p class="text-gray-400">
                            The professional choice for image optimization and conversion.
                        </p>
                    </div>
                    <div class="text-right">
                        <p>&copy; {{ date('Y') }} ImageConverter Pro. All rights reserved.</p>
                        <div class="mt-4 space-x-4">
                            <a href="#" class="text-gray-400 hover:text-white transition duration-150">Privacy Policy</a>
                            <a href="#" class="text-gray-400 hover:text-white transition duration-150">Terms of Service</a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</body>
</html> 