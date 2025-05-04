<x-layout-writer>
    <x-slot:header>Writer Earnings</x-slot:header>
    
    @if (session('success'))
        <div class="mb-4 rounded-lg bg-green-50 p-4 text-sm text-green-800 dark:bg-green-900 dark:text-green-300" role="alert">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-800 dark:bg-red-900 dark:text-red-300" role="alert">
            {{ session('error') }}
        </div>
    @endif
    
    <!-- Earnings Summary -->
    <div class="mb-6 grid gap-6 sm:grid-cols-2">
        <!-- Total Earnings -->
        <div class="rounded-lg border border-gray-200 bg-white p-6 shadow dark:border-gray-700 dark:bg-zinc-900">
            <div class="flex items-center">
                <div class="mr-4 inline-flex h-14 w-14 flex-shrink-0 items-center justify-center rounded-lg bg-green-100 dark:bg-green-900">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-green-600 dark:text-green-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Earnings</p>
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white">${{ number_format($total_earnings, 2) }}</h2>
                </div>
            </div>
        </div>
        
        <!-- Pending Amount -->
        <div class="rounded-lg border border-gray-200 bg-white p-6 shadow dark:border-gray-700 dark:bg-zinc-900">
            <div class="flex items-center">
                <div class="mr-4 inline-flex h-14 w-14 flex-shrink-0 items-center justify-center rounded-lg bg-amber-100 dark:bg-amber-900">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-amber-600 dark:text-amber-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pending Amount</p>
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white">${{ number_format($pending_amount, 2) }}</h2>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Pending Payments -->
    <div class="mb-6 rounded-lg border border-gray-200 bg-white p-6 shadow dark:border-gray-700 dark:bg-zinc-900">
        <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Pending Payments</h3>
        
        @if(count($pending_payments) > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-4 py-3">Payment ID</th>
                            <th scope="col" class="px-4 py-3">Order ID</th>
                            <th scope="col" class="px-4 py-3">Order Title</th>
                            <th scope="col" class="px-4 py-3">Amount</th>
                            <th scope="col" class="px-4 py-3">Created Date</th>
                            <th scope="col" class="px-4 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pending_payments as $payment)
                            <tr class="border-b dark:border-gray-700">
                                <td class="whitespace-nowrap px-4 py-3">{{ $payment->id }}</td>
                                <td class="whitespace-nowrap px-4 py-3">
                                    @if($payment->order)
                                        <a href="{{ route('writer.assignments.show', $payment->order) }}" class="font-medium text-blue-600 hover:underline dark:text-blue-400">
                                            {{ $payment->order->order_number }}
                                        </a>
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td class="px-4 py-3">{{ $payment->order->title ?? 'N/A' }}</td>
                                <td class="whitespace-nowrap px-4 py-3 font-medium">${{ number_format($payment->amount, 2) }}</td>
                                <td class="whitespace-nowrap px-4 py-3">{{ $payment->created_at->format('M d, Y') }}</td>
                                <td class="px-4 py-3">
                                    <span class="rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-medium text-amber-800 dark:bg-amber-900 dark:text-amber-300">
                                        Pending
                                    </span>
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
            <div class="rounded-lg border border-dashed border-gray-300 bg-gray-50 p-6 text-center dark:border-gray-600 dark:bg-gray-800">
                <p class="text-gray-500 dark:text-gray-400">No pending payments found</p>
            </div>
        @endif
    </div>
    
    <!-- Processed Payments -->
    <div class="rounded-lg border border-gray-200 bg-white p-6 shadow dark:border-gray-700 dark:bg-zinc-900">
        <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Processed Payments</h3>
        
        @if(count($processed_payments) > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-4 py-3">Payment ID</th>
                            <th scope="col" class="px-4 py-3">Order ID</th>
                            <th scope="col" class="px-4 py-3">Order Title</th>
                            <th scope="col" class="px-4 py-3">Amount</th>
                            <th scope="col" class="px-4 py-3">Processing Date</th>
                            <th scope="col" class="px-4 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($processed_payments as $payment)
                            <tr class="border-b dark:border-gray-700">
                                <td class="whitespace-nowrap px-4 py-3">{{ $payment->id }}</td>
                                <td class="whitespace-nowrap px-4 py-3">
                                    @if($payment->order)
                                        <a href="{{ route('writer.assignments.show', $payment->order) }}" class="font-medium text-blue-600 hover:underline dark:text-blue-400">
                                            {{ $payment->order->order_number }}
                                        </a>
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td class="px-4 py-3">{{ $payment->order->title ?? 'N/A' }}</td>
                                <td class="whitespace-nowrap px-4 py-3 font-medium">${{ number_format($payment->amount, 2) }}</td>
                                <td class="whitespace-nowrap px-4 py-3">{{ $payment->updated_at->format('M d, Y') }}</td>
                                <td class="px-4 py-3">
                                    <span class="rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900 dark:text-green-300">
                                        Processed
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $processed_payments->links() }}
            </div>
        @else
            <div class="rounded-lg border border-dashed border-gray-300 bg-gray-50 p-6 text-center dark:border-gray-600 dark:bg-gray-800">
                <p class="text-gray-500 dark:text-gray-400">No processed payments found</p>
            </div>
        @endif
    </div>
</x-layout-writer> 