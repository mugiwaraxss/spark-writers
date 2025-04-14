<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Spark Writers') }} - Professional Writing Services</title>
        
        <!-- Favicon -->
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon/apple-touch-icon.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon/favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon/favicon-16x16.png') }}">
        <link rel="manifest" href="{{ asset('favicon/site.webmanifest') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased">
        <!-- Header -->
        <header class="fixed inset-x-0 top-0 z-50 bg-white shadow-sm dark:bg-gray-900">
            <nav class="mx-auto flex max-w-7xl items-center justify-between p-6 lg:px-8">
                <div class="flex lg:flex-1">
                    <a href="#" class="-m-1.5 flex items-center p-1.5">
                        <img class="h-8 w-auto" src="{{ asset('images/logo.svg') }}" alt="Spark Writers Logo">
                        <span class="ml-3 text-xl font-bold text-gray-900 dark:text-white">Spark Writers</span>
                    </a>
                </div>
                
                <div class="flex lg:hidden">
                    <button type="button" id="mobile-menu-button" class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-gray-700 dark:text-gray-200">
                        <span class="sr-only">Open main menu</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>
                </div>
                
                <div class="hidden lg:flex lg:gap-x-12">
                    <a href="#services" class="text-sm font-semibold leading-6 text-gray-900 dark:text-white">Services</a>
                    <a href="#how-it-works" class="text-sm font-semibold leading-6 text-gray-900 dark:text-white">How It Works</a>
                    <a href="#pricing" class="text-sm font-semibold leading-6 text-gray-900 dark:text-white">Pricing</a>
                    <a href="#testimonials" class="text-sm font-semibold leading-6 text-gray-900 dark:text-white">Testimonials</a>
                    <a href="#faq" class="text-sm font-semibold leading-6 text-gray-900 dark:text-white">FAQ</a>
                </div>
                
                <div class="hidden lg:flex lg:flex-1 lg:justify-end lg:gap-x-6">
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-sm font-semibold leading-6 text-gray-900 dark:text-white">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-semibold leading-6 text-gray-900 dark:text-white">Log in</a>
                        <a href="{{ route('register') }}" class="rounded-md bg-blue-600 px-3.5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">Sign up</a>
                    @endauth
                </div>
            </nav>
            
            <!-- Mobile menu -->
            <div id="mobile-menu" class="hidden lg:hidden">
                <div class="space-y-1 px-4 pb-3 pt-2 sm:px-3">
                    <a href="#services" class="block rounded-md px-3 py-2 text-base font-medium text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-800">Services</a>
                    <a href="#how-it-works" class="block rounded-md px-3 py-2 text-base font-medium text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-800">How It Works</a>
                    <a href="#pricing" class="block rounded-md px-3 py-2 text-base font-medium text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-800">Pricing</a>
                    <a href="#testimonials" class="block rounded-md px-3 py-2 text-base font-medium text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-800">Testimonials</a>
                    <a href="#faq" class="block rounded-md px-3 py-2 text-base font-medium text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-800">FAQ</a>
                </div>
                <div class="border-t border-gray-200 pb-3 pt-4 dark:border-gray-700">
                    <div class="space-y-1 px-4">
                        @auth
                            <a href="{{ route('dashboard') }}" class="block rounded-md px-3 py-2 text-base font-medium text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-800">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="block rounded-md px-3 py-2 text-base font-medium text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-800">Log in</a>
                            <a href="{{ route('register') }}" class="block rounded-md bg-blue-600 px-3 py-2 text-base font-medium text-white hover:bg-blue-500">Sign up</a>
                        @endauth
                    </div>
                </div>
            </div>
        </header>
        
        <!-- Hero Section -->
        <div class="bg-gradient-to-b from-blue-50 to-white pt-32 dark:from-gray-900 dark:to-gray-800">
            <div class="mx-auto max-w-7xl px-6 py-24 sm:py-32 lg:flex lg:items-center lg:gap-x-10 lg:px-8 lg:py-40">
                <div class="mx-auto max-w-2xl lg:mx-0 lg:flex-auto">
                    <h1 class="max-w-lg text-4xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-6xl">Professional Writing Services for Every Need</h1>
                    <p class="mt-6 text-lg leading-8 text-gray-600 dark:text-gray-300">Get high-quality, plagiarism-free papers written by experts in your field. Our team of professional writers delivers custom content tailored to your specific requirements.</p>
                    <div class="mt-10 flex items-center gap-x-6">
                        <a href="{{ route('register') }}" class="rounded-md bg-blue-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">Get Started</a>
                        <a href="#how-it-works" class="text-sm font-semibold leading-6 text-gray-900 dark:text-white">Learn more <span aria-hidden="true">→</span></a>
                    </div>
                    
                    <div class="mt-10 flex flex-wrap items-center gap-4 text-sm text-gray-600 dark:text-gray-400">
                        <div class="flex items-center">
                            <svg class="mr-2 h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            100% Plagiarism-Free
                        </div>
                        <div class="flex items-center">
                            <svg class="mr-2 h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            On-Time Delivery
                        </div>
                        <div class="flex items-center">
                            <svg class="mr-2 h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            24/7 Support
                        </div>
                        <div class="flex items-center">
                            <svg class="mr-2 h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Money-Back Guarantee
                        </div>
                    </div>
                </div>
                <div class="mt-16 sm:mt-24 lg:mt-0 lg:flex-shrink-0 lg:flex-grow">
                    <div class="relative mx-auto rounded-xl bg-white p-6 shadow-xl dark:bg-gray-800 sm:max-w-md">
                        <div class="absolute -top-10 left-1/2 -translate-x-1/2 transform">
                            <div class="flex h-20 w-20 items-center justify-center rounded-full bg-blue-600 p-4 text-white shadow-lg">
                                <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                        </div>
                        <h2 class="mb-6 mt-10 text-center text-xl font-bold text-gray-900 dark:text-white">Request a Free Quote</h2>
                        <form action="{{ route('quote.request') }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label for="academic_level" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Academic Level</label>
                                <select id="academic_level" name="academic_level" class="mt-1 block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-blue-500 focus:outline-none focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm">
                                    <option value="high_school">High School</option>
                                    <option value="college">College</option>
                                    <option value="undergraduate">Undergraduate</option>
                                    <option value="masters">Master's</option>
                                    <option value="phd">PhD</option>
                                </select>
                            </div>
                            <div>
                                <label for="page_count" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Number of Pages</label>
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <button type="button" id="decrement-pages" class="inline-flex items-center rounded-l-md border border-r-0 border-gray-300 bg-gray-50 px-3 text-gray-500 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-400">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                        </svg>
                                    </button>
                                    <input type="number" name="page_count" id="page_count" min="1" value="5" class="block w-full border-gray-300 text-center dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm">
                                    <button type="button" id="increment-pages" class="inline-flex items-center rounded-r-md border border-l-0 border-gray-300 bg-gray-50 px-3 text-gray-500 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-400">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div>
                                <label for="deadline" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Deadline</label>
                                <select id="deadline" name="deadline" class="mt-1 block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-blue-500 focus:outline-none focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm">
                                    <option value="6">6 Hours</option>
                                    <option value="12">12 Hours</option>
                                    <option value="24">24 Hours</option>
                                    <option value="48" selected>2 Days</option>
                                    <option value="72">3 Days</option>
                                    <option value="168">7 Days</option>
                                    <option value="336">14 Days</option>
                                </select>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Estimated Price: <span id="estimated-price" class="text-blue-600">$50.00</span></h3>
                            </div>
                            <div>
                                <button type="submit" class="flex w-full justify-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                                    Get Full Quote
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <script>
            // Mobile menu toggle
            document.getElementById('mobile-menu-button').addEventListener('click', function() {
                const menu = document.getElementById('mobile-menu');
                menu.classList.toggle('hidden');
            });
            
            // Page count increment/decrement
            document.getElementById('increment-pages').addEventListener('click', function() {
                const input = document.getElementById('page_count');
                input.value = parseInt(input.value) + 1;
                updatePrice();
            });
            
            document.getElementById('decrement-pages').addEventListener('click', function() {
                const input = document.getElementById('page_count');
                if (parseInt(input.value) > 1) {
                    input.value = parseInt(input.value) - 1;
                    updatePrice();
                }
            });
            
            // Update estimated price
            function updatePrice() {
                const academicLevel = document.getElementById('academic_level').value;
                const pageCount = parseInt(document.getElementById('page_count').value);
                const deadline = parseInt(document.getElementById('deadline').value);
                
                // Base price per page based on academic level
                const basePrices = {
                    'high_school': 10.99,
                    'college': 14.99,
                    'undergraduate': 16.99,
                    'masters': 22.99,
                    'phd': 28.99
                };
                
                // Deadline multipliers
                const deadlineMultipliers = {
                    '6': 2.0,  // 6 hours - urgency premium
                    '12': 1.8, // 12 hours
                    '24': 1.5, // 24 hours
                    '48': 1.3, // 2 days
                    '72': 1.2, // 3 days
                    '168': 1.0, // 7 days
                    '336': 0.9  // 14 days - discount
                };
                
                const basePrice = basePrices[academicLevel] || basePrices['undergraduate'];
                const multiplier = deadlineMultipliers[deadline] || deadlineMultipliers['168'];
                
                const totalPrice = (basePrice * multiplier * pageCount).toFixed(2);
                document.getElementById('estimated-price').textContent = '$' + totalPrice;
            }
            
            // Initialize price and add event listeners
            document.addEventListener('DOMContentLoaded', function() {
                updatePrice();
                
                document.getElementById('academic_level').addEventListener('change', updatePrice);
                document.getElementById('page_count').addEventListener('change', updatePrice);
                document.getElementById('page_count').addEventListener('input', updatePrice);
                document.getElementById('deadline').addEventListener('change', updatePrice);
            });
        </script>
    </body>
</html>
