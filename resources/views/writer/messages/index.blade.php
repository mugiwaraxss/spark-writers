<x-layout-writer>
    <x-slot:header>Messages</x-slot:header>
    
    <div class="mb-6 grid gap-6 lg:grid-cols-3">
        <!-- Order Messages -->
        <div class="rounded-lg border border-gray-200 bg-white p-6 shadow dark:border-gray-700 dark:bg-zinc-900 lg:col-span-2">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Order Messages</h3>
            </div>
            
            @if(count($orderMessages) > 0)
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($orderMessages as $message)
                        <div class="py-4">
                            <div class="mb-2 flex items-center justify-between">
                                <a href="{{ route('writer.messages.order', $message->order) }}" class="text-md font-medium text-blue-600 hover:underline dark:text-blue-400">
                                    Order #{{ $message->order->id }}: {{ Str::limit($message->order->title, 50) }}
                                </a>
                                <span class="text-sm text-gray-500 dark:text-gray-400">{{ $message->order->updated_at->format('M d, Y') }}</span>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ $message->order->status === 'completed' ? 'Completed' : 'In Progress' }} - 
                                {{ Str::limit($message->order->description, 100) }}
                            </p>
                            <div class="mt-2 text-sm">
                                <a href="{{ route('writer.messages.order', $message->order) }}" class="font-medium text-blue-600 hover:underline dark:text-blue-400">View conversation</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 text-center dark:border-gray-700 dark:bg-gray-800">
                    <p class="text-gray-500 dark:text-gray-400">No order messages found.</p>
                </div>
            @endif
        </div>
        
        <!-- Direct Messages -->
        <div class="rounded-lg border border-gray-200 bg-white p-6 shadow dark:border-gray-700 dark:bg-zinc-900">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Direct Messages</h3>
                <a href="{{ route('writer.messages.direct') }}" class="text-sm font-medium text-blue-600 hover:underline dark:text-blue-400">View All</a>
            </div>
            
            @if(count($directMessages) > 0)
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($directMessages->take(5) as $message)
                        <div class="py-4">
                            <div class="mb-2 flex items-center justify-between">
                                <span class="font-medium text-gray-900 dark:text-white">
                                    From: {{ $message->sender->name ?? 'Unknown' }}
                                    <span class="ml-2 text-xs text-gray-500">({{ ucfirst($message->sender_type) }})</span>
                                </span>
                                <span class="text-sm text-gray-500 dark:text-gray-400">{{ $message->created_at->format('M d, Y') }}</span>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ Str::limit($message->content, 100) }}
                            </p>
                        </div>
                    @endforeach
                </div>
                
                @if(count($directMessages) > 5)
                    <div class="mt-4 text-center">
                        <a href="{{ route('writer.messages.direct') }}" class="text-sm font-medium text-blue-600 hover:underline dark:text-blue-400">View all {{ count($directMessages) }} messages</a>
                    </div>
                @endif
            @else
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 text-center dark:border-gray-700 dark:bg-gray-800">
                    <p class="text-gray-500 dark:text-gray-400">No direct messages found.</p>
                </div>
            @endif
        </div>
    </div>
</x-layout-writer> 