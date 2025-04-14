<x-layout-writer>
    <x-slot:header>Revision Request</x-slot:header>
    
    <div class="mb-6 grid gap-6 md:grid-cols-3">
        <!-- Sidebar -->
        <div class="md:col-span-1">
            <div class="rounded-lg border border-gray-200 bg-white p-6 shadow dark:border-gray-700 dark:bg-zinc-900">
                <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Order Details</h3>
                
                <div class="mb-4 space-y-3">
                    <div class="flex justify-between border-b border-gray-200 pb-2 dark:border-gray-700">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Order ID:</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $order->id }}</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-200 pb-2 dark:border-gray-700">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Status:</span>
                        <span class="rounded-full px-2 py-1 text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300">
                            Revision Requested
                        </span>
                    </div>
                    <div class="flex justify-between border-b border-gray-200 pb-2 dark:border-gray-700">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Client:</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $order->client->name }}</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-200 pb-2 dark:border-gray-700">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Due Date:</span>
                        <span class="text-sm font-medium
                            @if($order->deadline->isPast()) text-red-600 dark:text-red-400
                            @elseif($order->deadline->diffInDays(now()) < 2) text-amber-600 dark:text-amber-400
                            @else text-gray-900 dark:text-white @endif">
                            {{ $order->deadline->format('M d, Y g:i A') }}
                        </span>
                    </div>
                    <div class="flex justify-between border-b border-gray-200 pb-2 dark:border-gray-700">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Time Left:</span>
                        <span class="text-sm font-medium
                            @if($order->deadline->isPast()) text-red-600 dark:text-red-400
                            @elseif($order->deadline->diffInDays(now()) < 2) text-amber-600 dark:text-amber-400
                            @else text-gray-900 dark:text-white @endif">
                            @if($order->deadline->isPast())
                                Overdue
                            @else
                                {{ $order->deadline->diffForHumans(now(), ['parts' => 2]) }}
                            @endif
                        </span>
                    </div>
                </div>
                
                <div class="mt-6 space-y-3">
                    <a href="{{ route('writer.assignments.show', $order) }}" class="flex w-full items-center justify-center rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-center text-sm font-medium text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:hover:border-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-700">
                        <svg class="mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                        View Full Order
                    </a>
                    <form action="{{ route('writer.assignments.start-revision', $order) }}" method="POST" class="mt-3">
                        @csrf
                        <button type="submit" class="flex w-full items-center justify-center rounded-lg bg-blue-600 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            <svg class="mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Start Revision
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="md:col-span-2">
            <div class="rounded-lg border border-gray-200 bg-white p-6 shadow dark:border-gray-700 dark:bg-zinc-900">
                <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Revision Request Details</h3>
                
                @if($revisionRequest)
                    <div class="mb-6 rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800">
                        <div class="mb-4 flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="mr-3 flex h-10 w-10 items-center justify-center rounded-full bg-gray-200 dark:bg-gray-700">
                                    <svg class="h-6 w-6 text-gray-600 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $order->client->name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $revisionRequest->created_at->format('M d, Y g:i A') }}</p>
                                </div>
                            </div>
                            <span class="rounded-full bg-purple-100 px-3 py-1 text-xs font-medium text-purple-800 dark:bg-purple-900 dark:text-purple-300">
                                Revision Request
                            </span>
                        </div>
                        <div class="whitespace-pre-line text-sm text-gray-800 dark:text-gray-200">
                            {{ $revisionRequest->content }}
                        </div>
                        
                        @if($revisionRequest->attachment)
                            <div class="mt-4 flex items-center rounded-lg border border-gray-200 bg-white p-3 dark:border-gray-700 dark:bg-gray-700">
                                <svg class="mr-2 h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                </svg>
                                <div class="overflow-hidden">
                                    <p class="text-sm font-medium text-gray-900 truncate dark:text-white">{{ $revisionRequest->attachment_name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        <a href="{{ route('messages.attachment', $revisionRequest) }}" class="text-blue-600 hover:underline dark:text-blue-400">Download Attachment</a>
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="rounded-lg border border-gray-200 bg-gray-50 p-6 text-center dark:border-gray-700 dark:bg-gray-800">
                        <p class="text-gray-500 dark:text-gray-400">No revision request details found.</p>
                    </div>
                @endif
                
                <h4 class="mt-6 mb-3 text-md font-medium text-gray-900 dark:text-white">Your Previous Submission</h4>
                @php
                    $submissions = $order->files()->where('file_category', 'submission')
                                       ->where('uploaded_by', Auth::id())
                                       ->latest()
                                       ->get();
                @endphp
                
                @if(count($submissions) > 0)
                    <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800">
                        <p class="mb-3 text-sm text-gray-600 dark:text-gray-400">Files from your last submission:</p>
                        <ul class="space-y-2">
                            @foreach($submissions as $file)
                                <li class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-2 dark:border-gray-700 dark:bg-gray-700">
                                    <div class="flex items-center">
                                        <svg class="mr-2 h-5 w-5 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $file->file_name }}</span>
                                    </div>
                                    <a href="{{ route('files.download', $file) }}" class="text-sm text-blue-600 hover:underline dark:text-blue-400">
                                        Download
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @else
                    <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 text-center dark:border-gray-700 dark:bg-gray-800">
                        <p class="text-gray-500 dark:text-gray-400">No previous submissions found.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layout-writer> 