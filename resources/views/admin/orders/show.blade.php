<x-admin-layout>
    <x-slot:header>
        Order Details <span class="ml-2 text-base font-normal">#{{ $order->order_number ?? $order->id }}</span>
    </x-slot:header>
    
    <!-- Order Summary Card -->
    <div class="mb-6 rounded-lg border border-gray-200 bg-white p-6 shadow dark:border-gray-700 dark:bg-zinc-900">
        <div class="mb-4 flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $order->title }}</h2>
            <span class="rounded-full px-3 py-1 text-sm font-medium 
                @if($order->status == 'completed') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                @elseif($order->status == 'in_progress') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300
                @elseif($order->status == 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                @elseif($order->status == 'revision') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300
                @elseif($order->status == 'disputed') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                @endif">
                {{ ucfirst(str_replace('_', ' ', $order->status ?? 'unknown')) }}
            </span>
        </div>
        
        <div class="mb-6 grid gap-4 md:grid-cols-3">
            <div>
                <h3 class="mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300">Order Details</h3>
                <ul class="space-y-2 text-sm">
                    <li><span class="font-medium">Subject:</span> {{ $order->subject_area }}</li>
                    <li><span class="font-medium">Academic Level:</span> {{ ucfirst($order->academic_level) }}</li>
                    <li><span class="font-medium">Word Count:</span> {{ number_format($order->word_count) }}</li>
                    <li><span class="font-medium">Price:</span> ${{ number_format($order->price, 2) }}</li>
                    <li><span class="font-medium">Deadline:</span> 
                        {{ $order->deadline instanceof \DateTime || $order->deadline instanceof \Carbon\Carbon ? $order->deadline->format('M d, Y g:i A') : ($order->deadline ?: 'N/A') }}
                    </li>
                </ul>
            </div>
            
            <div>
                <h3 class="mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300">Client Information</h3>
                <ul class="space-y-2 text-sm">
                    <li><span class="font-medium">Name:</span> {{ $order->client->name ?? 'N/A' }}</li>
                    <li><span class="font-medium">Email:</span> {{ $order->client->email ?? 'N/A' }}</li>
                    <li><span class="font-medium">Joined:</span> 
                        {{ $order->client && $order->client->created_at ? $order->client->created_at->format('M d, Y') : 'N/A' }}
                    </li>
                </ul>
            </div>
            
            <div>
                <h3 class="mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300">Writer Information</h3>
                @if($order->writer)
                    <ul class="space-y-2 text-sm">
                        <li><span class="font-medium">Name:</span> {{ $order->writer->name }}</li>
                        <li><span class="font-medium">Email:</span> {{ $order->writer->email }}</li>
                        <li><span class="font-medium">Joined:</span> {{ $order->writer->created_at->format('M d, Y') }}</li>
                    </ul>
                @else
                    <div class="rounded-lg bg-gray-100 p-3 text-sm dark:bg-gray-700">
                        <p class="text-gray-700 dark:text-gray-300">No writer assigned</p>
                        
                        <button type="button" class="mt-2 rounded-lg bg-blue-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-500 dark:hover:bg-blue-600 dark:focus:ring-blue-800" data-modal-target="assignWriterModal" data-modal-toggle="assignWriterModal">
                            Assign Writer
                        </button>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Order Description -->
        <div class="mb-6">
            <h3 class="mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300">Order Description</h3>
            <div class="rounded-lg bg-gray-50 p-4 dark:bg-gray-700">
                <div class="prose max-w-none dark:prose-invert">
                    {{ $order->description }}
                </div>
            </div>
        </div>
        
        <!-- Action Buttons -->
        <div class="flex flex-wrap gap-3">
            @if($order->writer)
                <button type="button" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-500 dark:hover:bg-blue-600 dark:focus:ring-blue-800" data-modal-target="changeStatusModal" data-modal-toggle="changeStatusModal">
                    Change Status
                </button>
            @else
                <button type="button" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-500 dark:hover:bg-blue-600 dark:focus:ring-blue-800" data-modal-target="assignWriterModal" data-modal-toggle="assignWriterModal">
                    Assign Writer
                </button>
            @endif
            
            <a href="{{ route('admin.orders.index') }}" class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-700">
                Back to Orders
            </a>
        </div>
    </div>
    
    <!-- Attached Files Section -->
    <div class="mb-6 rounded-lg border border-gray-200 bg-white p-6 shadow dark:border-gray-700 dark:bg-zinc-900">
        <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Attached Files</h3>
        
        @if(count($order->files) > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-4 py-3">Filename</th>
                            <th scope="col" class="px-4 py-3">Uploaded By</th>
                            <th scope="col" class="px-4 py-3">Date Uploaded</th>
                            <th scope="col" class="px-4 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->files as $file)
                            <tr class="border-b dark:border-gray-700">
                                <td class="px-4 py-3">{{ $file->original_name }}</td>
                                <td class="px-4 py-3">{{ $file->uploaded_by ? $file->uploader->name : 'System' }}</td>
                                <td class="px-4 py-3">{{ $file->created_at->format('M d, Y g:i A') }}</td>
                                <td class="px-4 py-3">
                                    <a href="{{ route('files.download', $file) }}" class="font-medium text-blue-600 hover:underline dark:text-blue-400">Download</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 text-center dark:border-gray-700 dark:bg-gray-800">
                <p class="text-gray-500 dark:text-gray-400">No files attached to this order.</p>
            </div>
        @endif
    </div>
    
    <!-- Modals -->
    <!-- Assign Writer Modal -->
    <div id="assignWriterModal" tabindex="-1" aria-hidden="true" class="fixed left-0 right-0 top-0 z-50 hidden h-[calc(100%-1rem)] max-h-full w-full overflow-y-auto overflow-x-hidden p-4 md:inset-0">
        <div class="relative max-h-full w-full max-w-md">
            <div class="relative rounded-lg bg-white shadow dark:bg-gray-700">
                <div class="flex items-center justify-between rounded-t border-b p-4 dark:border-gray-600">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Assign Writer
                    </h3>
                    <button type="button" class="ml-auto inline-flex h-8 w-8 items-center justify-center rounded-lg bg-transparent text-sm text-gray-400 hover:bg-gray-200 hover:text-gray-900 dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="assignWriterModal">
                        <svg class="h-3 w-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <form action="{{ route('admin.orders.assign-writer', $order) }}" method="POST">
                    @csrf
                    <div class="p-6">
                        <label for="writer_id" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Select Writer</label>
                        <select id="writer_id" name="writer_id" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500" required>
                            <option value="">Choose a writer</option>
                            @foreach($availableWriters as $writer)
                                <option value="{{ $writer->id }}">{{ $writer->name }} ({{ $writer->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-center space-x-2 rounded-b border-t border-gray-200 p-6 dark:border-gray-600">
                        <button type="submit" class="rounded-lg bg-blue-700 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Assign</button>
                        <button type="button" class="rounded-lg border border-gray-200 bg-white px-5 py-2.5 text-sm font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-900 focus:z-10 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:border-gray-500 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="assignWriterModal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Change Status Modal -->
    <div id="changeStatusModal" tabindex="-1" aria-hidden="true" class="fixed left-0 right-0 top-0 z-50 hidden h-[calc(100%-1rem)] max-h-full w-full overflow-y-auto overflow-x-hidden p-4 md:inset-0">
        <div class="relative max-h-full w-full max-w-md">
            <div class="relative rounded-lg bg-white shadow dark:bg-gray-700">
                <div class="flex items-center justify-between rounded-t border-b p-4 dark:border-gray-600">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Change Order Status
                    </h3>
                    <button type="button" class="ml-auto inline-flex h-8 w-8 items-center justify-center rounded-lg bg-transparent text-sm text-gray-400 hover:bg-gray-200 hover:text-gray-900 dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="changeStatusModal">
                        <svg class="h-3 w-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <form action="{{ route('admin.orders.change-status', $order) }}" method="POST">
                    @csrf
                    <div class="p-6">
                        <label for="status" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">New Status</label>
                        <select id="status" name="status" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500" required>
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="assigned" {{ $order->status == 'assigned' ? 'selected' : '' }}>Assigned</option>
                            <option value="in_progress" {{ $order->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="revision" {{ $order->status == 'revision' ? 'selected' : '' }}>Revision</option>
                            <option value="disputed" {{ $order->status == 'disputed' ? 'selected' : '' }}>Disputed</option>
                        </select>
                    </div>
                    <div class="flex items-center space-x-2 rounded-b border-t border-gray-200 p-6 dark:border-gray-600">
                        <button type="submit" class="rounded-lg bg-blue-700 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Update Status</button>
                        <button type="button" class="rounded-lg border border-gray-200 bg-white px-5 py-2.5 text-sm font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-900 focus:z-10 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:border-gray-500 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="changeStatusModal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.0.0/flowbite.min.js"></script>
</x-admin-layout> 