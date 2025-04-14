<x-client-layout>
    <x-slot:header>Client Dashboard</x-slot:header>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Order Stats Card -->
        <div class="bg-white p-6 rounded-lg shadow-md dark:bg-gray-800">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Orders</h3>
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Active Orders</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ count($active_orders) }}</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Payments Card -->
        <div class="bg-white p-6 rounded-lg shadow-md dark:bg-gray-800">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Payments</h3>
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Total Spent</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">${{ $payments['total_spent'] ?? 0 }}</p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Completed Orders Card -->
        <div class="bg-white p-6 rounded-lg shadow-md dark:bg-gray-800">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Completed Orders</h3>
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Total Completed</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ count($completed_orders) }}</p>
                </div>
                <div class="bg-purple-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Pending Payments Card -->
        <div class="bg-white p-6 rounded-lg shadow-md dark:bg-gray-800">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Pending Payments</h3>
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Awaiting Payment</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $payments['pending_payments'] ?? 0 }}</p>
                </div>
                <div class="bg-yellow-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <a href="{{ route('client.orders.create') }}" class="flex items-center rounded-lg border border-gray-200 bg-white p-4 shadow-sm transition hover:bg-gray-50 dark:border-gray-700 dark:bg-zinc-800 dark:hover:bg-zinc-700">
            <div class="mr-4 rounded-full bg-blue-100 p-2">
                <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
            </div>
            <div>
                <h4 class="text-sm font-medium text-gray-900 dark:text-white">New Order</h4>
                <p class="text-sm text-gray-500 dark:text-gray-400">Create a new writing order</p>
            </div>
        </a>
        
        <a href="{{ route('client.orders') }}" class="flex items-center rounded-lg border border-gray-200 bg-white p-4 shadow-sm transition hover:bg-gray-50 dark:border-gray-700 dark:bg-zinc-800 dark:hover:bg-zinc-700">
            <div class="mr-4 rounded-full bg-indigo-100 p-2">
                <svg class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
            </div>
            <div>
                <h4 class="text-sm font-medium text-gray-900 dark:text-white">My Orders</h4>
                <p class="text-sm text-gray-500 dark:text-gray-400">View all your orders</p>
            </div>
        </a>
        
        <a href="{{ route('client.payments') }}" class="flex items-center rounded-lg border border-gray-200 bg-white p-4 shadow-sm transition hover:bg-gray-50 dark:border-gray-700 dark:bg-zinc-800 dark:hover:bg-zinc-700">
            <div class="mr-4 rounded-full bg-green-100 p-2">
                <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <h4 class="text-sm font-medium text-gray-900 dark:text-white">Payment History</h4>
                <p class="text-sm text-gray-500 dark:text-gray-400">View all your payments</p>
            </div>
        </a>
        
        <a href="{{ route('client.profile') }}" class="flex items-center rounded-lg border border-gray-200 bg-white p-4 shadow-sm transition hover:bg-gray-50 dark:border-gray-700 dark:bg-zinc-800 dark:hover:bg-zinc-700">
            <div class="mr-4 rounded-full bg-purple-100 p-2">
                <svg class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </div>
            <div>
                <h4 class="text-sm font-medium text-gray-900 dark:text-white">My Profile</h4>
                <p class="text-sm text-gray-500 dark:text-gray-400">Update your profile</p>
            </div>
        </a>
    </div>
    
    <!-- Recent Orders Section -->
    <div class="bg-white p-6 rounded-lg shadow-md dark:bg-gray-800 mb-8">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Recent Orders</h3>
            <a href="{{ route('client.orders') }}" class="text-sm font-medium text-blue-600 hover:underline dark:text-blue-400">View All</a>
        </div>
        
        @if(count($active_orders) > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Order</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Title</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Deadline</th>
                            <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                        @foreach($active_orders as $order)
                            <tr>
                                <td class="px-4 py-3 whitespace-nowrap">#{{ $order->id }}</td>
                                <td class="px-4 py-3">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $order->title }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $order->academic_level }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $order->status === 'pending' ? 'yellow' : ($order->status === 'in_progress' ? 'blue' : 'green') }}-100 text-{{ $order->status === 'pending' ? 'yellow' : ($order->status === 'in_progress' ? 'blue' : 'green') }}-800">
                                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ \Carbon\Carbon::parse($order->deadline)->format('M d, Y') }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('client.orders.show', $order) }}" class="font-medium text-blue-600 hover:underline dark:text-blue-400">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="py-8 text-center">
                <p class="text-gray-500 dark:text-gray-400">You don't have any active orders.</p>
                <a href="{{ route('client.orders.create') }}" class="rounded-lg bg-blue-600 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-700 dark:hover:bg-blue-800 dark:focus:ring-blue-800">Create Order</a>
            </div>
        @endif
    </div>
</x-client-layout> 