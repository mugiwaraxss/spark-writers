<x-client-layout>
    <x-slot:header>Payment History</x-slot:header>

    <div class="mb-6 grid grid-cols-1 gap-6 md:grid-cols-3">
        <!-- Total Spent Card -->
        <div class="rounded-lg border border-gray-200 bg-white p-6 shadow dark:border-gray-700 dark:bg-gray-800">
            <div class="flex items-center">
                <div class="mr-4 inline-flex h-12 w-12 items-center justify-center rounded-full bg-green-100 dark:bg-green-900">
                    <svg class="h-6 w-6 text-green-600 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Spent</p>
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">${{ number_format($total_spent, 2) }}</h2>
                </div>
            </div>
        </div>

        <!-- Completed Payments Card -->
        <div class="rounded-lg border border-gray-200 bg-white p-6 shadow dark:border-gray-700 dark:bg-gray-800">
            <div class="flex items-center">
                <div class="mr-4 inline-flex h-12 w-12 items-center justify-center rounded-full bg-purple-100 dark:bg-purple-900">
                    <svg class="h-6 w-6 text-purple-600 dark:text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Completed Payments</p>
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $completed_payments->total() }}</h2>
                </div>
            </div>
        </div>

        <!-- Pending Payments Card -->
        <div class="rounded-lg border border-gray-200 bg-white p-6 shadow dark:border-gray-700 dark:bg-gray-800">
            <div class="flex items-center">
                <div class="mr-4 inline-flex h-12 w-12 items-center justify-center rounded-full bg-yellow-100 dark:bg-yellow-900">
                    <svg class="h-6 w-6 text-yellow-600 dark:text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pending Payments</p>
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $pending_payments->total() }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Completed Payments Section -->
    <div class="mb-8">
        <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Completed Payments</h3>
        
        @if(count($completed_payments) > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-4 py-3">Payment ID</th>
                            <th scope="col" class="px-4 py-3">Order</th>
                            <th scope="col" class="px-4 py-3">Amount</th>
                            <th scope="col" class="px-4 py-3">Payment Method</th>
                            <th scope="col" class="px-4 py-3">Date</th>
                            <th scope="col" class="px-4 py-3">Transaction ID</th>
                            <th scope="col" class="px-4 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($completed_payments as $payment)
                            <tr class="border-b dark:border-gray-700">
                                <td class="px-4 py-3">#{{ $payment->id }}</td>
                                <td class="px-4 py-3">
                                    <a href="{{ route('client.orders.show', $payment->order) }}" class="font-medium text-blue-600 hover:underline dark:text-blue-400">
                                        Order #{{ $payment->order->id }}
                                    </a>
                                </td>
                                <td class="px-4 py-3">${{ number_format($payment->amount, 2) }}</td>
                                <td class="px-4 py-3">{{ ucfirst($payment->payment_method ?? 'N/A') }}</td>
                                <td class="px-4 py-3">{{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') : 'N/A' }}</td>
                                <td class="px-4 py-3">{{ $payment->transaction_id ?? 'N/A' }}</td>
                                <td class="px-4 py-3">
                                    <a href="{{ route('client.orders.show', $payment->order) }}" class="font-medium text-blue-600 hover:underline dark:text-blue-400">View Order</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                {{ $completed_payments->links() }}
            </div>
        @else
            <div class="flex flex-col items-center justify-center rounded-lg border border-dashed border-gray-300 bg-gray-50 py-8 text-center dark:border-gray-600 dark:bg-gray-800">
                <svg class="mb-3 h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="mb-2 text-lg font-medium text-gray-900 dark:text-white">No completed payments</h3>
                <p class="text-gray-500 dark:text-gray-400">You haven't made any payments yet.</p>
            </div>
        @endif
    </div>

    <!-- Pending Payments Section -->
    <div>
        <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Pending Payments</h3>
        
        @if(count($pending_payments) > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-4 py-3">Payment ID</th>
                            <th scope="col" class="px-4 py-3">Order</th>
                            <th scope="col" class="px-4 py-3">Amount</th>
                            <th scope="col" class="px-4 py-3">Created</th>
                            <th scope="col" class="px-4 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pending_payments as $payment)
                            <tr class="border-b dark:border-gray-700">
                                <td class="px-4 py-3">#{{ $payment->id }}</td>
                                <td class="px-4 py-3">
                                    <a href="{{ route('client.orders.show', $payment->order) }}" class="font-medium text-blue-600 hover:underline dark:text-blue-400">
                                        Order #{{ $payment->order->id }}
                                    </a>
                                </td>
                                <td class="px-4 py-3">${{ number_format($payment->amount, 2) }}</td>
                                <td class="px-4 py-3">{{ $payment->created_at->format('M d, Y') }}</td>
                                <td class="px-4 py-3">
                                    <a href="{{ route('client.payments.pay', $payment->order) }}" class="rounded bg-blue-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-blue-700">Pay Now</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                {{ $pending_payments->links() }}
            </div>
        @else
            <div class="flex flex-col items-center justify-center rounded-lg border border-dashed border-gray-300 bg-gray-50 py-8 text-center dark:border-gray-600 dark:bg-gray-800">
                <svg class="mb-3 h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <h3 class="mb-2 text-lg font-medium text-gray-900 dark:text-white">No pending payments</h3>
                <p class="text-gray-500 dark:text-gray-400">You don't have any pending payments at the moment.</p>
            </div>
        @endif
    </div>
</x-client-layout> 