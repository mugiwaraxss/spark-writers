<x-layout-writer>
    <x-slot:header>Available Orders</x-slot:header>
    
    <div class="mb-6">
        <div class="rounded-lg border border-gray-200 bg-white p-6 shadow dark:border-gray-700 dark:bg-zinc-900">
            <div class="mb-6 flex flex-col justify-between gap-4 md:flex-row md:items-center">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Orders Available for Claiming</h3>
                <div class="flex gap-4">
                    <a href="{{ route('writer.orders') }}" class="flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:hover:border-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-700">
                        <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        My Orders
                    </a>
                </div>
            </div>
            
            <div class="mb-6">
                <div class="flex rounded-lg border border-blue-300 bg-blue-50 p-4 text-sm text-blue-800 dark:border-blue-800 dark:bg-gray-800 dark:text-blue-400" role="alert">
                    <div class="flex-shrink-0">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-600 dark:text-blue-500">Information</h3>
                        <div class="mt-2 text-sm text-blue-600 dark:text-blue-400">
                            <p>You can view and claim any available order that you feel confident in completing.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            @if(count($available_orders) > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-4 py-3">Order ID</th>
                                <th scope="col" class="px-4 py-3">Title</th>
                                <th scope="col" class="px-4 py-3">Subject</th>
                                <th scope="col" class="px-4 py-3">Pages</th>
                                <th scope="col" class="px-4 py-3">Deadline</th>
                                <th scope="col" class="px-4 py-3">Payment</th>
                                <th scope="col" class="px-4 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($available_orders as $order)
                                <tr class="border-b dark:border-gray-700">
                                    <td class="whitespace-nowrap px-4 py-3">{{ $order->order_number ?? $order->id }}</td>
                                    <td class="px-4 py-3">{{ Str::limit($order->title, 30) }}</td>
                                    <td class="px-4 py-3">{{ ucfirst(str_replace('_', ' ', $order->subject_area)) }}</td>
                                    <td class="px-4 py-3">{{ $order->page_count ?? '-' }}</td>
                                    <td class="whitespace-nowrap px-4 py-3">{{ $order->deadline->format('M d, Y g:i A') }}</td>
                                    <td class="whitespace-nowrap px-4 py-3">${{ number_format($order->writer_payment ?? 0, 2) }}</td>
                                    <td class="whitespace-nowrap px-4 py-3">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('writer.orders.view', $order) }}" class="font-medium text-blue-600 hover:underline dark:text-blue-400">View</a>
                                            <form action="{{ route('writer.orders.claim', $order) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="font-medium text-green-600 hover:underline dark:text-green-400">Claim</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4">
                    {{ $available_orders->links() }}
                </div>
            @else
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-6 text-center dark:border-gray-700 dark:bg-gray-800">
                    <p class="text-gray-500 dark:text-gray-400">No orders are available at the moment.</p>
                </div>
            @endif
        </div>
    </div>
</x-layout-writer> 