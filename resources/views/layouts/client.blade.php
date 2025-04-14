<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
        <title>{{ config('app.name', 'Laravel') }} - Client {{ $title ?? '' }}</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <style>
            /* Page transitions */
            .page-transition-enter { 
                opacity: 0; 
                transform: translateY(5px);
            }
            .page-transition-enter-active { 
                opacity: 1; 
                transform: translateY(0);
                transition: opacity 300ms, transform 300ms;
            }
            
            /* Button hover effects */
            .btn-hover-effect {
                transition: all 0.3s ease;
                position: relative;
                overflow: hidden;
            }
            
            .btn-hover-effect:after {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(255, 255, 255, 0.2);
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .btn-hover-effect:hover:after {
                transform: translateX(0);
            }
        </style>
    </head>
    <body class="min-h-screen bg-gray-50 text-gray-900">
        <div class="min-h-screen">
            <!-- Top Navigation Bar -->
            <nav class="bg-primary-600 text-white shadow-md">
                <div class="px-4">
                    <div class="flex h-16 items-center justify-between">
                        <div class="flex items-center">
                            <a href="{{ route('client.dashboard') }}" class="flex items-center space-x-2 transition-transform hover:scale-105">
                                <x-app-logo class="h-8 w-8 text-white" />
                                <span class="text-xl font-semibold text-white">Client Portal</span>
                            </a>
                        </div>

                        <div class="flex items-center space-x-4">
                            <div class="flex items-center space-x-2">
                                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-primary-500 text-white">
                                    <i class="fas fa-user text-sm"></i>
                                </span>
                                <span class="text-sm font-medium text-white">{{ auth()->user()->name }}</span>
                            </div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn-hover-effect flex items-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-primary-600 shadow-sm transition-all hover:bg-opacity-90 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-primary-300 focus:ring-offset-2">
                                    <i class="fas fa-sign-out-alt mr-2"></i>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Main Content Area -->
            <div class="flex">
                <!-- Sidebar -->
                <div class="hidden w-64 flex-shrink-0 bg-white shadow-md lg:block">
                    <nav class="flex h-full flex-col">
                        <div class="space-y-1 p-4">
                            <a href="{{ route('client.dashboard') }}" class="group flex items-center rounded-lg px-4 py-3 text-sm font-medium transition-all duration-200 {{ request()->routeIs('client.dashboard') ? 'bg-primary-100 text-primary-700' : 'text-gray-700 hover:bg-primary-50 hover:text-primary-700' }}">
                                <i class="fas fa-home mr-3 text-lg {{ request()->routeIs('client.dashboard') ? 'text-primary-600' : 'text-gray-500 group-hover:text-primary-600' }}"></i>
                                <span>Dashboard</span>
                            </a>
                            <a href="{{ route('client.orders.create') }}" class="group flex items-center rounded-lg px-4 py-3 text-sm font-medium transition-all duration-200 {{ request()->routeIs('client.orders.create') ? 'bg-primary-100 text-primary-700' : 'text-gray-700 hover:bg-primary-50 hover:text-primary-700' }}">
                                <i class="fas fa-plus-circle mr-3 text-lg {{ request()->routeIs('client.orders.create') ? 'text-primary-600' : 'text-gray-500 group-hover:text-primary-600' }}"></i>
                                <span>Place Order</span>
                            </a>
                            <a href="{{ route('client.orders') }}" class="group flex items-center rounded-lg px-4 py-3 text-sm font-medium transition-all duration-200 {{ request()->routeIs('client.orders') || request()->routeIs('client.orders.*') && !request()->routeIs('client.orders.create') ? 'bg-primary-100 text-primary-700' : 'text-gray-700 hover:bg-primary-50 hover:text-primary-700' }}">
                                <i class="fas fa-file-alt mr-3 text-lg {{ request()->routeIs('client.orders') || request()->routeIs('client.orders.*') && !request()->routeIs('client.orders.create') ? 'text-primary-600' : 'text-gray-500 group-hover:text-primary-600' }}"></i>
                                <span>My Orders</span>
                            </a>
                            <a href="{{ route('client.payments') }}" class="group flex items-center rounded-lg px-4 py-3 text-sm font-medium transition-all duration-200 {{ request()->routeIs('client.payments') || request()->routeIs('client.payments.*') ? 'bg-primary-100 text-primary-700' : 'text-gray-700 hover:bg-primary-50 hover:text-primary-700' }}">
                                <i class="fas fa-credit-card mr-3 text-lg {{ request()->routeIs('client.payments') || request()->routeIs('client.payments.*') ? 'text-primary-600' : 'text-gray-500 group-hover:text-primary-600' }}"></i>
                                <span>Payment History</span>
                            </a>
                            <a href="{{ route('client.notifications') }}" class="group flex items-center rounded-lg px-4 py-3 text-sm font-medium transition-all duration-200 {{ request()->routeIs('client.notifications') ? 'bg-primary-100 text-primary-700' : 'text-gray-700 hover:bg-primary-50 hover:text-primary-700' }}">
                                <i class="fas fa-bell mr-3 text-lg {{ request()->routeIs('client.notifications') ? 'text-primary-600' : 'text-gray-500 group-hover:text-primary-600' }}"></i>
                                <span>Notifications</span>
                            </a>
                            <a href="{{ route('client.profile') }}" class="group flex items-center rounded-lg px-4 py-3 text-sm font-medium transition-all duration-200 {{ request()->routeIs('client.profile') ? 'bg-primary-100 text-primary-700' : 'text-gray-700 hover:bg-primary-50 hover:text-primary-700' }}">
                                <i class="fas fa-user-circle mr-3 text-lg {{ request()->routeIs('client.profile') ? 'text-primary-600' : 'text-gray-500 group-hover:text-primary-600' }}"></i>
                                <span>My Profile</span>
                            </a>
                        </div>
                    </nav>
                </div>

                <!-- Main Content -->
                <div class="flex-1 bg-gray-50">
                    <main class="animate-fade-in p-6">
                        @if (isset($header))
                            <h1 class="mb-6 text-2xl font-bold text-gray-900">{{ $header }}</h1>
                        @endif

                        @if (session('success'))
                            <div class="mb-4 flex items-center rounded-lg bg-green-100 p-4 text-green-800 animate-slide-in">
                                <i class="fas fa-check-circle mr-3 text-green-500"></i>
                                {{ session('success') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="mb-4 flex items-center rounded-lg bg-red-100 p-4 text-red-800 animate-slide-in">
                                <i class="fas fa-exclamation-circle mr-3 text-red-500"></i>
                                {{ session('error') }}
                            </div>
                        @endif

                        @if (session('info'))
                            <div class="mb-4 flex items-center rounded-lg bg-primary-100 p-4 text-primary-800 animate-slide-in">
                                <i class="fas fa-info-circle mr-3 text-primary-500"></i>
                                {{ session('info') }}
                            </div>
                        @endif

                        <div class="animate-scale-in">
                            {{ $slot }}
                        </div>
                    </main>
                </div>
            </div>
        </div>
    </body>
</html>