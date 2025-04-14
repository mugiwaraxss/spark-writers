<x-layout-writer>
    <x-slot:header>My Orders</x-slot:header>
    
    <div class="mb-6">
        <div class="rounded-lg border border-gray-200 bg-white p-6 shadow dark:border-gray-700 dark:bg-zinc-900">
            <div class="mb-6 flex flex-col justify-between gap-4 md:flex-row md:items-center">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Order Management</h3>
                <div class="flex gap-4">
                    <a href="{{ route('writer.assignments.index') }}" class="flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:hover:border-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-700">
                        <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Assignments
                    </a>
                    <a href="{{ route('writer.available-orders') }}" class="flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:hover:border-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-700">
                        <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Available Orders
                    </a>
                </div>
            </div>
            
            <!-- Tabs -->
            <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
                <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="ordersTab" role="tablist">
                    <li class="mr-2" role="presentation">
                        <button class="inline-block p-4 border-b-2 rounded-t-lg active" id="active-tab" data-tabs-target="#active" type="button" role="tab" aria-controls="active" aria-selected="true">
                            Active Orders
                        </button>
                    </li>
                    <li class="mr-2" role="presentation">
                        <button class="inline-block p-4 border-b-2 rounded-t-lg" id="completed-tab" data-tabs-target="#completed" type="button" role="tab" aria-controls="completed" aria-selected="false">
                            Completed Orders
                        </button>
                    </li>
                </ul>
            </div>
            
            <div id="ordersTabContent">
                <!-- Active Orders Tab -->
                <div class="block" id="active" role="tabpanel" aria-labelledby="active-tab">
                    @if($active_orders->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm">
                                <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-4 py-3">ID</th>
                                        <th scope="col" class="px-4 py-3">Title</th>
                                        <th scope="col" class="px-4 py-3">Client</th>
                                        <th scope="col" class="px-4 py-3">Status</th>
                                        <th scope="col" class="px-4 py-3">Deadline</th>
                                        <th scope="col" class="px-4 py-3">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($active_orders as $order)
                                        <tr class="border-b dark:border-gray-700">
                                            <td class="whitespace-nowrap px-4 py-3 font-medium">{{ $order->order_number ?? $order->id }}</td>
                                            <td class="px-4 py-3">{{ Str::limit($order->title, 30) }}</td>
                                            <td class="px-4 py-3">{{ $order->client->name }}</td>
                                            <td class="px-4 py-3">
                                                <span class="rounded-full px-2.5 py-0.5 text-xs font-medium 
                                                    @if($order->status == 'in_progress') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300
                                                    @elseif($order->status == 'assigned') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                                                    @elseif($order->status == 'revision') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300
                                                    @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                                                    @endif">
                                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                                </span>
                                            </td>
                                            <td class="whitespace-nowrap px-4 py-3">{{ $order->deadline->format('M d, Y g:i A') }}</td>
                                            <td class="whitespace-nowrap px-4 py-3">
                                                <a href="{{ route('writer.assignments.show', $order) }}" class="font-medium text-blue-600 hover:underline dark:text-blue-500">View Details</a>
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
                        <div class="rounded-lg border border-gray-200 bg-gray-50 p-6 text-center dark:border-gray-700 dark:bg-gray-800">
                            <p class="text-gray-500 dark:text-gray-400">You don't have any active orders at the moment.</p>
                            <a href="{{ route('writer.available-orders') }}" class="mt-4 inline-flex items-center rounded-lg bg-blue-600 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                Browse Available Orders
                            </a>
                        </div>
                    @endif
                </div>
                
                <!-- Completed Orders Tab -->
                <div class="hidden" id="completed" role="tabpanel" aria-labelledby="completed-tab">
                    @if($completed_orders->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm">
                                <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-4 py-3">ID</th>
                                        <th scope="col" class="px-4 py-3">Title</th>
                                        <th scope="col" class="px-4 py-3">Client</th>
                                        <th scope="col" class="px-4 py-3">Completed On</th>
                                        <th scope="col" class="px-4 py-3">Payment</th>
                                        <th scope="col" class="px-4 py-3">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($completed_orders as $order)
                                        <tr class="border-b dark:border-gray-700">
                                            <td class="whitespace-nowrap px-4 py-3 font-medium">{{ $order->order_number ?? $order->id }}</td>
                                            <td class="px-4 py-3">{{ Str::limit($order->title, 30) }}</td>
                                            <td class="px-4 py-3">{{ $order->client->name }}</td>
                                            <td class="whitespace-nowrap px-4 py-3">{{ $order->updated_at->format('M d, Y') }}</td>
                                            <td class="whitespace-nowrap px-4 py-3">${{ number_format($order->writer_payment ?? 0, 2) }}</td>
                                            <td class="whitespace-nowrap px-4 py-3">
                                                <a href="{{ route('writer.assignments.show', $order) }}" class="font-medium text-blue-600 hover:underline dark:text-blue-500">View Details</a>
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
                        <div class="rounded-lg border border-gray-200 bg-gray-50 p-6 text-center dark:border-gray-700 dark:bg-gray-800">
                            <p class="text-gray-500 dark:text-gray-400">You haven't completed any orders yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    @push('scripts')
    <script>
        // Simple tab functionality
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('[data-tabs-target]');
            const tabContents = document.querySelectorAll('[role="tabpanel"]');
            
            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    const target = document.querySelector(this.dataset.tabsTarget);
                    
                    tabContents.forEach(content => {
                        content.classList.add('hidden');
                        content.classList.remove('block');
                    });
                    
                    tabs.forEach(t => {
                        t.classList.remove('active', 'border-blue-600', 'text-blue-600');
                        t.setAttribute('aria-selected', false);
                    });
                    
                    this.classList.add('active', 'border-blue-600', 'text-blue-600');
                    this.setAttribute('aria-selected', true);
                    
                    target.classList.remove('hidden');
                    target.classList.add('block');
                });
            });
        });
    </script>
    @endpush
</x-layout-writer> 