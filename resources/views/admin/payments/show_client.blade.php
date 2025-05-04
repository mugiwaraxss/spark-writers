<x-layout-admin>
    <x-slot:header>Payment Details</x-slot:header>
    
    <div class="mb-6">
        <div class="rounded-lg border border-gray-200 bg-white p-6 shadow dark:border-gray-700 dark:bg-zinc-900">
            <div class="mb-6 flex flex-col justify-between gap-4 md:flex-row md:items-center">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Payment #{{ $payment->id }}
                    <span class="ml-2 text-sm font-normal text-gray-500">
                        ({{ ucfirst($payment->status) }})
                    </span>
                </h3>
                <div class="flex gap-4">
                    <a href="{{ route('admin.payments.client') }}" class="flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:hover:border-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-700">
                        <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to Payments
                    </a>
                    @if($payment->status === 'completed')
                        <button type="button" data-modal-target="refundModal" data-modal-toggle="refundModal" class="flex items-center rounded-lg border border-red-300 bg-white px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50 focus:outline-none focus:ring-4 focus:ring-red-100 dark:border-red-500 dark:bg-gray-800 dark:text-red-500 dark:hover:bg-gray-700">
                            <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                            </svg>
                            Issue Refund
                        </button>
                    @endif
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <!-- Payment Information -->
                <div class="rounded-lg border border-gray-200 p-6 dark:border-gray-700">
                    <h4 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Payment Information</h4>
                    <dl class="grid grid-cols-1 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Amount</dt>
                            <dd class="text-lg font-semibold text-gray-900 dark:text-white">${{ number_format($payment->amount, 2) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                            <dd>
                                @if($payment->status === 'completed')
                                    <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900 dark:text-green-300">
                                        Completed
                                    </span>
                                @elseif($payment->status === 'pending')
                                    <span class="inline-flex items-center rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">
                                        Pending
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800 dark:bg-red-900 dark:text-red-300">
                                        Refunded
                                    </span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Payment Method</dt>
                            <dd class="font-medium text-gray-900 dark:text-white">{{ $payment->payment_method ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Transaction ID</dt>
                            <dd class="font-medium text-gray-900 dark:text-white">{{ $payment->transaction_id ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Payment Date</dt>
                            <dd class="font-medium text-gray-900 dark:text-white">
                                {{ $payment->payment_date ? $payment->payment_date->format('M d, Y g:i A') : 'N/A' }}
                            </dd>
                        </div>
                    </dl>
                </div>

                <!-- Order Information -->
                <div class="rounded-lg border border-gray-200 p-6 dark:border-gray-700">
                    <h4 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Order Information</h4>
                    <dl class="grid grid-cols-1 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Order ID</dt>
                            <dd class="font-medium text-gray-900 dark:text-white">
                                <a href="{{ route('admin.orders.show', $payment->order) }}" class="text-blue-600 hover:underline dark:text-blue-500">
                                    #{{ $payment->order->order_number ?? $payment->order->id }}
                                </a>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Client</dt>
                            <dd class="font-medium text-gray-900 dark:text-white">{{ $payment->order->client->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Order Title</dt>
                            <dd class="font-medium text-gray-900 dark:text-white">{{ $payment->order->title }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Order Status</dt>
                            <dd class="font-medium text-gray-900 dark:text-white">{{ ucfirst($payment->order->status) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Created Date</dt>
                            <dd class="font-medium text-gray-900 dark:text-white">
                                {{ $payment->created_at->format('M d, Y g:i A') }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <!-- Refund Modal -->
    @if($payment->status === 'completed')
        <div id="refundModal" tabindex="-1" aria-hidden="true" class="fixed left-0 right-0 top-0 z-50 hidden h-[calc(100%-1rem)] max-h-full w-full overflow-y-auto overflow-x-hidden p-4 md:inset-0">
            <div class="relative max-h-full w-full max-w-md">
                <div class="relative rounded-lg bg-white shadow dark:bg-gray-700">
                    <div class="flex items-start justify-between rounded-t border-b p-4 dark:border-gray-600">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                            Issue Refund
                        </h3>
                        <button type="button" class="ml-auto inline-flex h-8 w-8 items-center justify-center rounded-lg bg-transparent text-sm text-gray-400 hover:bg-gray-200 hover:text-gray-900 dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="refundModal">
                            <svg class="h-3 w-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <form action="{{ route('admin.payments.client.refund', $payment) }}" method="POST">
                        @csrf
                        <div class="p-6">
                            <p class="mb-4 text-sm text-gray-500 dark:text-gray-400">
                                You are about to issue a refund for ${{ number_format($payment->amount, 2) }}. This action cannot be undone.
                            </p>
                            <div>
                                <label for="refund_reason" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Refund Reason</label>
                                <textarea id="refund_reason" name="refund_reason" rows="3" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500" required></textarea>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2 rounded-b border-t border-gray-200 p-6 dark:border-gray-600">
                            <button type="submit" class="rounded-lg bg-red-700 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">Issue Refund</button>
                            <button type="button" class="rounded-lg border border-gray-200 bg-white px-5 py-2.5 text-sm font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-900 focus:z-10 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:border-gray-500 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="refundModal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</x-layout-admin> 