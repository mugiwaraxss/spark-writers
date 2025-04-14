<x-admin-layout title="Disputes">
    <x-slot name="header">Dispute Management</x-slot>

    <div class="rounded-lg bg-white p-6 shadow-sm dark:bg-gray-800">
        @if($disputed_orders->isEmpty())
            <div class="flex flex-col items-center justify-center py-12">
                <div class="mb-4 h-16 w-16 text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="mb-2 text-lg font-medium text-gray-900 dark:text-white">No Active Disputes</h3>
                <p class="text-gray-500 dark:text-gray-400">There are currently no disputed orders that require attention.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-4 py-3">Order ID</th>
                            <th scope="col" class="px-4 py-3">Client</th>
                            <th scope="col" class="px-4 py-3">Writer</th>
                            <th scope="col" class="px-4 py-3">Dispute Date</th>
                            <th scope="col" class="px-4 py-3">Status</th>
                            <th scope="col" class="px-4 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($disputed_orders as $order)
                            <tr class="border-b dark:border-gray-700">
                                <td class="whitespace-nowrap px-4 py-3">{{ $order->order_number }}</td>
                                <td class="px-4 py-3">{{ $order->client->name }}</td>
                                <td class="px-4 py-3">{{ $order->writer->name ?? 'Unassigned' }}</td>
                                <td class="px-4 py-3">{{ $order->disputed_at->format('M d, Y') }}</td>
                                <td class="px-4 py-3">
                                    <span class="rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800 dark:bg-red-900 dark:text-red-300">
                                        Disputed
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="font-medium text-blue-600 hover:underline dark:text-blue-400">
                                        View Details
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $disputed_orders->links() }}
            </div>
        @endif
    </div>
</x-admin-layout> 