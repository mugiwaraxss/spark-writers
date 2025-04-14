<x-client-layout>
    <x-slot:header>Payment Successful</x-slot:header>

    <div class="mx-auto max-w-xl rounded-lg border border-gray-200 bg-white p-8 text-center shadow dark:border-gray-700 dark:bg-zinc-900">
        <div class="mb-6 flex justify-center">
            <div class="flex h-24 w-24 items-center justify-center rounded-full bg-green-100 dark:bg-green-900">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-green-600 dark:text-green-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
        </div>
        
        <h2 class="mb-2 text-2xl font-bold text-gray-900 dark:text-white">Payment Successful!</h2>
        <p class="mb-6 text-gray-600 dark:text-gray-400">
            Thank you for your payment. Your order has been confirmed and is now being processed.
        </p>
        
        <div class="mb-8 rounded-lg bg-gray-50 p-4 dark:bg-gray-800">
            <div class="mb-3 flex justify-between border-b border-gray-200 pb-3 dark:border-gray-700">
                <span class="font-medium text-gray-700 dark:text-gray-300">Order Number:</span>
                <span class="font-semibold text-gray-900 dark:text-white">{{ $order->order_number }}</span>
            </div>
            <div class="mb-3 flex justify-between border-b border-gray-200 pb-3 dark:border-gray-700">
                <span class="font-medium text-gray-700 dark:text-gray-300">Amount Paid:</span>
                <span class="font-semibold text-gray-900 dark:text-white">${{ number_format($payment->amount, 2) }}</span>
            </div>
            <div class="mb-3 flex justify-between border-b border-gray-200 pb-3 dark:border-gray-700">
                <span class="font-medium text-gray-700 dark:text-gray-300">Payment Method:</span>
                <span class="font-semibold text-gray-900 dark:text-white">{{ ucfirst($payment->payment_method) }}</span>
            </div>
            <div class="flex justify-between">
                <span class="font-medium text-gray-700 dark:text-gray-300">Date:</span>
                <span class="font-semibold text-gray-900 dark:text-white">{{ $payment->created_at->format('M d, Y g:i A') }}</span>
            </div>
        </div>
        
        <div class="flex flex-col space-y-4 sm:flex-row sm:space-x-4 sm:space-y-0">
            <a href="{{ route('client.orders.show', $order) }}" class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-700 dark:hover:bg-blue-800 dark:focus:ring-blue-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                View Order Details
            </a>
            <a href="{{ route('client.dashboard') }}" class="rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-center text-sm font-medium text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:hover:border-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-700">
                Return to Dashboard
            </a>
        </div>
    </div>
    
    <div class="mt-8 rounded-lg border border-gray-200 bg-white p-6 shadow dark:border-gray-700 dark:bg-zinc-900">
        <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">What Happens Next?</h3>
        
        <div class="space-y-4">
            <div class="flex">
                <div class="mr-4 flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 text-blue-600 dark:bg-blue-900 dark:text-blue-300">
                    1
                </div>
                <div>
                    <h4 class="text-base font-medium text-gray-900 dark:text-white">Writer Assignment</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Our system will match your order with a qualified writer who specializes in your subject area.
                    </p>
                </div>
            </div>
            
            <div class="flex">
                <div class="mr-4 flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 text-blue-600 dark:bg-blue-900 dark:text-blue-300">
                    2
                </div>
                <div>
                    <h4 class="text-base font-medium text-gray-900 dark:text-white">Work in Progress</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        The assigned writer will begin working on your order. You can track progress and communicate with the writer through our platform.
                    </p>
                </div>
            </div>
            
            <div class="flex">
                <div class="mr-4 flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 text-blue-600 dark:bg-blue-900 dark:text-blue-300">
                    3
                </div>
                <div>
                    <h4 class="text-base font-medium text-gray-900 dark:text-white">Quality Check</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Once completed, your order will go through our quality assurance process to ensure it meets all requirements.
                    </p>
                </div>
            </div>
            
            <div class="flex">
                <div class="mr-4 flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 text-blue-600 dark:bg-blue-900 dark:text-blue-300">
                    4
                </div>
                <div>
                    <h4 class="text-base font-medium text-gray-900 dark:text-white">Delivery</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        You'll be notified when your completed order is ready for download. You can request revisions if needed.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-client-layout> 