<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
        <title>{{ config('app.name', 'Laravel') }} - Admin {{ $title ?? '' }}</title>
    </head>
    <body class="min-h-screen bg-white">
        <div class="min-h-screen">
            <!-- Top Navigation Bar -->
            <nav class="bg-white shadow">
                <div class="px-4">
                    <div class="flex h-16 items-center justify-between">
                        <div class="flex items-center">
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2">
                                <x-app-logo class="h-8 w-8" />
                                <span class="text-xl font-semibold text-gray-900">Admin Portal</span>
                            </a>
                        </div>

                        <div class="flex items-center space-x-4">
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
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center rounded-lg px-4 py-2 text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }}">
                                <span>Dashboard</span>
                            </a>
                            <a href="{{ route('admin.orders.index') }}" class="flex items-center rounded-lg px-4 py-2 text-sm font-medium {{ request()->routeIs('admin.orders.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }}">
                                <span>Orders</span>
                            </a>
                            <a href="{{ route('admin.writers.index') }}" class="flex items-center rounded-lg px-4 py-2 text-sm font-medium {{ request()->routeIs('admin.writers.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }}">
                                <span>Writers</span>
                            </a>
                            <a href="{{ route('admin.payments.client') }}" class="flex items-center rounded-lg px-4 py-2 text-sm font-medium {{ request()->routeIs('admin.payments.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }}">
                                <span>Payments</span>
                            </a>
                            <a href="{{ route('admin.reports') }}" class="flex items-center rounded-lg px-4 py-2 text-sm font-medium {{ request()->routeIs('admin.reports') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }}">
                                <span>Reports</span>
                            </a>
                            <a href="{{ route('admin.disputes') }}" class="flex items-center rounded-lg px-4 py-2 text-sm font-medium {{ request()->routeIs('admin.disputes') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }}">
                                <span>Disputes</span>
                            </a>
                            <a href="{{ route('admin.messages.index') }}" class="flex items-center rounded-lg px-4 py-2 text-sm font-medium {{ request()->routeIs('admin.messages.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }}">
                                <span>Messages</span>
                            </a>
                            <a href="{{ route('admin.settings') }}" class="flex items-center rounded-lg px-4 py-2 text-sm font-medium {{ request()->routeIs('admin.settings') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }}">
                                <span>Settings</span>
                            </a>
                        </div>
                    </nav>
                </div>

                <!-- Main Content -->
                <div class="flex-1">
                    <main class="p-6">
                        @if (isset($header))
                            <h1 class="mb-6 text-2xl font-semibold text-gray-900">{{ $header }}</h1>
                        @endif

                        @if (session('success'))
                            <div class="mb-4 rounded-lg bg-green-100 p-4 text-green-800">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="mb-4 rounded-lg bg-red-100 p-4 text-red-800">
                                {{ session('error') }}
                            </div>
                        @endif

                        @if (session('info'))
                            <div class="mb-4 rounded-lg bg-blue-100 p-4 text-blue-800">
                                {{ session('info') }}
                            </div>
                        @endif

                        {{ $slot }}
                    </main>
                </div>
            </div>
        </div>
    </body>
</html> 