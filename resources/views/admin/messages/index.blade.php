<x-admin-layout>
    <x-slot:header>Message Management</x-slot:header>
    
    <div class="mb-6 rounded-lg border border-gray-200 bg-white p-6 shadow dark:border-gray-700 dark:bg-zinc-900">
        <div class="mb-6 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">All Order Conversations</h3>
            <a href="{{ route('admin.messages.users') }}" class="rounded-lg bg-blue-600 px-4 py-2 text-center text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-700 dark:hover:bg-blue-800 dark:focus:ring-blue-800">
                Direct Messages
            </a>
        </div>
        
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Order ID</th>
                        <th scope="col" class="px-6 py-3">Client</th>
                        <th scope="col" class="px-6 py-3">Writer</th>
                        <th scope="col" class="px-6 py-3">Last Updated</th>
                        <th scope="col" class="px-6 py-3">Status</th>
                        <th scope="col" class="px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($messagesGroups as $messageGroup)
                        @if($messageGroup->order)
                        <tr class="border-b bg-white hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-600">
                            <th scope="row" class="whitespace-nowrap px-6 py-4 font-medium text-gray-900 dark:text-white">
                                #{{ $messageGroup->order->id }}
                            </th>
                            <td class="px-6 py-4">
                                {{ $messageGroup->order->client ? $messageGroup->order->client->name : 'N/A' }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $messageGroup->order->writer ? $messageGroup->order->writer->name : 'Not Assigned' }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $messageGroup->order->updated_at->diffForHumans() }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="rounded-full px-2.5 py-1 text-xs font-medium
                                    @if($messageGroup->order->status == 'completed') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                    @elseif($messageGroup->order->status == 'in_progress') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300
                                    @elseif($messageGroup->order->status == 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                                    @elseif($messageGroup->order->status == 'revision') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300
                                    @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                                    @endif">
                                    {{ ucfirst(str_replace('_', ' ', $messageGroup->order->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.messages.order', $messageGroup->order) }}" class="font-medium text-blue-600 hover:underline dark:text-blue-500">View Messages</a>
                            </td>
                        </tr>
                        @endif
                    @empty
                        <tr class="border-b bg-white dark:border-gray-700 dark:bg-gray-800">
                            <td colspan="6" class="px-6 py-4 text-center">
                                No messages found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $messagesGroups->links() }}
        </div>
    </div>
</x-admin-layout> 