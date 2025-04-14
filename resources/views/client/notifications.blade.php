<x-client-layout>
    <x-slot:header>Notifications</x-slot:header>
    
    @if (session('success'))
        <div class="mb-4 rounded-lg bg-green-50 p-4 text-sm text-green-800 dark:bg-green-900 dark:text-green-300" role="alert">
            {{ session('success') }}
        </div>
    @endif
    
    <div class="mb-6 flex justify-between items-center">
        <div class="flex space-x-3">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">All Notifications</h3>
            @if($unread_count > 0)
                <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full">
                    {{ $unread_count }}
                </span>
            @endif
        </div>
        
        @if($unread_count > 0)
            <form action="{{ route('client.notifications.mark-all-read') }}" method="POST">
                @csrf
                <button type="submit" class="text-sm font-medium text-blue-600 hover:underline dark:text-blue-400">
                    Mark all as read
                </button>
            </form>
        @endif
    </div>
    
    @if(count($notifications) > 0)
        <div class="space-y-4">
            @foreach($notifications as $notification)
                <div class="flex items-start p-4 rounded-lg border {{ $notification->read_status ? 'bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700' : 'bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800' }}">
                    <div class="mr-3 mt-0.5">
                        <span class="inline-flex items-center justify-center h-8 w-8 rounded-full 
                            @if($notification->type == 'order')
                                bg-blue-100 dark:bg-blue-900
                            @elseif($notification->type == 'payment')
                                bg-green-100 dark:bg-green-900
                            @elseif($notification->type == 'system')
                                bg-purple-100 dark:bg-purple-900
                            @else
                                bg-gray-100 dark:bg-gray-700
                            @endif
                        ">
                            @if($notification->type == 'order')
                                <svg class="h-4 w-4 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            @elseif($notification->type == 'payment')
                                <svg class="h-4 w-4 text-green-600 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            @elseif($notification->type == 'system')
                                <svg class="h-4 w-4 text-purple-600 dark:text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            @else
                                <svg class="h-4 w-4 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                </svg>
                            @endif
                        </span>
                    </div>
                    
                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-1">
                            <h4 class="text-sm font-semibold text-gray-900 dark:text-white">
                                {{ $notification->title }}
                            </h4>
                            <div class="flex items-center gap-2">
                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                                </span>
                                
                                @if(!$notification->read_status)
                                    <form action="{{ route('client.notifications.mark-read', $notification) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-xs font-medium text-blue-600 hover:underline dark:text-blue-400">
                                            Mark as read
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                        
                        <p class="text-sm text-gray-700 dark:text-gray-300">
                            {{ $notification->message }}
                        </p>
                        
                        @if($notification->action_url)
                            <div class="mt-2">
                                <a href="{{ $notification->action_url }}" class="text-sm font-medium text-blue-600 hover:underline dark:text-blue-400">
                                    {{ $notification->action_text ?? 'View Details' }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="mt-4">
            {{ $notifications->links() }}
        </div>
    @else
        <div class="flex flex-col items-center justify-center rounded-lg border border-dashed border-gray-300 bg-gray-50 py-8 text-center dark:border-gray-600 dark:bg-gray-800">
            <svg class="mb-3 h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
            </svg>
            <h3 class="mb-2 text-lg font-medium text-gray-900 dark:text-white">No notifications</h3>
            <p class="text-gray-500 dark:text-gray-400">You don't have any notifications at the moment.</p>
        </div>
    @endif
</x-client-layout> 