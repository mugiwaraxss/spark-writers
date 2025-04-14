<x-admin-layout title="Client Payments">
    <x-slot name="header">Client Payment Management</x-slot>

    <div class="rounded-lg bg-white p-6 shadow-sm dark:bg-gray-800">
        <!-- Filters -->
        <form action="{{ route('admin.payments.client') }}" method="GET" class="mb-6">
            <div class="grid gap-4 md:grid-cols-4">
                <div>
                    <label for="status" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Payment Status</label>
                    <select id="status" name="status" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500">
                        <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>Refunded</option>
                    </select>
                </div>
                
                <div>
                    <label for="date_from" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Date From</label>
                    <input type="date" id="date_from" name="date_from" value="{{ request('date_from') }}" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500">
                </div>
                
                <div>
                    <label for="date_to" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Date To</label>
                    <input type="date" id="date_to" name="date_to" value="{{ request('date_to') }}" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500">
                </div>
                
                <div class="flex items-end">
                    <button type="submit" class="inline-flex items-center rounded-lg bg-blue-600 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-700 dark:hover:bg-blue-800 dark:focus:ring-blue-800">
                        Filter
                    </button>
                </div>
            </div>
        </form>

        <!-- Summary -->
        <div class="mb-6 grid gap-4 md:grid-cols-3">
            <div class="rounded-lg bg-blue-50 p-4 dark:bg-blue-900">
                <h3 class="text-lg font-semibold text-blue-700 dark:text-blue-300">Total Payments</h3>
                <p class="mt-2 text-2xl font-bold text-blue-800 dark:text-blue-200">${{ number_format($payments->sum('amount'), 2) }}</p>
            </div>
            
            <div class="rounded-lg bg-green-50 p-4 dark:bg-green-900">
                <h3 class="text-lg font-semibold text-green-700 dark:text-green-300">Completed Payments</h3>
                <p class="mt-2 text-2xl font-bold text-green-800 dark:text-green-200">${{ number_format($payments->where('status', 'completed')->sum('amount'), 2) }}</p>
            </div>
            
            <div class="rounded-lg bg-red-50 p-4 dark:bg-red-900">
                <h3 class="text-lg font-semibold text-red-700 dark:text-red-300">Refunded Payments</h3>
                <p class="mt-2 text-2xl font-bold text-red-800 dark:text-red-200">${{ number_format($payments->where('status', 'refunded')->sum('amount'), 2) }}</p>
            </div>
        </div>

        <!-- Payments Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-4 py-3">ID</th>
                        <th scope="col" class="px-4 py-3">Order</th>
                        <th scope="col" class="px-4 py-3">Client</th>
                        <th scope="col" class="px-4 py-3">Amount</th>
                        <th scope="col" class="px-4 py-3">Payment Method</th>
                        <th scope="col" class="px-4 py-3">Date</th>
                        <th scope="col" class="px-4 py-3">Status</th>
                        <th scope="col" class="px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payments as $payment)
                    <tr class="border-b dark:border-gray-700">
                        <td class="whitespace-nowrap px-4 py-3">{{ $payment->id }}</td>
                        <td class="px-4 py-3">
                            @if($payment->order)
                                <a href="{{ route('admin.orders.show', $payment->order) }}" class="font-medium text-blue-600 hover:underline dark:text-blue-400">
                                    #{{ $payment->order->order_number ?? $payment->order->id }}
                                </a>
                            @else
                                <span class="text-gray-500 dark:text-gray-400">N/A</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if($payment->order && $payment->order->client)
                                {{ $payment->order->client->name }}
                            @else
                                <span class="text-gray-500 dark:text-gray-400">Unknown</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 font-medium">${{ number_format($payment->amount, 2) }}</td>
                        <td class="px-4 py-3">{{ ucfirst($payment->payment_method) }}</td>
                        <td class="whitespace-nowrap px-4 py-3">{{ $payment->created_at->format('M d, Y H:i') }}</td>
                        <td class="px-4 py-3">
                            <span class="rounded-full px-2.5 py-0.5 text-xs font-medium
                                @if($payment->status == 'completed') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                @elseif($payment->status == 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                                @elseif($payment->status == 'refunded') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                                @endif">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.payments.client.show', $payment) }}" class="font-medium text-blue-600 hover:underline dark:text-blue-400">
                                    View
                                </a>
                                
                                @if($payment->status == 'completed')
                                <button type="button" onclick="openRefundModal({{ $payment->id }})" class="font-medium text-red-600 hover:underline dark:text-red-400">
                                    Refund
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $payments->withQueryString()->links() }}
        </div>
    </div>

    <!-- Refund Modal -->
    <div id="refundModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex min-h-screen items-end justify-center px-4 pb-20 pt-4 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75 dark:bg-gray-900"></div>
            </div>
            
            <span class="hidden sm:inline-block sm:h-screen sm:align-middle" aria-hidden="true">&#8203;</span>
            
            <div class="inline-block transform overflow-hidden rounded-lg bg-white text-left align-bottom shadow-xl transition-all dark:bg-gray-800 sm:my-8 sm:w-full sm:max-w-lg sm:align-middle">
                <form id="refundForm" action="" method="POST">
                    @csrf
                    
                    <div class="bg-white px-4 pb-4 pt-5 dark:bg-gray-800 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 dark:bg-red-900 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">
                                    Refund Payment
                                </h3>
                                
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Please provide a reason for this refund. This will be communicated to the client.
                                    </p>
                                    
                                    <div class="mt-4">
                                        <label for="refund_reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Refund Reason</label>
                                        <textarea id="refund_reason" name="refund_reason" rows="3" class="mt-1 block w-full rounded-md border border-gray-300 p-2 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm" required></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 px-4 py-3 dark:bg-gray-700 sm:flex sm:flex-row-reverse sm:px-6">
                        <button type="submit" class="inline-flex w-full justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm">
                            Process Refund
                        </button>
                        <button type="button" onclick="closeRefundModal()" class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 sm:mt-0 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openRefundModal(paymentId) {
            const route = "{{ route('admin.payments.client.refund', ['payment' => ':id']) }}";
            document.getElementById('refundForm').action = route.replace(':id', paymentId);
            document.getElementById('refundModal').classList.remove('hidden');
        }
        
        function closeRefundModal() {
            document.getElementById('refundModal').classList.add('hidden');
        }
    </script>
</x-admin-layout> 