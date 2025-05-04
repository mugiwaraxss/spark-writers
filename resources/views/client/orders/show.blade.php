<x-client-layout>
    <x-slot:header>Order #{{ $order->order_number }}</x-slot:header>
    
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
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $order->order_number }}</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-200 pb-2 dark:border-gray-700">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Date Created:</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $order->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-200 pb-2 dark:border-gray-700">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Due Date:</span>
                        <span class="text-sm font-medium 
                            @php
                                $deadline = $order->deadline;
                                if (!($deadline instanceof \Carbon\Carbon)) {
                                    $deadline = \Carbon\Carbon::parse($deadline);
                                }
                                $isPast = $deadline->isPast();
                                $diffInDays = $deadline->diffInDays(now());
                            @endphp
                            @if($isPast && $order->status != 'completed') text-red-600 dark:text-red-400
                            @elseif($diffInDays < 2 && $order->status != 'completed') text-amber-600 dark:text-amber-400
                            @else text-gray-900 dark:text-white @endif">
                            {{ $deadline->format('M d, Y g:i A') }}
                        </span>
                    </div>
                    <div class="flex justify-between border-b border-gray-200 pb-2 dark:border-gray-700">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Time Left:</span>
                        <span class="text-sm font-medium 
                            @if($isPast && $order->status != 'completed') text-red-600 dark:text-red-400
                            @elseif($diffInDays < 2 && $order->status != 'completed') text-amber-600 dark:text-amber-400
                            @else text-gray-900 dark:text-white @endif">
                            @if($order->status == 'completed')
                                Completed
                            @elseif($isPast)
                                Overdue
                            @else
                                {{ $deadline->diffForHumans(now(), ['parts' => 2]) }}
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between border-b border-gray-200 pb-2 dark:border-gray-700">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Writer:</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">
                            @if($order->writer)
                                {{ $order->writer->name }}
                            @else
                                <span class="text-amber-600 dark:text-amber-400">Not assigned yet</span>
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between border-b border-gray-200 pb-2 dark:border-gray-700">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Amount:</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">${{ number_format($order->amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-200 pb-2 dark:border-gray-700">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Payment Status:</span>
                        <span class="text-sm font-medium @if($order->payment_status == 'paid') text-green-600 dark:text-green-400 @else text-amber-600 dark:text-amber-400 @endif">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </div>
                </div>
                
                <div class="space-y-3">
                    @if($order->payment_status == 'pending')
                        <a href="{{ route('client.payments.pay', $order) }}" class="flex w-full items-center justify-center rounded-lg bg-blue-600 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-700 dark:hover:bg-blue-800 dark:focus:ring-blue-800">
                            <svg class="mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                            Make Payment
                        </a>
                    @endif
                    
                    @if($order->status == 'completed')
                        <a href="{{ route('client.orders.download', $order) }}" class="flex w-full items-center justify-center rounded-lg bg-green-600 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-4 focus:ring-green-300 dark:bg-green-700 dark:hover:bg-green-800 dark:focus:ring-green-800">
                            <svg class="mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Download Paper
                        </a>
                        
                        @if(!$order->reviewed)
                            <a href="{{ route('client.reviews.create', $order) }}" class="flex w-full items-center justify-center rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-center text-sm font-medium text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:hover:border-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-700">
                                <svg class="mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                </svg>
                                Leave Review
                            </a>
                        @endif
                    @endif
                    
                    @if($order->status == 'completed')
                        <button type="button" data-modal-target="revisionModal" data-modal-toggle="revisionModal" class="flex w-full items-center justify-center rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-center text-sm font-medium text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:hover:border-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-700">
                            <svg class="mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Request Revision
                        </button>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Order Details -->
        <div class="lg:col-span-2">
            <div class="rounded-lg border border-gray-200 bg-white p-6 shadow dark:border-gray-700 dark:bg-zinc-900">
                <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Order Details</h3>
                
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
                        <p class="text-base font-medium text-gray-900 dark:text-white">{{ ucfirst(str_replace('_', ' ', $order->academic_level)) }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Citation Style:</p>
                        <p class="text-base font-medium text-gray-900 dark:text-white">{{ strtoupper($order->citation_style ?? 'Not specified') }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Number of Pages:</p>
                        <p class="text-base font-medium text-gray-900 dark:text-white">{{ ceil($order->word_count / 275) }} (approx. {{ $order->word_count }} words)</p>
                    </div>
                    
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Sources Required:</p>
                        <p class="text-base font-medium text-gray-900 dark:text-white">{{ $order->sources_required ?? 'Not specified' }}</p>
                    </div>
                    
                    @if($order->services && count($order->services) > 0)
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
                
                <!-- Supporting Files -->
                @if(count($order->files) > 0)
                <div class="mb-6">
                    <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">Supporting Files:</p>
                    <ul class="space-y-2">
                        @foreach($order->files as $file)
                            <li class="flex items-center justify-between rounded-lg border border-gray-200 bg-gray-50 px-4 py-2 dark:border-gray-700 dark:bg-gray-800">
                                <div class="flex items-center">
                                    <!-- File type icon based on extension -->
                                    @php
                                        $extension = pathinfo($file->filename, PATHINFO_EXTENSION);
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
                                    
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $file->original_filename }}</span>
                                </div>
                                <a href="{{ route('files.download', $file) }}" class="ml-2 rounded-lg bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 hover:bg-blue-100 dark:bg-blue-900 dark:text-blue-300 dark:hover:bg-blue-800">
                                    Download
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                @endif
                
                <!-- Completed Work Section -->
                @if($order->status == 'completed' && isset($order->files))
                <div class="mb-6">
                    <div class="mb-2 flex items-center justify-between">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Completed Work</h4>
                    </div>
                    
                    <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800">
                        @php
                            $submissionFiles = $order->files->where('file_category', 'submission');
                        @endphp
                        
                        @if(count($submissionFiles) > 0)
                            <ul class="space-y-2">
                                @foreach($submissionFiles as $file)
                                <li class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-3 dark:border-gray-700 dark:bg-gray-700">
                                    <div class="flex items-center">
                                        <!-- File type icon based on extension -->
                                        @php
                                            $extension = pathinfo($file->original_filename, PATHINFO_EXTENSION);
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
                                        
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $file->original_filename }}</span>
                                    </div>
                                    <a href="{{ route('files.download', $file) }}" class="ml-2 inline-flex items-center rounded-lg bg-green-100 px-3 py-1.5 text-xs font-medium text-green-700 hover:bg-green-200 dark:bg-green-900 dark:text-green-300 dark:hover:bg-green-800">
                                        <svg class="mr-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                        Download
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                            
                            <div class="mt-4 rounded-lg bg-blue-50 p-4 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                <div class="flex items-center">
                                    <svg class="mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="font-medium">Submission Note:</span>
                                </div>
                                <p class="mt-2 whitespace-pre-line text-sm">{{ $order->submission_note ?? 'The writer has completed your order as per your requirements. Please review the submitted files and let us know if you need any revisions.' }}</p>
                            </div>
                        @else
                            <div class="flex items-center justify-center py-8">
                                <p class="text-gray-500 dark:text-gray-400">The completed work files will appear here once the writer submits them.</p>
                            </div>
                        @endif
                    </div>
                </div>
                @endif
                
                <!-- Messages/Chat Section -->
                <div class="mb-6">
                    <div class="mb-2 flex items-center justify-between">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Order Communication</h4>
                        <span class="text-sm text-gray-600 dark:text-gray-400">{{ isset($messages) && is_countable($messages) ? count($messages) : 0 }} messages</span>
                    </div>
                    
                    <div class="mb-4 h-96 overflow-y-auto rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800">
                        @forelse(isset($messages) ? $messages : [] as $message)
                            <div class="mb-4 flex @if($message->sender_type == 'client') justify-end @endif">
                                <div class="max-w-3/4 rounded-lg @if($message->sender_type == 'client') bg-blue-600 text-white @else bg-white text-gray-900 dark:bg-gray-700 dark:text-white @endif p-3 shadow">
                                    <div class="mb-1 flex items-center justify-between">
                                        <span class="text-xs font-medium @if($message->sender_type == 'client') text-blue-100 @else text-gray-600 dark:text-gray-400 @endif">
                                            {{ ucfirst($message->sender_type) }}: {{ $message->sender_type == 'client' ? 'You' : $order->writer->name }}
                                        </span>
                                        <span class="text-xs @if($message->sender_type == 'client') text-blue-100 @else text-gray-500 dark:text-gray-400 @endif">
                                            {{ $message->created_at->format('M d, Y g:i A') }}
                                        </span>
                                    </div>
                                    <p class="whitespace-pre-line text-sm">{{ $message->content }}</p>
                                    
                                    @if($message->has_attachment)
                                        <div class="mt-2 flex items-center rounded-lg @if($message->sender_type == 'client') bg-blue-700 @else bg-gray-100 dark:bg-gray-600 @endif p-2">
                                            <svg class="mr-2 h-4 w-4 @if($message->sender_type == 'client') text-blue-100 @else text-gray-500 dark:text-gray-400 @endif" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                            </svg>
                                            <a href="{{ route('messages.attachment', $message) }}" class="text-xs @if($message->sender_type == 'client') text-blue-100 hover:text-white @else text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 @endif">
                                                {{ $message->attachment_name }}
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="flex h-full items-center justify-center">
                                <p class="text-gray-500 dark:text-gray-400">No messages yet. Start the conversation with your writer!</p>
                            </div>
                        @endforelse
                    </div>
                    
                    <form action="{{ route('client.messages.store', $order) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="message" class="sr-only">Your message</label>
                            <textarea id="message" name="message" rows="3" class="block w-full rounded-lg border border-gray-300 bg-white p-2.5 text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500" placeholder="Type your message here..."></textarea>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2 rtl:space-x-reverse">
                                <label for="file_attachment" class="cursor-pointer rounded-lg bg-gray-50 p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-900 dark:bg-gray-700 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                    </svg>
                                    <span class="sr-only">Attach file</span>
                                </label>
                                <input id="file_attachment" name="attachment" type="file" class="hidden">
                                <span id="file_name" class="text-sm text-gray-500 dark:text-gray-400"></span>
                            </div>
                            <button type="submit" class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-center text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-700 dark:hover:bg-blue-800 dark:focus:ring-blue-800">
                                <svg class="mr-2 -ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                </svg>
                                Send
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Revision Request Modal -->
    <div id="revisionModal" tabindex="-1" aria-hidden="true" class="fixed left-0 right-0 top-0 z-50 hidden h-[calc(100%-1rem)] max-h-full w-full overflow-y-auto overflow-x-hidden p-4 md:inset-0">
        <div class="relative max-h-full w-full max-w-md">
            <div class="relative rounded-lg bg-white shadow dark:bg-gray-700">
                <button type="button" class="absolute right-2.5 top-3 ml-auto inline-flex h-8 w-8 items-center justify-center rounded-lg bg-transparent text-sm text-gray-400 hover:bg-gray-200 hover:text-gray-900 dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="revisionModal">
                    <svg class="h-3 w-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
                <div class="px-6 py-6 lg:px-8">
                    <h3 class="mb-4 text-xl font-medium text-gray-900 dark:text-white">Request Revision</h3>
                    <form class="space-y-6" action="{{ route('client.orders.request-revision', $order) }}" method="POST">
                        @csrf
                        <div>
                            <label for="revision_instructions" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Revision Instructions</label>
                            <textarea id="revision_instructions" name="revision_instructions" rows="4" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500" placeholder="Describe in detail what needs to be revised..." required></textarea>
                        </div>
                        <button type="submit" class="w-full rounded-lg bg-blue-600 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-700 dark:hover:bg-blue-800 dark:focus:ring-blue-800">Submit Revision Request</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Display selected filename when attaching a file
        document.getElementById('file_attachment').addEventListener('change', function() {
            const fileNameElement = document.getElementById('file_name');
            if (this.files.length > 0) {
                fileNameElement.textContent = this.files[0].name;
            } else {
                fileNameElement.textContent = '';
            }
        });
        
        // Scroll to bottom of message container on page load
        document.addEventListener('DOMContentLoaded', function() {
            const messageContainer = document.querySelector('.overflow-y-auto');
            messageContainer.scrollTop = messageContainer.scrollHeight;
        });
    </script>
</x-client-layout> 