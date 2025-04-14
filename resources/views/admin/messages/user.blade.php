<x-admin-layout>
    <x-slot:header>Direct Messages with {{ $user->name }}</x-slot:header>
    
    <div class="mb-6 grid gap-6 lg:grid-cols-3">
        <!-- User Info Card -->
        <div class="lg:col-span-1">
            <div class="rounded-lg border border-gray-200 bg-white p-6 shadow dark:border-gray-700 dark:bg-zinc-900">
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">User Information</h3>
                </div>
                
                <div class="mb-6 space-y-3">
                    <div class="flex justify-between border-b border-gray-200 pb-2 dark:border-gray-700">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Name:</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->name }}</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-200 pb-2 dark:border-gray-700">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Email:</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->email }}</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-200 pb-2 dark:border-gray-700">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Role:</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ ucfirst($user->role) }}</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-200 pb-2 dark:border-gray-700">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Member Since:</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
                
                <div class="mb-6">
                    <h4 class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">Actions:</h4>
                    <div class="space-y-2">
                        @if($user->role == 'client')
                            <a href="{{ route('admin.clients.show', $user) }}" class="block w-full rounded-lg bg-blue-600 px-4 py-2 text-center text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-700 dark:hover:bg-blue-800 dark:focus:ring-blue-800">
                                View Client Profile
                            </a>
                        @elseif($user->role == 'writer')
                            <a href="{{ route('admin.writers.show', $user) }}" class="block w-full rounded-lg bg-blue-600 px-4 py-2 text-center text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-700 dark:hover:bg-blue-800 dark:focus:ring-blue-800">
                                View Writer Profile
                            </a>
                        @endif
                        
                        <a href="{{ route('admin.messages.users') }}" class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-center text-sm font-medium text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:hover:border-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-700">
                            Back to All Users
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Messages Section -->
        <div class="lg:col-span-2">
            <div class="rounded-lg border border-gray-200 bg-white p-6 shadow dark:border-gray-700 dark:bg-zinc-900">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Direct Messages</h3>
                    <span class="text-sm text-gray-600 dark:text-gray-400">{{ count($messages) }} messages</span>
                </div>
                
                <!-- Messages Display -->
                <div class="mb-6 h-96 overflow-y-auto rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800" id="messageContainer">
                    @forelse($messages as $message)
                        <div class="mb-4 flex @if($message->sender_id === Auth::id()) justify-end @endif">
                            <div class="max-w-3/4 rounded-lg 
                                @if($message->sender_id === Auth::id()) bg-blue-600 text-white 
                                @else bg-white text-gray-900 dark:bg-gray-700 dark:text-white @endif p-3 shadow">
                                <div class="mb-1 flex items-center justify-between">
                                    <span class="text-xs font-medium">
                                        {{ $message->sender_id === Auth::id() ? 'You' : $user->name }}
                                    </span>
                                    <span class="text-xs">
                                        {{ $message->created_at->format('M d, Y g:i A') }}
                                    </span>
                                </div>
                                <p class="whitespace-pre-line text-sm">{{ $message->content }}</p>
                                
                                @if($message->attachment_path)
                                    <div class="mt-2 flex items-center rounded-lg @if($message->sender_id === Auth::id()) bg-blue-700 @else bg-gray-100 dark:bg-gray-600 @endif p-2">
                                        <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                        </svg>
                                        <a href="{{ route('messages.attachment', $message) }}" class="text-xs @if($message->sender_id === Auth::id()) text-gray-100 hover:text-white @else text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 @endif">
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
                <form action="{{ route('admin.messages.send-direct', $user) }}" method="POST" enctype="multipart/form-data">
                    @csrf
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