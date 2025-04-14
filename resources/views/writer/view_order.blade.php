<x-layout-writer>
    <x-slot:header>Order Details</x-slot:header>
    
    <div class="mb-6">
        <div class="rounded-lg border border-gray-200 bg-white p-6 shadow dark:border-gray-700 dark:bg-zinc-900">
            <div class="mb-6 flex flex-col justify-between gap-4 md:flex-row md:items-center">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    {{ $order->title }}
                    <span class="ml-2 text-base font-normal text-gray-500">#{{ $order->order_number ?? $order->id }}</span>
                </h3>
                <div class="flex gap-2">
                    <a href="{{ route('writer.available-orders') }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-center text-sm font-medium text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:hover:border-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-700">
                        <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to Available Orders
                    </a>
                    <form action="{{ route('writer.assignments.claim', $order) }}" method="POST">
                        @csrf
                        <button type="submit" class="inline-flex items-center rounded-lg bg-green-600 px-4 py-2 text-center text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-4 focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                            <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Claim This Order
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Order Summary -->
            <div class="mb-6 grid gap-6 md:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800">
                    <h4 class="mb-2 text-sm font-medium text-gray-900 dark:text-white">Subject Area</h4>
                    <p class="text-base text-gray-700 dark:text-gray-300">{{ ucfirst(str_replace('_', ' ', $order->subject_area)) }}</p>
                </div>
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800">
                    <h4 class="mb-2 text-sm font-medium text-gray-900 dark:text-white">Academic Level</h4>
                    <p class="text-base text-gray-700 dark:text-gray-300">{{ ucfirst($order->academic_level) }}</p>
                </div>
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800">
                    <h4 class="mb-2 text-sm font-medium text-gray-900 dark:text-white">Deadline</h4>
                    <p class="text-base text-gray-700 dark:text-gray-300">{{ $order->deadline->format('M d, Y g:i A') }}</p>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        {{ $order->deadline->diffForHumans() }}
                    </p>
                </div>
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800">
                    <h4 class="mb-2 text-sm font-medium text-gray-900 dark:text-white">Payment</h4>
                    <p class="text-base text-gray-700 dark:text-gray-300">${{ number_format($order->writer_payment ?? 0, 2) }}</p>
                </div>
            </div>
            
            <!-- Order Description -->
            <div class="mb-6">
                <h4 class="mb-3 text-sm font-medium text-gray-900 dark:text-white">Order Description</h4>
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800">
                    <div class="prose max-w-none dark:prose-invert">
                        {{ $order->description }}
                    </div>
                </div>
            </div>
            
            <!-- Instruction Files -->
            @if($order->files->where('file_category', 'instruction')->count() > 0)
                <div class="mb-6">
                    <h4 class="mb-3 text-sm font-medium text-gray-900 dark:text-white">Instruction Files</h4>
                    <div class="grid gap-2 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach($order->files->where('file_category', 'instruction') as $file)
                            <div class="flex rounded-lg border border-gray-200 bg-gray-50 p-3 dark:border-gray-700 dark:bg-gray-800">
                                <div class="mr-3 flex h-10 w-10 items-center justify-center rounded-lg bg-blue-50 dark:bg-blue-900/20">
                                    <svg class="h-5 w-5 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="truncate text-sm font-medium text-blue-600 dark:text-blue-400">
                                        {{ $file->file_name }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ \Illuminate\Support\Facades\Storage::size('public/' . $file->file_path) / 1024 < 1024 
                                            ? round(\Illuminate\Support\Facades\Storage::size('public/' . $file->file_path) / 1024, 2) . ' KB' 
                                            : round(\Illuminate\Support\Facades\Storage::size('public/' . $file->file_path) / 1024 / 1024, 2) . ' MB' 
                                        }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
            
            <!-- Claim Button -->
            <div class="mt-6 border-t border-gray-200 pt-6 dark:border-gray-700">
                <div class="flex flex-col items-center justify-center gap-4">
                    <p class="text-center text-sm text-gray-600 dark:text-gray-400">
                        By claiming this order, you agree to complete it by the deadline. Make sure you understand the requirements before proceeding.
                    </p>
                    <form action="{{ route('writer.assignments.claim', $order) }}" method="POST">
                        @csrf
                        <button type="submit" class="inline-flex items-center rounded-lg bg-green-600 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-4 focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                            <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Claim This Order
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layout-writer> 