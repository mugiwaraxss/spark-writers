<x-admin-layout>
    <x-slot:header>Order #{{ $order->id }} Messages</x-slot:header>
    
    <div class="mb-6 grid gap-6 lg:grid-cols-3">
        <!-- Order Summary Card -->
        <div class="lg:col-span-1">
            <div class="rounded-lg border border-gray-200 bg-white p-6 shadow dark:border-gray-700 dark:bg-zinc-900">
                <div class="mb-6 flex justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Order Summary</h3>
                    <span class="rounded-full px-3 py-1 text-xs font-medium 
                        @if($order->status == 'completed') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                        @elseif($order->status == 'in_progress') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300
                        @elseif($order->status == 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                        @elseif($order->status == 'revision') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300
                        @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                        @endif">
                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                    </span>
                </div>
                
                <div class="mb-6 space-y-3">
                    <div class="flex justify-between border-b border-gray-200 pb-2 dark:border-gray-700">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Order ID:</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">#{{ $order->id }}</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-200 pb-2 dark:border-gray-700">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Date Created:</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $order->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-200 pb-2 dark:border-gray-700">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Due Date:</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $order->deadline->format('M d, Y') }}</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-200 pb-2 dark:border-gray-700">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Client:</span>
                        <a href="{{ route('admin.messages.user', $order->client) }}" class="text-sm font-medium text-blue-600 hover:underline dark:text-blue-500">
                            {{ $order->client->name }}
                        </a>
                    </div>
                    @if($order->writer)
                    <div class="flex justify-between border-b border-gray-200 pb-2 dark:border-gray-700">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Writer:</span>
                        <a href="{{ route('admin.messages.user', $order->writer) }}" class="text-sm font-medium text-blue-600 hover:underline dark:text-blue-500">
                            {{ $order->writer->name }}
                        </a>
                    </div>
                    @endif
                    <div class="flex justify-between border-b border-gray-200 pb-2 dark:border-gray-700">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Price:</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">${{ number_format($order->price, 2) }}</span>
                    </div>
                </div>
                
                <div class="mb-6">
                    <h4 class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">Actions:</h4>
                    <div class="space-y-2">
                        <a href="{{ route('admin.orders.show', $order) }}" class="block w-full rounded-lg bg-blue-600 px-4 py-2 text-center text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-700 dark:hover:bg-blue-800 dark:focus:ring-blue-800">
                            View Full Order Details
                        </a>
                        <a href="{{ route('admin.messages.index') }}" class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-center text-sm font-medium text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:hover:border-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-700">
                            Back to All Messages
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Messages Section -->
        <div class="lg:col-span-2">
            <div class="rounded-lg border border-gray-200 bg-white p-6 shadow dark:border-gray-700 dark:bg-zinc-900">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Conversation</h3>
                    <span class="text-sm text-gray-600 dark:text-gray-400">{{ count($order->messages) }} messages</span>
                </div>
                
                <!-- Messages Display -->
                <div class="mb-6 h-96 overflow-y-auto rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800" id="messageContainer">
                    @forelse($order->messages as $message)
                        <div class="mb-4 flex">
                            <div class="max-w-3/4 rounded-lg 
                                @if($message->sender_type == 'admin') bg-blue-600 text-white 
                                @elseif($message->sender_type == 'writer') bg-green-600 text-white
                                @else bg-white text-gray-900 dark:bg-gray-700 dark:text-white @endif p-3 shadow">
                                <div class="mb-1 flex items-center justify-between">
                                    <span class="text-xs font-medium">
                                        @if($message->sender_type == 'admin')
                                            Admin: {{ $message->sender->name ?? 'System' }}
                                        @elseif($message->sender_type == 'writer')
                                            Writer: {{ $message->sender->name ?? 'Unknown' }}
                                        @else
                                            Client: {{ $message->sender->name ?? 'Unknown' }}
                                        @endif
                                    </span>
                                    <span class="text-xs">
                                        {{ $message->created_at->format('M d, Y g:i A') }}
                                    </span>
                                </div>
                                <p class="whitespace-pre-line text-sm">{{ $message->content }}</p>
                                
                                @if($message->attachment_path)
                                    <div class="mt-2 flex items-center rounded-lg @if($message->sender_type == 'admin') bg-blue-700 @elseif($message->sender_type == 'writer') bg-green-700 @else bg-gray-100 dark:bg-gray-600 @endif p-2">
                                        <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                        </svg>
                                        <a href="{{ route('messages.attachment', $message) }}" class="text-xs @if($message->sender_type == 'admin' || $message->sender_type == 'writer') text-gray-100 hover:text-white @else text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 @endif">
                                            {{ $message->attachment_name }}
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="flex h-full items-center justify-center">
                            <p class="text-gray-500 dark:text-gray-400">No messages yet. Start the conversation!</p>
                        </div>
                    @endforelse
                </div>
                
                <!-- Message Form -->
                <form action="{{ route('admin.messages.send', $order) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label for="recipient_type" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Send To</label>
                        <div class="flex space-x-4">
                            <div class="flex items-center">
                                <input id="recipient_client" type="radio" name="recipient_type" value="client" class="h-4 w-4 border-gray-300 bg-gray-100 text-blue-600 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600" checked>
                                <label for="recipient_client" class="ml-2 text-sm font-medium text-gray-900 dark:text-white">Client</label>
                            </div>
                            @if($order->writer)
                            <div class="flex items-center">
                                <input id="recipient_writer" type="radio" name="recipient_type" value="writer" class="h-4 w-4 border-gray-300 bg-gray-100 text-blue-600 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600">
                                <label for="recipient_writer" class="ml-2 text-sm font-medium text-gray-900 dark:text-white">Writer</label>
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="message" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Message</label>
                        <textarea id="message" name="message" rows="4" class="block w-full rounded-lg border border-gray-300 bg-white p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500" placeholder="Type your message here..."></textarea>
                    </div>
                    
                    <div class="mb-4">
                        <label class="mb-2 block text-sm font-medium text-gray-900 dark:text-white" for="attachment">Attachment (optional)</label>
                        <input class="block w-full cursor-pointer rounded-lg border border-gray-300 bg-gray-50 text-sm text-gray-900 focus:outline-none dark:border-gray-600 dark:bg-gray-700 dark:text-gray-400 dark:placeholder-gray-400" id="attachment" name="attachment" type="file">
                    </div>
                    
                    <button type="submit" class="flex items-center rounded-lg bg-blue-600 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-700 dark:hover:bg-blue-800 dark:focus:ring-blue-800">
                        <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                        Send Message
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Scroll to bottom of message container on page load
            const messageContainer = document.getElementById('messageContainer');
            messageContainer.scrollTop = messageContainer.scrollHeight;
        });
    </script>
</x-admin-layout> 