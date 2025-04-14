<x-client-layout>
    <x-slot:header>My Orders</x-slot:header>

    <div class="mb-8">
        <a href="{{ route('client.orders.create') }}" class="inline-flex items-center rounded-lg bg-blue-600 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-700 dark:hover:bg-blue-800 dark:focus:ring-blue-800">
            <svg class="mr-2 -ml-1 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Create New Order
        </a>
    </div>

    <!-- Active Orders -->
    <div class="mb-8">
        <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Active Orders</h3>
        
        @if(count($active_orders) > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-4 py-3">Order ID</th>
                            <th scope="col" class="px-4 py-3">Title</th>
                            <th scope="col" class="px-4 py-3">Academic Level</th>
                            <th scope="col" class="px-4 py-3">Deadline</th>
                            <th scope="col" class="px-4 py-3">Status</th>
                            <th scope="col" class="px-4 py-3">Price</th>
                            <th scope="col" class="px-4 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($active_orders as $order)
                            <tr class="border-b dark:border-gray-700">
                                <td class="px-4 py-3">#{{ $order->id }}</td>
                                <td class="px-4 py-3">{{ \Illuminate\Support\Str::limit($order->title, 30) }}</td>
                                <td class="px-4 py-3">{{ $order->academic_level }}</td>
                                <td class="px-4 py-3">{{ \Carbon\Carbon::parse($order->deadline)->format('M d, Y') }}</td>
                                <td class="px-4 py-3">
                                    <span class="rounded-full px-2 py-1 text-xs font-medium 
                                        @if($order->status == 'completed') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                        @elseif($order->status == 'in_progress') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300
                                        @elseif($order->status == 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                                        @elseif($order->status == 'revision') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300
                                        @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">${{ number_format($order->price, 2) }}</td>
                                <td class="px-4 py-3">
                                    <a href="{{ route('client.orders.show', $order) }}" class="font-medium text-blue-600 hover:underline dark:text-blue-400">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                {{ $active_orders->links() }}
            </div>
        @else
            <div class="flex flex-col items-center justify-center rounded-lg border border-dashed border-gray-300 bg-gray-50 py-8 text-center dark:border-gray-600 dark:bg-gray-800">
                <svg class="mb-3 h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="mb-2 text-lg font-medium text-gray-900 dark:text-white">No active orders</h3>
                <p class="mb-4 text-gray-500 dark:text-gray-400">You don't have any active orders at the moment.</p>
                <a href="{{ route('client.orders.create') }}" class="rounded-lg bg-blue-600 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-700 dark:hover:bg-blue-800">
                    Create New Order
                </a>
            </div>
        @endif
    </div>

    <!-- Completed Orders -->
    <div>
        <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Completed Orders</h3>
        
        @if(count($completed_orders) > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-4 py-3">Order ID</th>
                            <th scope="col" class="px-4 py-3">Title</th>
                            <th scope="col" class="px-4 py-3">Academic Level</th>
                            <th scope="col" class="px-4 py-3">Completion Date</th>
                            <th scope="col" class="px-4 py-3">Price</th>
                            <th scope="col" class="px-4 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($completed_orders as $order)
                            <tr class="border-b dark:border-gray-700">
                                <td class="px-4 py-3">#{{ $order->id }}</td>
                                <td class="px-4 py-3">{{ \Illuminate\Support\Str::limit($order->title, 30) }}</td>
                                <td class="px-4 py-3">{{ $order->academic_level }}</td>
                                <td class="px-4 py-3">{{ $order->updated_at->format('M d, Y') }}</td>
                                <td class="px-4 py-3">${{ number_format($order->price, 2) }}</td>
                                <td class="px-4 py-3">
                                    <a href="{{ route('client.orders.show', $order) }}" class="font-medium text-blue-600 hover:underline dark:text-blue-400">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                {{ $completed_orders->links() }}
            </div>
        @else
            <div class="flex flex-col items-center justify-center rounded-lg border border-dashed border-gray-300 bg-gray-50 py-8 text-center dark:border-gray-600 dark:bg-gray-800">
                <svg class="mb-3 h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                </svg>
                <h3 class="mb-2 text-lg font-medium text-gray-900 dark:text-white">No completed orders</h3>
                <p class="text-gray-500 dark:text-gray-400">Your completed orders will appear here.</p>
            </div>
        @endif
    </div>
</x-client-layout> 