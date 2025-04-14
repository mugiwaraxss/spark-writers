<x-admin-layout title="Writer Payments">
    <x-slot name="header">Writer Payment Management</x-slot>

    <div class="rounded-lg bg-white p-6 shadow-sm dark:bg-gray-800">
        <!-- Filters -->
        <form action="{{ route('admin.payments.writer') }}" method="GET" class="mb-6">
            <div class="grid gap-4 md:grid-cols-4">
                <div>
                    <label for="status" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Payment Status</label>
                    <select id="status" name="status" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500">
                        <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="processed" {{ request('status') == 'processed' ? 'selected' : '' }}>Processed</option>
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
                <h3 class="text-lg font-semibold text-blue-700 dark:text-blue-300">Total Payments to Writers</h3>
                <p class="mt-2 text-2xl font-bold text-blue-800 dark:text-blue-200">${{ number_format($payments->sum('amount'), 2) }}</p>
            </div>
            
            <div class="rounded-lg bg-green-50 p-4 dark:bg-green-900">
                <h3 class="text-lg font-semibold text-green-700 dark:text-green-300">Processed Payments</h3>
                <p class="mt-2 text-2xl font-bold text-green-800 dark:text-green-200">${{ number_format($payments->where('status', 'processed')->sum('amount'), 2) }}</p>
            </div>
            
            <div class="rounded-lg bg-amber-50 p-4 dark:bg-amber-900">
                <h3 class="text-lg font-semibold text-amber-700 dark:text-amber-300">Pending Payments</h3>
                <p class="mt-2 text-2xl font-bold text-amber-800 dark:text-amber-200">${{ number_format($payments->where('status', 'pending')->sum('amount'), 2) }}</p>
            </div>
        </div>

        <!-- Writer Payments Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-4 py-3">ID</th>
                        <th scope="col" class="px-4 py-3">Writer</th>
                        <th scope="col" class="px-4 py-3">Order</th>
                        <th scope="col" class="px-4 py-3">Amount</th>
                        <th scope="col" class="px-4 py-3">Created Date</th>
                        <th scope="col" class="px-4 py-3">Payment Date</th>
                        <th scope="col" class="px-4 py-3">Status</th>
                        <th scope="col" class="px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payments as $payment)
                    <tr class="border-b dark:border-gray-700">
                        <td class="whitespace-nowrap px-4 py-3">{{ $payment->id }}</td>
                        <td class="px-4 py-3">
                            @if($payment->writer)
                                <a href="{{ route('admin.writers.show', $payment->writer) }}" class="font-medium text-blue-600 hover:underline dark:text-blue-400">
                                    {{ $payment->writer->name }}
                                </a>
                            @else
                                <span class="text-gray-500 dark:text-gray-400">Unknown Writer</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if($payment->order)
                                <a href="{{ route('admin.orders.show', $payment->order) }}" class="font-medium text-blue-600 hover:underline dark:text-blue-400">
                                    #{{ $payment->order->order_number ?? $payment->order->id }}
                                </a>
                            @else
                                <span class="text-gray-500 dark:text-gray-400">N/A</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 font-medium">${{ number_format($payment->amount, 2) }}</td>
                        <td class="whitespace-nowrap px-4 py-3">{{ $payment->created_at->format('M d, Y') }}</td>
                        <td class="whitespace-nowrap px-4 py-3">
                            @if($payment->payment_date)
                                {{ \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') }}
                            @else
                                <span class="text-gray-500 dark:text-gray-400">Not paid yet</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <span class="rounded-full px-2.5 py-0.5 text-xs font-medium
                                @if($payment->status == 'processed') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                @elseif($payment->status == 'pending') bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-300
                                @endif">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.payments.writer.show', $payment) }}" class="font-medium text-blue-600 hover:underline dark:text-blue-400">
                                    View
                                </a>
                                
                                @if($payment->status == 'pending')
                                <button type="button" onclick="openProcessPaymentModal({{ $payment->id }})" class="font-medium text-green-600 hover:underline dark:text-green-400">
                                    Process
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

    <!-- Process Payment Modal -->
    <div id="processPaymentModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex min-h-screen items-end justify-center px-4 pb-20 pt-4 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75 dark:bg-gray-900"></div>
            </div>
            
            <span class="hidden sm:inline-block sm:h-screen sm:align-middle" aria-hidden="true">&#8203;</span>
            
            <div class="inline-block transform overflow-hidden rounded-lg bg-white text-left align-bottom shadow-xl transition-all dark:bg-gray-800 sm:my-8 sm:w-full sm:max-w-lg sm:align-middle">
                <form id="processPaymentForm" action="" method="POST">
                    @csrf
                    
                    <div class="bg-white px-4 pb-4 pt-5 dark:bg-gray-800 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-green-100 dark:bg-green-900 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">
                                    Process Writer Payment
                                </h3>
                                
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Please enter the transaction details to mark this payment as processed.
                                    </p>
                                    
                                    <div class="mt-4">
                                        <label for="transaction_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Transaction ID</label>
                                        <input type="text" id="transaction_id" name="transaction_id" required class="mt-1 block w-full rounded-md border border-gray-300 p-2 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 px-4 py-3 dark:bg-gray-700 sm:flex sm:flex-row-reverse sm:px-6">
                        <button type="submit" class="inline-flex w-full justify-center rounded-md border border-transparent bg-green-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm">
                            Process Payment
                        </button>
                        <button type="button" onclick="closeProcessPaymentModal()" class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 sm:mt-0 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openProcessPaymentModal(paymentId) {
            document.getElementById('processPaymentForm').action = `{{ route('admin.payments.writer.process', '') }}/${paymentId}`;
            document.getElementById('processPaymentModal').classList.remove('hidden');
        }
        
        function closeProcessPaymentModal() {
            document.getElementById('processPaymentModal').classList.add('hidden');
        }
    </script>
</x-admin-layout> 