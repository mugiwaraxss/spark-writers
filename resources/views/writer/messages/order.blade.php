<x-layout-writer>
    <x-slot:header>Order Messages</x-slot:header>
    
    <div class="mb-6">
        <div class="mb-4 flex flex-col justify-between gap-4 md:flex-row md:items-center">
            <div>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Order #{{ $order->id }}: {{ $order->title }}
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Client: {{ $order->client->name }} | Status: {{ ucfirst($order->status) }}
                </p>
            </div>
            <div class="flex gap-4">
                <a href="{{ route('writer.messages.index') }}" class="flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:hover:border-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-700">
                    <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Messages
                </a>
                <a href="{{ route('writer.assignments.show', $order) }}" class="flex items-center rounded-lg border border-blue-700 bg-blue-700 px-4 py-2 text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    View Assignment
                </a>
            </div>
        </div>

        <!-- Messages -->
        <div class="mb-4 rounded-lg border border-gray-200 bg-white p-6 shadow dark:border-gray-700 dark:bg-zinc-900">
            <div class="mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Messages</h3>
            </div>
            
            <div class="mb-6 h-96 overflow-y-auto rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800">
                @if(count($order->messages) > 0)
                    @foreach($order->messages as $message)
                        <div class="mb-4 flex {{ $message->sender_id == Auth::id() ? 'justify-end' : 'justify-start' }}">
                            <div class="max-w-3/4 rounded-lg {{ $message->sender_id == Auth::id() ? 'bg-blue-600 text-white' : ($message->sender_type == 'admin' ? 'bg-purple-600 text-white' : 'bg-white text-gray-900 dark:bg-gray-700 dark:text-white') }} p-3 shadow">
                                <div class="mb-1 flex items-center justify-between">
                                    <span class="text-xs font-medium">
                                        @if($message->sender_id == Auth::id())
                                            You
                                        @elseif($message->sender_type == 'admin')
                                            Admin: {{ $message->sender->name ?? 'Unknown' }}
                                        @else
                                            Client: {{ $order->client->name }}
                                        @endif
                                    </span>
                                    <span class="text-xs">
                                        {{ $message->created_at->format('M d, Y g:i A') }}
                                    </span>
                                </div>
                                <p class="whitespace-pre-line text-sm">{{ $message->content }}</p>
                                
                                @if($message->attachment_path)
                                    <div class="mt-2 flex items-center rounded-lg {{ $message->sender_id == Auth::id() ? 'bg-blue-700' : ($message->sender_type == 'admin' ? 'bg-purple-700' : 'bg-gray-100 dark:bg-gray-600') }} p-2">
                                        <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                        </svg>
                                        <a href="{{ Storage::url($message->attachment_path) }}" class="text-xs {{ $message->sender_id == Auth::id() || $message->sender_type == 'admin' ? 'text-gray-100 hover:text-white' : 'text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300' }}" target="_blank">
                                            {{ $message->attachment_name }}
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="flex h-full items-center justify-center">
                        <p class="text-gray-500 dark:text-gray-400">No messages yet. Start the conversation!</p>
                    </div>
                @endif
            </div>
            
            <!-- Reply Form -->
            <form action="{{ route('writer.messages.reply', $order) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label for="message" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Your Message</label>
                    <textarea id="message" name="message" rows="4" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500" placeholder="Type your message here..." required></textarea>
                </div>
                <div class="mb-4">
                    <label class="mb-2 block text-sm font-medium text-gray-900 dark:text-white" for="attachment">Attachment (optional)</label>
                    <input class="block w-full cursor-pointer rounded-lg border border-gray-300 bg-gray-50 text-sm text-gray-900 focus:outline-none dark:border-gray-600 dark:bg-gray-700 dark:text-gray-400 dark:placeholder-gray-400" id="attachment" name="attachment" type="file">
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Max file size: 10MB</p>
                </div>
                <button type="submit" class="rounded-lg bg-blue-700 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Send Message</button>
            </form>
        </div>
    </div>
</x-layout-writer> 