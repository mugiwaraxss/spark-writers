<x-layout-writer>
    <x-slot:header>Assignment #{{ $order->id }}</x-slot:header>
    
    <div class="mb-6 grid gap-6 lg:grid-cols-3">
        <!-- Assignment Summary Card -->
        <div class="lg:col-span-1">
            <div class="rounded-lg border border-gray-200 bg-white p-6 shadow dark:border-gray-700 dark:bg-zinc-900">
                <div class="mb-6 flex justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Assignment Summary</h3>
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
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $order->id }}</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-200 pb-2 dark:border-gray-700">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Assigned:</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $order->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-200 pb-2 dark:border-gray-700">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Due Date:</span>
                        <span class="text-sm font-medium 
                            @if($order->deadline->isPast() && $order->status != 'completed') text-red-600 dark:text-red-400
                            @elseif($order->deadline->diffInDays(now()) < 2 && $order->status != 'completed') text-amber-600 dark:text-amber-400
                            @else text-gray-900 dark:text-white @endif">
                            {{ $order->deadline->format('M d, Y g:i A') }}
                        </span>
                    </div>
                    <div class="flex justify-between border-b border-gray-200 pb-2 dark:border-gray-700">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Time Left:</span>
                        <span class="text-sm font-medium 
                            @if($order->deadline->isPast() && $order->status != 'completed') text-red-600 dark:text-red-400
                            @elseif($order->deadline->diffInDays(now()) < 2 && $order->status != 'completed') text-amber-600 dark:text-amber-400
                            @else text-gray-900 dark:text-white @endif">
                            @if($order->status == 'completed')
                                Completed
                            @elseif($order->deadline->isPast())
                                Overdue
                            @else
                                {{ $order->deadline->diffForHumans(now(), ['parts' => 2]) }}
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between border-b border-gray-200 pb-2 dark:border-gray-700">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Client:</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $order->client->name }}</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-200 pb-2 dark:border-gray-700">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Payment Amount:</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">${{ number_format($order->price ?? 0, 2) }}</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-200 pb-2 dark:border-gray-700">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Payment Status:</span>
                        <span class="text-sm font-medium @if($order->payment_status == 'paid') text-green-600 dark:text-green-400 @else text-amber-600 dark:text-amber-400 @endif">
                            {{ ucfirst($order->payment_status ?? 'pending') }}
                        </span>
                    </div>
                </div>
                
                <div class="space-y-3">
                    @if($order->status == 'pending' || $order->status == 'in_progress' || $order->status == 'assigned')
                        <button type="button" data-modal-target="submitWorkModal" data-modal-toggle="submitWorkModal" class="flex w-full items-center justify-center rounded-lg bg-blue-600 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-700 dark:hover:bg-blue-800 dark:focus:ring-blue-800">
                            <svg class="mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Submit Work
                        </button>
                    @endif
                    
                    @if($order->status == 'in_progress')
                        <form action="{{ route('writer.assignments.update-progress', $order) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="mb-3">
                                <label for="progress" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Work Progress</label>
                                <input id="progress" name="progress" type="range" min="0" max="100" value="{{ $order->progress ?? 0 }}" class="h-2 w-full cursor-pointer appearance-none rounded-lg bg-gray-200 dark:bg-gray-700">
                                <div class="mt-1 flex justify-between">
                                    <span class="text-xs text-gray-600 dark:text-gray-400">0%</span>
                                    <span class="text-xs font-medium text-gray-900 dark:text-white"><span id="progressValue">{{ $order->progress ?? 0 }}</span>%</span>
                                    <span class="text-xs text-gray-600 dark:text-gray-400">100%</span>
                                </div>
                            </div>
                            <button type="submit" class="flex w-full items-center justify-center rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-center text-sm font-medium text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:hover:border-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-700">
                                <svg class="mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Update Progress
                            </button>
                        </form>
                    @endif
                    
                    @if($order->status == 'revision')
                        <button type="button" data-modal-target="revisionDetailsModal" data-modal-toggle="revisionDetailsModal" class="flex w-full items-center justify-center rounded-lg bg-purple-600 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-purple-700 focus:outline-none focus:ring-4 focus:ring-purple-300 dark:bg-purple-700 dark:hover:bg-purple-800 dark:focus:ring-purple-800">
                            <svg class="mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            View Revision Details
                        </button>
                    @endif
                    
                    @if(isset($order->files) && $order->files->where('file_category', 'submission')->count() > 0)
                        <a href="{{ route('writer.assignments.download-submission', $order) }}" class="flex w-full items-center justify-center rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-center text-sm font-medium text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:hover:border-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-700">
                            <svg class="mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Download Submitted Work
                        </a>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Assignment Details -->
        <div class="lg:col-span-2">
            <div class="rounded-lg border border-gray-200 bg-white p-6 shadow dark:border-gray-700 dark:bg-zinc-900">
                <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Assignment Details</h3>
                
                <div class="mb-6 grid gap-6 md:grid-cols-2">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Title:</p>
                        <p class="text-base font-medium text-gray-900 dark:text-white">{{ $order->title }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Subject:</p>
                        <p class="text-base font-medium text-gray-900 dark:text-white">{{ ucfirst(str_replace('_', ' ', $order->subject_area)) }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Academic Level:</p>
                        <p class="text-base font-medium text-gray-900 dark:text-white">{{ ucfirst(str_replace('_', ' ', $order->academic_level ?? 'not specified')) }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Citation Style:</p>
                        <p class="text-base font-medium text-gray-900 dark:text-white">{{ strtoupper($order->citation_style ?? 'not specified') }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Number of Pages:</p>
                        <p class="text-base font-medium text-gray-900 dark:text-white">{{ $order->page_count ?? 'not specified' }} {{ isset($order->page_count) ? "(approx. " . $order->page_count * 275 . " words)" : "" }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Sources Required:</p>
                        <p class="text-base font-medium text-gray-900 dark:text-white">{{ $order->sources_required ?? 'not specified' }}</p>
                    </div>
                    
                    @if(isset($order->services) && is_array($order->services) && count($order->services) > 0)
                    <div class="md:col-span-2">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Additional Services:</p>
                        <ul class="ml-4 mt-2 list-disc text-gray-900 dark:text-white">
                            @foreach($order->services as $service)
                                <li>{{ ucfirst(str_replace('_', ' ', $service)) }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
                
                <div class="mb-6">
                    <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">Instructions:</p>
                    <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800">
                        <p class="whitespace-pre-line text-gray-900 dark:text-white">{{ $order->instructions }}</p>
                    </div>
                </div>
                
                <!-- Client Files -->
                @if(isset($order->files) && count($order->files) > 0)
                <div class="mb-6">
                    <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">Client Files:</p>
                    <ul class="space-y-2">
                        @foreach($order->files as $file)
                            @if($file->file_category == 'requirements')
                            <li class="flex items-center justify-between rounded-lg border border-gray-200 bg-gray-50 px-4 py-2 dark:border-gray-700 dark:bg-gray-800">
                                <div class="flex items-center">
                                    <!-- File type icon based on extension -->
                                    @php
                                        $extension = pathinfo($file->file_name, PATHINFO_EXTENSION);
                                        $iconColor = 'text-gray-500';
                                        
                                        if (in_array($extension, ['pdf'])) {
                                            $iconColor = 'text-red-500';
                                        } elseif (in_array($extension, ['doc', 'docx'])) {
                                            $iconColor = 'text-blue-500';
                                        } elseif (in_array($extension, ['xls', 'xlsx'])) {
                                            $iconColor = 'text-green-500';
                                        } elseif (in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
                                            $iconColor = 'text-purple-500';
                                        }
                                    @endphp
                                    
                                    <svg class="mr-3 h-5 w-5 {{ $iconColor }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                    
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $file->file_name }}</span>
                                </div>
                                <a href="{{ route('files.download', $file) }}" class="ml-2 rounded-lg bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 hover:bg-blue-100 dark:bg-blue-900 dark:text-blue-300 dark:hover:bg-blue-800">
                                    Download
                                </a>
                            </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
                @endif
                
                <!-- Messages/Chat Section -->
                <div class="mb-6">
                    <div class="mb-2 flex items-center justify-between">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Client Communication</h4>
                        <span class="text-sm text-gray-600 dark:text-gray-400">{{ count($order->messages ?? []) }} messages</span>
                    </div>
                    
                    <div class="mb-4 h-96 overflow-y-auto rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800">
                        @if(isset($order->messages) && count($order->messages) > 0)
                            @foreach($order->messages as $message)
                                <div class="mb-4 flex @if($message->sender_id == Auth::id()) justify-end @endif">
                                    <div class="max-w-3/4 rounded-lg @if($message->sender_id == Auth::id()) bg-blue-600 text-white @else bg-white text-gray-900 dark:bg-gray-700 dark:text-white @endif p-3 shadow">
                                        <div class="mb-1 flex items-center justify-between">
                                            <span class="text-xs font-medium @if($message->sender_id == Auth::id()) text-blue-100 @else text-gray-600 dark:text-gray-400 @endif">
                                                {{ $message->sender_id == Auth::id() ? 'You' : $order->client->name }}
                                            </span>
                                            <span class="text-xs @if($message->sender_id == Auth::id()) text-blue-100 @else text-gray-500 dark:text-gray-400 @endif">
                                                {{ $message->created_at->format('M d, Y g:i A') }}
                                            </span>
                                        </div>
                                        <p class="whitespace-pre-line text-sm">{{ $message->content }}</p>
                                        
                                        @if(isset($message->attachment) && $message->attachment)
                                            <div class="mt-2 flex items-center rounded-lg @if($message->sender_id == Auth::id()) bg-blue-700 @else bg-gray-100 dark:bg-gray-600 @endif p-2">
                                                <svg class="mr-2 h-4 w-4 @if($message->sender_id == Auth::id()) text-blue-100 @else text-gray-500 dark:text-gray-400 @endif" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                                </svg>
                                                <a href="{{ route('messages.attachment', $message) }}" class="text-xs @if($message->sender_id == Auth::id()) text-blue-100 hover:text-white @else text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 @endif">
                                                    {{ $message->attachment_name }}
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="flex items-center justify-center h-full">
                                <p class="text-gray-500 dark:text-gray-400">No messages yet. Start a conversation with the client.</p>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Message Form -->
                    <form action="{{ route('writer.assignments.message', $order) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label for="message" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Message</label>
                            <textarea id="message" name="message" rows="3" class="block w-full rounded-lg border border-gray-300 bg-white p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500" placeholder="Type your message here..."></textarea>
                        </div>
                        <div class="mb-4">
                            <label class="mb-2 block text-sm font-medium text-gray-900 dark:text-white" for="attachment">Attach File (optional)</label>
                            <input class="block w-full cursor-pointer rounded-lg border border-gray-300 bg-white text-sm text-gray-900 focus:outline-none dark:border-gray-600 dark:bg-gray-700 dark:text-gray-400 dark:placeholder-gray-400" id="attachment" name="attachment" type="file">
                        </div>
                        <button type="submit" class="rounded-lg bg-blue-600 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Submit Work Modal -->
    <div id="submitWorkModal" tabindex="-1" aria-hidden="true" class="fixed left-0 right-0 top-0 z-50 hidden h-[calc(100%-1rem)] max-h-full w-full overflow-y-auto overflow-x-hidden p-4 md:inset-0">
        <div class="relative max-h-full w-full max-w-2xl">
            <div class="relative rounded-lg bg-white shadow dark:bg-gray-800">
                <div class="flex items-start justify-between rounded-t border-b p-4 dark:border-gray-700">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Submit Work
                    </h3>
                    <button type="button" class="ml-auto inline-flex h-8 w-8 items-center justify-center rounded-lg bg-transparent text-sm text-gray-400 hover:bg-gray-200 hover:text-gray-900 dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="submitWorkModal">
                        <svg class="h-3 w-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                
                <form action="{{ route('writer.assignments.submit', $order) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-6 p-6">
                        <div>
                            <label for="comment" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Submission Comment</label>
                            <textarea id="comment" name="comment" rows="4" class="block w-full rounded-lg border border-gray-300 bg-white p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500" placeholder="Provide any additional information about your submission"></textarea>
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-900 dark:text-white" for="files">Upload Files</label>
                            <input class="block w-full cursor-pointer rounded-lg border border-gray-300 bg-white text-sm text-gray-900 focus:outline-none dark:border-gray-600 dark:bg-gray-700 dark:text-gray-400 dark:placeholder-gray-400" id="files" name="files[]" type="file" multiple required>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Upload all completed documents. You can select multiple files.</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2 rounded-b border-t border-gray-200 p-6 dark:border-gray-700">
                        <button type="submit" class="rounded-lg bg-blue-600 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit Work</button>
                        <button type="button" class="rounded-lg border border-gray-200 bg-white px-5 py-2.5 text-sm font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-900 focus:z-10 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-700" data-modal-hide="submitWorkModal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Revision Details Modal -->
    @if($order->status == 'revision' && $order->latestRevision)
    <div id="revisionDetailsModal" tabindex="-1" aria-hidden="true" class="fixed left-0 right-0 top-0 z-50 hidden h-[calc(100%-1rem)] max-h-full w-full overflow-y-auto overflow-x-hidden p-4 md:inset-0">
        <div class="relative max-h-full w-full max-w-md">
            <div class="relative rounded-lg bg-white shadow dark:bg-gray-700">
                <button type="button" class="absolute right-2.5 top-3 ml-auto inline-flex h-8 w-8 items-center justify-center rounded-lg bg-transparent text-sm text-gray-400 hover:bg-gray-200 hover:text-gray-900 dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="revisionDetailsModal">
                    <svg class="h-3 w-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
                <div class="px-6 py-6 lg:px-8">
                    <h3 class="mb-4 text-xl font-medium text-gray-900 dark:text-white">Revision Details</h3>
                    
                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Date Requested:</p>
                        <p class="text-base text-gray-900 dark:text-white">{{ $order->latestRevision->created_at->format('M d, Y g:i A') }}</p>
                    </div>
                    
                    <div class="mb-6">
                        <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">Revision Instructions:</p>
                        <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800">
                            <p class="whitespace-pre-line text-gray-900 dark:text-white">{{ $order->latestRevision->revision_instructions }}</p>
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">Previous Submission:</p>
                        <a href="{{ route('writer.assignments.download-submission', $order) }}" class="flex items-center rounded-lg border border-gray-200 bg-gray-50 px-4 py-3 text-blue-600 hover:text-blue-800 dark:border-gray-700 dark:bg-gray-800 dark:text-blue-400 dark:hover:text-blue-300">
                            <svg class="mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Download Your Previous Submission
                        </a>
                    </div>
                    
                    <button type="button" data-modal-target="submitWorkModal" data-modal-toggle="submitWorkModal" data-modal-hide="revisionDetailsModal" class="w-full rounded-lg bg-blue-600 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-700 dark:hover:bg-blue-800 dark:focus:ring-blue-800">
                        Submit Revised Work
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    <script>
        // Display selected filename when attaching a file
        if (document.getElementById('file_attachment')) {
            document.getElementById('file_attachment').addEventListener('change', function() {
                const fileNameElement = document.getElementById('file_name');
                if (this.files.length > 0) {
                    fileNameElement.textContent = this.files[0].name;
                } else {
                    fileNameElement.textContent = '';
                }
            });
        }
        
        // Update progress value display
        const progressInput = document.getElementById('progress');
        if (progressInput) {
            const progressValue = document.getElementById('progressValue');
            progressInput.addEventListener('input', function() {
                progressValue.textContent = this.value;
            });
        }
        
        // Scroll to bottom of message container on page load
        document.addEventListener('DOMContentLoaded', function() {
            const messageContainer = document.querySelector('.overflow-y-auto');
            if (messageContainer) {
                messageContainer.scrollTop = messageContainer.scrollHeight;
            }
        });

        // Simple show/hide modal functionality as fallback for Flowbite
        document.addEventListener('DOMContentLoaded', function() {
            // Manual implementation for submit work modal
            const submitWorkModal = document.getElementById('submitWorkModal');
            if (submitWorkModal) {
                document.querySelectorAll('[data-modal-target="submitWorkModal"]').forEach(button => {
                    button.addEventListener('click', function() {
                        submitWorkModal.classList.remove('hidden');
                    });
                });
                
                document.querySelectorAll('[data-modal-hide="submitWorkModal"]').forEach(button => {
                    button.addEventListener('click', function() {
                        submitWorkModal.classList.add('hidden');
                    });
                });
            }
            
            // Manual implementation for revision details modal
            const revisionModal = document.getElementById('revisionDetailsModal');
            if (revisionModal) {
                document.querySelectorAll('[data-modal-target="revisionDetailsModal"]').forEach(button => {
                    button.addEventListener('click', function() {
                        revisionModal.classList.remove('hidden');
                    });
                });
                
                document.querySelectorAll('[data-modal-hide="revisionDetailsModal"]').forEach(button => {
                    button.addEventListener('click', function() {
                        revisionModal.classList.add('hidden');
                    });
                });
                
                // Handle showing submit modal from revision modal
                const submitFromRevisionBtns = document.querySelectorAll('[data-modal-target="submitWorkModal"][data-modal-hide="revisionDetailsModal"]');
                submitFromRevisionBtns.forEach(button => {
                    button.addEventListener('click', function() {
                        revisionModal.classList.add('hidden');
                        submitWorkModal.classList.remove('hidden');
                    });
                });
            }
        });
    </script>
</x-layout-writer> 