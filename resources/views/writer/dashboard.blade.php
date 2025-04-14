<x-layout-writer>
    <x-slot:header>Writer Dashboard</x-slot:header>
    
    <!-- Current Status Banner -->
    <div class="mb-6 rounded-lg border border-gray-200 bg-white p-4 shadow dark:border-gray-700 dark:bg-zinc-900">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="mr-4 flex h-12 w-12 items-center justify-center rounded-full {{ $writer->available ? 'bg-green-100 text-green-600 dark:bg-green-900 dark:text-green-300' : 'bg-amber-100 text-amber-600 dark:bg-amber-900 dark:text-amber-300' }}">
                    @if($writer->available)
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    @endif
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                        You are currently <span class="{{ $writer->available ? 'text-green-600 dark:text-green-400' : 'text-amber-600 dark:text-amber-400' }}">{{ $writer->available ? 'Available' : 'Busy' }}</span>
                    </h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        {{ $writer->available ? 'You can receive new assignments' : 'You will not be assigned new orders until you change your status' }}
                    </p>
                </div>
            </div>
            <form action="{{ route('writer.toggleAvailability') }}" method="POST">
                @csrf
                <button type="submit" class="rounded-lg {{ $writer->available ? 'bg-amber-100 text-amber-800 hover:bg-amber-200 dark:bg-amber-900 dark:text-amber-300 dark:hover:bg-amber-800' : 'bg-green-100 text-green-800 hover:bg-green-200 dark:bg-green-900 dark:text-green-300 dark:hover:bg-green-800' }} px-4 py-2 text-sm font-medium transition">
                    {{ $writer->available ? 'Mark as Busy' : 'Mark as Available' }}
                </button>
            </form>
        </div>
    </div>
    
    <!-- Stats Overview -->
    <div class="mb-6 grid gap-6 sm:grid-cols-2 xl:grid-cols-4">
        <!-- Active Assignments -->
        <div class="rounded-lg border border-gray-200 bg-white p-4 shadow dark:border-gray-700 dark:bg-zinc-900">
            <div class="flex items-center">
                <div class="mr-4 inline-flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 dark:text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Active Assignments</p>
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['active_assignments'] ?? 0 }}</h2>
                </div>
            </div>
        </div>

        <!-- Completed Orders -->
        <div class="rounded-lg border border-gray-200 bg-white p-4 shadow dark:border-gray-700 dark:bg-zinc-900">
            <div class="flex items-center">
                <div class="mr-4 inline-flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-lg bg-green-100 dark:bg-green-900">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600 dark:text-green-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Completed Orders</p>
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['completed_orders'] ?? 0 }}</h2>
                </div>
            </div>
        </div>

        <!-- Satisfaction Rate -->
        <div class="rounded-lg border border-gray-200 bg-white p-4 shadow dark:border-gray-700 dark:bg-zinc-900">
            <div class="flex items-center">
                <div class="mr-4 inline-flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-lg bg-amber-100 dark:bg-amber-900">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-600 dark:text-amber-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Satisfaction Rate</p>
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['satisfaction_rate'] ?? 0 }}%</h2>
                </div>
            </div>
        </div>

        <!-- Total Earnings -->
        <div class="rounded-lg border border-gray-200 bg-white p-4 shadow dark:border-gray-700 dark:bg-zinc-900">
            <div class="flex items-center">
                <div class="mr-4 inline-flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-lg bg-purple-100 dark:bg-purple-900">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600 dark:text-purple-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Monthly Earnings</p>
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">${{ number_format($stats['monthly_earnings'] ?? 0, 2) }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Current Assignments -->
    <div class="mb-6 rounded-lg border border-gray-200 bg-white p-6 shadow dark:border-gray-700 dark:bg-zinc-900">
        <div class="mb-4 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Current Assignments</h3>
            <a href="{{ route('writer.assignments.index') }}" class="text-sm font-medium text-blue-600 hover:underline dark:text-blue-400">View All</a>
        </div>
        
        @if(count($currentAssignments ?? []) > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-4 py-3">Order ID</th>
                            <th scope="col" class="px-4 py-3">Title</th>
                            <th scope="col" class="px-4 py-3">Subject</th>
                            <th scope="col" class="px-4 py-3">Pages</th>
                            <th scope="col" class="px-4 py-3">Due Date</th>
                            <th scope="col" class="px-4 py-3">Time Left</th>
                            <th scope="col" class="px-4 py-3">Status</th>
                            <th scope="col" class="px-4 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($currentAssignments as $assignment)
                            <tr class="border-b dark:border-gray-700">
                                <td class="whitespace-nowrap px-4 py-3">{{ $assignment->order_number }}</td>
                                <td class="px-4 py-3">{{ Str::limit($assignment->title, 30) }}</td>
                                <td class="px-4 py-3">{{ ucfirst(str_replace('_', ' ', $assignment->subject)) }}</td>
                                <td class="px-4 py-3">{{ $assignment->page_count }}</td>
                                <td class="whitespace-nowrap px-4 py-3">{{ $assignment->deadline->format('M d, Y g:i A') }}</td>
                                <td class="px-4 py-3">
                                    @php
                                        $hoursLeft = now()->diffInHours($assignment->deadline, false);
                                        $urgentClass = '';
                                        if ($hoursLeft < 12) $urgentClass = 'text-red-600 dark:text-red-400 font-semibold';
                                        elseif ($hoursLeft < 24) $urgentClass = 'text-amber-600 dark:text-amber-400';
                                    @endphp
                                    <span class="{{ $urgentClass }}">
                                        @if($hoursLeft < 0)
                                            Overdue
                                        @elseif($hoursLeft < 24)
                                            {{ $hoursLeft }} hours
                                        @else
                                            {{ ceil($hoursLeft / 24) }} days
                                        @endif
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="rounded-full px-2 py-1 text-xs font-medium 
                                        @if($assignment->status == 'completed') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                        @elseif($assignment->status == 'in_progress') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300
                                        @elseif($assignment->status == 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                                        @elseif($assignment->status == 'revision') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300
                                        @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $assignment->status)) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <a href="{{ route('writer.assignments.show', $assignment) }}" class="font-medium text-blue-600 hover:underline dark:text-blue-400">Work on it</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="flex flex-col items-center justify-center rounded-lg border border-dashed border-gray-300 bg-gray-50 py-8 text-center dark:border-gray-600 dark:bg-gray-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="mb-3 h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="mb-2 text-lg font-medium text-gray-900 dark:text-white">No current assignments</h3>
                <p class="mb-4 text-gray-500 dark:text-gray-400">You have no active assignments at the moment.</p>
                <a href="{{ route('writer.orders.available') }}" class="rounded-lg bg-blue-600 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-700 dark:hover:bg-blue-800">
                    Browse available orders
                </a>
            </div>
        @endif
    </div>

    <!-- Available Orders -->
    <div class="rounded-lg border border-gray-200 bg-white p-6 shadow dark:border-gray-700 dark:bg-zinc-900">
        <div class="mb-4 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Available Orders</h3>
            <a href="{{ route('writer.orders.available') }}" class="text-sm font-medium text-blue-600 hover:underline dark:text-blue-400">View All</a>
        </div>
        
        @if(count($availableOrders ?? []) > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-4 py-3">Order ID</th>
                            <th scope="col" class="px-4 py-3">Title</th>
                            <th scope="col" class="px-4 py-3">Subject</th>
                            <th scope="col" class="px-4 py-3">Pages</th>
                            <th scope="col" class="px-4 py-3">Deadline</th>
                            <th scope="col" class="px-4 py-3">Payment</th>
                            <th scope="col" class="px-4 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($availableOrders as $order)
                            <tr class="border-b dark:border-gray-700">
                                <td class="whitespace-nowrap px-4 py-3">{{ $order->order_number }}</td>
                                <td class="px-4 py-3">{{ Str::limit($order->title, 30) }}</td>
                                <td class="px-4 py-3">{{ ucfirst(str_replace('_', ' ', $order->subject)) }}</td>
                                <td class="px-4 py-3">{{ $order->page_count }}</td>
                                <td class="whitespace-nowrap px-4 py-3">{{ $order->deadline->format('M d, Y g:i A') }}</td>
                                <td class="whitespace-nowrap px-4 py-3">${{ number_format($order->writer_payment, 2) }}</td>
                                <td class="whitespace-nowrap px-4 py-3">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('writer.orders.view', $order) }}" class="font-medium text-blue-600 hover:underline dark:text-blue-400">View</a>
                                        <form action="{{ route('writer.orders.claim', $order) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="font-medium text-green-600 hover:underline dark:text-green-400">Claim</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 text-center dark:border-gray-700 dark:bg-gray-800">
                <p class="text-gray-500 dark:text-gray-400">No available orders match your expertise at the moment.</p>
            </div>
        @endif
    </div>
</x-layout-writer> 