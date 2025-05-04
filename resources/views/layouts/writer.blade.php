<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
        <title>{{ config('app.name', 'Laravel') }} - Writer {{ $title ?? '' }}</title>
    </head>
    <body class="min-h-screen bg-white">
        <div class="min-h-screen">
            <!-- Top Navigation Bar -->
            <nav class="bg-white shadow">
                <div class="px-4">
                    <div class="flex h-16 items-center justify-between">
                        <div class="flex items-center">
                            <a href="{{ route('writer.dashboard') }}" class="flex items-center space-x-2">
                                <x-app-logo class="h-8 w-8" />
                                <span class="text-xl font-semibold text-gray-900">Writer Portal</span>
                            </a>
                        </div>

                        <div class="flex items-center space-x-4">
                            <a href="{{ route('writer.messages.index') }}" class="flex items-center">
                                <span class="relative mr-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                    </svg>
                                    @php
                                        $unreadMessageCount = Auth::user()->notifications()
                                            ->where('read_status', false)
                                            ->where(function($query) {
                                                $query->where('type', 'message')
                                                    ->orWhere('type', 'direct_message');
                                            })
                                            ->count();
                                    @endphp
                                    
                                    @if($unreadMessageCount > 0)
                                        <span class="absolute -right-1 -top-1 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-xs text-white">
                                            {{ $unreadMessageCount > 9 ? '9+' : $unreadMessageCount }}
                                        </span>
                                    @endif
                                </span>
                                <span class="text-sm text-gray-700">Messages</span>
                            </a>
                            <span class="text-sm text-gray-700">{{ auth()->user()->name }}</span>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
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
                <div class="hidden w-64 flex-shrink-0 bg-gray-50 lg:block">
                    <nav class="flex h-full flex-col">
                        <div class="space-y-1 p-4">
                            <a href="{{ route('writer.dashboard') }}" class="flex items-center rounded-lg px-4 py-2 text-sm font-medium {{ request()->routeIs('writer.dashboard') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }}">
                                <span>Dashboard</span>
                            </a>
                            <a href="{{ route('writer.assignments.index') }}" class="flex items-center rounded-lg px-4 py-2 text-sm font-medium {{ request()->routeIs('writer.assignments.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }}">
                                <span>My Assignments</span>
                            </a>
                            <a href="{{ route('writer.available-orders') }}" class="flex items-center rounded-lg px-4 py-2 text-sm font-medium {{ request()->routeIs('writer.available-orders') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }}">
                                <span>Available Orders</span>
                            </a>
                            <a href="{{ route('writer.messages.index') }}" class="flex items-center rounded-lg px-4 py-2 text-sm font-medium {{ request()->routeIs('writer.messages.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }}">
                                <span>Messages</span>
                                @if($unreadMessageCount > 0)
                                    <span class="ml-2 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-xs text-white">
                                        {{ $unreadMessageCount > 9 ? '9+' : $unreadMessageCount }}
                                    </span>
                                @endif
                            </a>
                            <a href="{{ route('writer.earnings') }}" class="flex items-center rounded-lg px-4 py-2 text-sm font-medium {{ request()->routeIs('writer.earnings') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }}">
                                <span>Earnings</span>
                            </a>
                        </div>
                    </nav>
                </div>

                <!-- Main Content -->
                <div class="flex-1 px-4 py-8 sm:px-6 lg:px-8">
                    <!-- Page Heading -->
                    @if(isset($header))
                        <header class="mb-6">
                            <h1 class="text-2xl font-bold text-gray-900">{{ $header }}</h1>
                        </header>
                    @endif

                    <!-- Flash Messages -->
                    @if (session('success'))
                        <div class="mb-6 rounded-md bg-green-50 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-green-800">
                                        {{ session('success') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-6 rounded-md bg-red-50 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-red-800">
                                        {{ session('error') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{ $slot }}
                </div>
            </div>
        </div>
        
        <!-- Include Flowbite JS for modals -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/flowbite.min.js"></script>
        
        <!-- Modal initialization script -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize all modals
                const modals = document.querySelectorAll('[data-modal-toggle]');
                modals.forEach(modalToggle => {
                    const modalTarget = document.getElementById(modalToggle.getAttribute('data-modal-target'));
                    if (modalTarget) {
                        modalToggle.addEventListener('click', function() {
                            modalTarget.classList.toggle('hidden');
                        });
                    }
                });
                
                // Initialize modal hide buttons
                const hideButtons = document.querySelectorAll('[data-modal-hide]');
                hideButtons.forEach(hideButton => {
                    const modalTarget = document.getElementById(hideButton.getAttribute('data-modal-hide'));
                    if (modalTarget) {
                        hideButton.addEventListener('click', function() {
                            modalTarget.classList.add('hidden');
                        });
                    }
                });
            });
        </script>
    </body>
</html> 